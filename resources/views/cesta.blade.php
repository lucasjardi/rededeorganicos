@extends('layouts.app')

@section('content')
<div class="container">
    <div class="shadow-lg p-4 mb-5 bg-white rounded" style="padding: 50px">
      <h1><i class="fas fa-shopping-basket"></i> Minha cesta</h1>
        @if ($errors->any())
          <div class="alert alert-danger alert-dismissible fade show">
              <ul>
                  @foreach ($errors->all() as $error)
                      <li>{{ $error }} 
                        @if ($errors->has('local_de_retirada'))
                          <a href="{{url('/home')}}">Selecionar Local</a>
                        @endif
                      </li>
                  @endforeach
              </ul>
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
              </button>
          </div>
        @endif
      <br>
      <table class="table table-responsive-md">
        <thead>
          <tr>
            <th scope="col">Descrição</th>
            <th scope="col">Valor</th>
            <th><a href="{{ url('/cesta/limpar') }}" class="btn btn-default btn-sm">Esvaziar Cesta</a></th>
          </tr>
        </thead>
        <tbody>
          @foreach($cesta as $c)
          <tr>
            <th scope="row">{{$c->quantidade}} {{$c->unidade}} de {{$c->produto->nome}}</th>
            <td>R$ @dinheiro($c->subtotal)</td>
            <td>
              {!! Form::open(['method' => 'DELETE', 'url' => 'cesta/'.$c->id, 'style' => 'display: inline']) !!}
                <button type="submit" class="btn btn-danger btn-sm">Remover</button>
              {!! Form::close() !!}
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>

      <hr>
      @if(Session::has('local_not_visible'))
        <div class="alert alert-danger mt-3">
          {{ Session::get('local_not_visible') }}
        </div>
      @endif

      @if (!isset($destino))
          <div class="alert alert-warning">
            Você ainda não escolheu seu local de retirada. 
            O pedido só poderá ser solicitado após a escolha do mesmo.
            <a href="{{ route('home') }}" style="color:inherit"><b><u>Clique aqui para Escolher</u></b></a>
          </div>
      @endif

      @if(isset($destino) && count($cesta) > 0)
      <b>Local de Retirada:</b> {{$destino->descricao}} <small class="text-secondary font-italic">(Para mudar o local de retirada, vá até o <a class="text-underline" href="{{ route('home') }}"><u>Início</u></a> e mude na caixa de seleção)</small><br>
      <b>Subtotal:</b> R$ {{number_format($subtotal, 2, ',', '.')}} <br>
      @if($desconto>0)<b>Desconto:</b> R$ {{number_format($desconto, 2, ',', '.')}}<br>@endif
      <h4><b>Total:</b>&nbsp;R$ {{number_format($total, 2, ',', '.')}}</h4>
      <br>

      <form action="/solicitarPedido" method="POST">
        @csrf
        <div class="form-group">
          <label for="exampleFormControlTextarea1">Observações (opcional)</label>
          <textarea name="descricao" class="form-control" id="exampleFormControlTextarea1" rows="3" placeholder="Deixe aqui sua observação"></textarea>
        </div>
        <input type="hidden" name="local_de_retirada" value="{{$destino->codigo}}">
        <input type="hidden" name="total" value="{{$total}}">
        <button class="btn btn-success" type="submit">Solicitar Pedido</button>
      </form>
      @endif

    </div>
</div>
@endsection

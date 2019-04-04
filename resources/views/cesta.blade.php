@extends('layouts.app')

@section('content')
<div class="container">
    <div class="shadow-lg p-4 mb-5 bg-white rounded" style="padding: 50px">
      <h1 class="font-italic"><i class="fas fa-shopping-cart"></i> Finalizar Pedido</h1>
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
            <th scope="col">SubTotal</th>
            <th><a href="{{ url('/cesta/limpar') }}" class="btn btn-default btn-sm">Esvaziar Carrinho</a></th>
          </tr>
        </thead>
        <tbody>
          @php
            $total = 0;
          @endphp
          @foreach($cesta as $c)
          <tr>
            <th scope="row">{{$c->quantidade}} {{$c->unidade}} de {{$c->produto->nome}}</th>
            <td>R$ {{$c->subtotal}}</td>
            <td>
              {!! Form::open(['method' => 'DELETE', 'url' => 'cesta/'.$c->id, 'style' => 'display: inline']) !!}
                <button type="submit" class="btn btn-danger btn-sm">Remover</button>
              {!! Form::close() !!}
            </td>
          </tr>
          @php
            $total += $c->subtotal;
          @endphp
          @endforeach
        </tbody>
      </table>

      <hr>

      Local de Retirada: {{$destino}}
      <h4><b>Total:</b>&nbsp;R$ {{$total}}</h4>
      <form action="/solicitarPedido" method="POST">
        @csrf
        <input type="hidden" name="local_de_retirada" value="{{$destino}}">
        <button class="btn btn-success" type="submit">Solicitar Pedido</button>
      </form>

    </div>
</div>
@endsection

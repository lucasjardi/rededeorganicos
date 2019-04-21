@extends('layouts.app')

@section('content')
<div class="{{!$isMobile?'shadow-sm container p-3 rounded':''}}" style="background: rgba(255,255,255,0.2)">
  <div class="bg-white p-3">
      @if ($errors->any())
          <div class="alert alert-danger alert-dismissible fade show">
              <ul>
                  @foreach ($errors->all() as $error)
                      <li>{{ $error }}</li>
                  @endforeach
              </ul>
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
              </button>
          </div>
      @endif
      
      @if( Session::has('mensagem_sucesso') )
          <div class="alert alert-success alert-dismissible fade show">
              {{ Session::get('mensagem_sucesso') }}
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
              </button>
          </div>
      @endif


      <a href="{{ route('manutencao.pedidos') }}">« Voltar</a>

      @if( Request::is('*/editar') )
        {{ Form::model($pedido, ['method' => 'PATCH', 'url' => 'pedidos/' . $pedido->codigo]) }}
      @else
        {!! Form::open(['url' => 'pedidos']) !!}
      @endif

      <label for="nomeCliente">Cliente (Obrigatório) <small class="text-secondary">(Comece a digitar o nome do cliente para escolher)</small></label>
        <input class="form-control" id="nomeCliente" value="{{isset($pedido->usuario->name)?$pedido->usuario->name:''}}">
      {!! Form::input('hidden','codCliente',null,['id' => 'codCliente']) !!}
      {!! Form::label('codDestino','Destino (Obrigatório)') !!}
      {{ Form::select('codDestino',$destinos,null, ['class' => 'form-control']) }}
      {!! Form::label('valor','Valor (R$) (Obrigatório)') !!}
      {!! Form::input('text','valor',null, ['class' => 'form-control', 'placeholder' => '00,00']) !!}
      {!! Form::label('status','Status (Obrigatório)') !!}
      {{ Form::select('status',$statuses,null, ['class' => 'form-control']) }}

      {!! "<br>" !!}
      {!! Form::submit('Salvar', ['class' => 'btn btn-primary']) !!}
      {!! Form::close() !!}

      <hr>
      @if (!isset($pedido))
      <div class="alert alert-info">
          Você só pode adicionar itens ao pedido depois de salvar o pedido.
      </div>
      @endif
      <label><b>Itens do Pedido</b></label>
      @if (isset($pedido))
      <form action="{{ url('/itempedido/'. $pedido->codigo) }}" method="POST">
        @csrf
        <div class="row">
            <div class="col">
              <div class="form-group">
                {{-- <label>Produto (CÓDIGO): </label>
                <input class="form-control" list="produtosDataList" name="codProduto">

                <datalist id="produtosDataList">
                  @foreach ($produtos as $produto)
                    <option value="{{$produto->codigo}}">{{$produto->nome}}</option>
                  @endforeach
                </datalist> --}}
                <label>Produto: </label>
                <input class="form-control" id="nomeProduto">
                <input type="hidden" name="codProduto" id="codProduto" value="">
              </div>
            </div>
            <div class="col">
              <div class="form-group">
                <label>Quantidade: </label>
                <input class="form-control" type="text" name="quantidade">
              </div>
            </div>
            <div class="col">
              <div class="form-group">
                <label>Unidade: </label>
                <select class="form-control" name="unidade">
                  <option value="-1">Escolha uma unidade</option>
                  @foreach ($unidades as $unidade)
                    @if($unidade->descricao != "Produtor Escolhe")
                    <option value="{{$unidade->descricao}}">{{$unidade->descricao}}</option>
                    @endif
                  @endforeach
                </select>
              </div>
            </div>
            <div class="col">
              <div class="form-group">
                <label>Valor Total (R$): </label>
                <input class="form-control" type="text" name="valorTotal" id="valorItemPedido">
              </div>
            </div>

            <div class="col">
              <div class="form-group">
                <button class="btn btn-primary form-control" style="margin-top: 20px !important">Adicionar</button>
              </div>
            </div>
          </div>
        </form>
      @endif

      <table id="produtos" class="table table-striped table-hover">
        <thead class="thead-dark">
          <th scope="col">código do produto</th>
          <th scope="col">quantidade</th>
          <th scope="col">valor total (R$)</th>
          <th scope="col">descrição</th>
          <th>Ações</th>
        </thead>      
        <tbody>
          @isset ($pedido->itens)
              @foreach ($pedido->itens as $item)
                <tr>
                  <td> {{ $item->codProduto }}</td>
                  <td>{{ $item->quantidade }}</td>
                  <td>@dinheiro($item->valorTotal)</td>
                  <td>{{ $item->descricao }}</td>
                  <td>
                      <a href="{{url('/itempedido/'.$item->codigo)}}"><i class="fa fa-times text-danger"></i></a>
                  </td>
                </tr>
              @endforeach
          @endisset
        </tbody>
      </table>

      {{-- <div id="formItens"></div> --}}
  </div>
</div>
@endsection

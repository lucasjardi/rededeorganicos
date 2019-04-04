@extends('layouts.app')

@section('content')
<div class="shadow-sm container p-3 rounded" style="background: rgba(255,255,255,0.2)">
  <div class="bg-white p-5">
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

      @if ( Request::is('*/novo') )
        {!! Form::label('codCliente','Cliente') !!}
        {{ Form::select('codCliente',$clientes,null, ['class' => 'form-control']) }}
      @endif
      {!! Form::label('codDestino','Destino') !!}
      {{ Form::select('codDestino',$destinos,null, ['class' => 'form-control']) }}
      {!! Form::label('valor','Valor (R$)') !!}
      {!! Form::input('text','valor',null, ['class' => 'form-control', 'placeholder' => 'R$ 00,00']) !!}
      {!! Form::label('status','Status') !!}
      {{ Form::select('status',$statuses,null, ['class' => 'form-control']) }}

      {!! "<br><br>" !!}
      {!! Form::submit('Salvar', ['class' => 'btn btn-primary']) !!}
      {!! Form::close() !!}

      <hr>
      <label><b>Itens do Pedido</b></label>
      {{-- <button class="btn btn-primary btn-sm" id="adicionarItem" onclick="event.preventDefault();"><i class="fa fa-plus"></i> Adicionar</button> --}}

      <form action="{{ url('/itempedido/'. $pedido->codigo) }}" method="POST">
        @csrf
        <div class="row">
          <div class="col">
            <div class="form-group">
              <label>Produto (CÓDIGO): </label>
              <input class="form-control" list="produtosDataList" name="codProduto">

              <datalist id="produtosDataList">
                @foreach ($produtos as $produto)
                  <option value="{{$produto->codigo}}">{{$produto->nome}}</option>
                @endforeach
              </datalist>
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
                <option></option>
                @foreach ($unidades as $unidade)
                  <option value="{{$unidade->descricao}}">{{$unidade->descricao}}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="col">
            <div class="form-group">
              <label>Valor Total (R$): </label>
              <input class="form-control" type="text" name="valorTotal">
            </div>
          </div>

          <div class="col">
            <div class="form-group">
              <button class="btn btn-primary form-control" style="margin-top: 20px !important">Adicionar</button>
            </div>
          </div>
        </div>
      </form>

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
                  <td>{{ $item->valorTotal }}</td>
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

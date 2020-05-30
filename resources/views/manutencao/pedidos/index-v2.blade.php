@extends('layouts.app')

@section('content')
<div class="{{!$isMobile?'shadow-sm container p-3 rounded':''}}" style="background: rgba(255,255,255,0.2)">
  @if( Session::has('mensagem_sucesso') )
      <div class="alert alert-success alert-dismissible fade show">
          {{ Session::get('mensagem_sucesso') }}
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
          </button>
      </div>
  @endif
  
    <div class="bg-white pt-5 {{!$isMobile?'p-3':''}}">
     <div class="m-auto pb-3" style="{{!$isMobile?'width: 150px':''}}">
        <a href="{{ route('manutencao.novo.pedido') }}" class="btn btn-outline-primary {{$isMobile?'btn-block':''}}" style="margin-top: -40px"><i class="fa fa-plus"></i> Cadastrar Novo</a>
     </div>
      <div style="display: flex;justify-content: flex-end;margin-bottom: 8px;">
        <input type="text" class="input" placeholder="Pesquisar" id="search" name="search" value="{{Request::get('search')}}" onkeydown="search(event)" />
        <button onclick="search()" style="border: none; background: none;cursor: pointer;"><i class="fa fa-times text-danger"></i></button>
      </div>
      <table class="table table-striped table-hover">
        <thead class="thead-dark">
          <th scope="col">#</th>
          <th scope="col">Cliente</th>
          <th scope="col" id="dataPedido" onclick="orderBy('dataPedido', this)" style="cursor: pointer">
            Data do Pedido <i class="fas fa-sort" style="float: right;margin-top: 5px;"></i>
          </th>
          <th scope="col" id="valor" onclick="orderBy('valor', this)" style="cursor: pointer">
            Valor <i class="fas fa-sort" style="float: right;margin-top: 5px;"></i>
          </th>
          <th scope="col" id="status" onclick="orderBy('status', this)" style="cursor: pointer">
            Status <i class="fas fa-sort" style="float: right;margin-top: 5px;"></i>
          </th>
          <th class="text-right">Ações</th>
        </thead>
        <tbody>
          @foreach ($pedidos->items() as $pedido)
            <tr>
              <th scope="row">{{ $pedido->codigo }}</th>
              <td>{{ $pedido->usuario->name }}</td>
              <td>@datetime($pedido->dataPedido)</td>
              <td>@dinheiro($pedido->valor)</td>
              <td class="{{$pedido->status == 1 ? 'bg-analise' : 
                          ($pedido->status == 2 ? 'bg-aguardando' : 
                          ($pedido->status == 3 ? 'bg-confirmado' : 
                          'bg-cancelado'))}}">
                <span class="font-weight-bold text-uppercase">
                  {{$pedido->st->descricao}}
                </span>
              </td>
              <td class="text-right">
                <a href="{{ url('pedido/' . $pedido->codigo . '/imprimir') }}" target="_blank">
                  <i class="fa fa-print text-secondary"></i>
                </a>&nbsp;
                <a href="{{ url('manutencao/pedido/' . $pedido->codigo . '/editar') }}">
                  <i class="fa fa-edit text-primary"></i>
                </a>&nbsp;
                 {!! Form::open(['method' => 'DELETE', 'url' => 'pedidos/'.$pedido->codigo, 'style' => 'display: inline']) !!}
                <button type="submit" style="border: none; background: none;cursor: pointer;"><i class="fa fa-times text-danger"></i></button>
                {!! Form::close() !!}
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>

      <div style="display: flex;justify-content: flex-end">{{$pedidos->appends(\Request::except('page'))->links()}}</div>
  </div>
</div>

@endsection

@extends('layouts.app')

@section('content')
<div class="shadow-sm container p-3 rounded" style="background: rgba(255,255,255,0.2)">
  @if( Session::has('mensagem_sucesso') )
      <div class="alert alert-success alert-dismissible fade show">
          {{ Session::get('mensagem_sucesso') }}
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
          </button>
      </div>
  @endif
  <div class="bg-white p-5">
     <a href="{{ route('manutencao.novo.pedido') }}" class="btn btn-primary float-right" style="margin-top: -40px"><i class="fa fa-plus"></i> Cadastrar Novo</a>
      <table id="produtos" class="table table-striped table-hover">
        <thead class="thead-dark">
          <th scope="col">#</th>
          <th scope="col">Cliente</th>
          <th scope="col">Data do Pedido</th>
          <th scope="col">Valor</th>
          <th>Ações</th>
        </thead>

        <tbody>
          @foreach ($pedidos as $pedido)
            <tr>
              <th scope="row">{{ $pedido->codigo }}</th>
              <td>{{ $pedido->usuario->name }}</td>
              <td>{{ $pedido->dataPedido }}</td>
              <td>{{ $pedido->valor }}</td>
              <td>
                <a href="{{ url('manutencao/pedido/' . $pedido->codigo . '/editar') }}">
                  <i class="fa fa-edit text-primary"></i>
                </a>&nbsp;
                 {!! Form::open(['method' => 'DELETE', 'url' => 'pedidos/'.$pedido->codigo.'/remover', 'style' => 'display: inline']) !!}
                <button type="submit" style="border: none; background: none;cursor: pointer;"><i class="fa fa-times text-danger"></i></button>
                {!! Form::close() !!}
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
  </div>
</div>

@endsection

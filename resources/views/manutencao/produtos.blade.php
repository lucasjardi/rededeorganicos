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
    <a href="{{ Request::is('*/desativados')?route('manutencao.produtos'):route('manutencao.produtos.desativados') }}" class="btn btn-secondary float-right" style="margin-top: -40px; margin-right: 150px">Listar Produtos {{Request::is('*/desativados')?"Ativados":"Desativados" }}</a>
    <a href="{{ route('manutencao.novo.produto') }}" class="btn btn-primary float-right" style="margin-top: -40px"><i class="fa fa-plus"></i> Cadastrar Novo</a>
      <table id="produtos" class="table table-striped table-hover">
        <thead class="thead-dark">
          <th scope="col">#</th>
          <th scope="col">Nome</th>
          <th scope="col">Unidade</th>
          <th scope="col">Grupo</th>
          <th scope="col">Ações</th>
        </thead>

        <tbody>
          @foreach ($produtos as $produto)
            <tr>
              <th scope="row">{{ $produto->codigo }}</th>
              <td onclick="location.href='{{ url('manutencao/produto/' . $produto->codigo . '/editar') }}'"
            style="cursor: pointer">{{ $produto->nome }}</td>
              <td>{{ $produto->unidade->descricao }}</td>
              <td>{{ $produto->grupo->descricao }}</td>
              <td>
                <a href="{{ url('manutencao/produto/' . $produto->codigo . '/editar') }}">
                  <i class="fa fa-edit text-primary"></i>
                </a>&nbsp;
                 @if ($produto->ativo == 1)
                   {!! Form::open(['method' => 'PATCH', 'url' => 'produtos/'.$produto->codigo.'/desativar', 'style' => 'display: inline']) !!}
                    <button type="submit" style="border: none; background: none;cursor: pointer;"><i class="fa fa-times text-danger"></i></button>
                    {!! Form::close() !!}
                 @else
                    {!! Form::open(['method' => 'PATCH', 'url' => 'produtos/'.$produto->codigo.'/ativar', 'style' => 'display: inline', 'id' => 'formAtivar']) !!}
                    <button class="btn btn-default btn-sm" type="submit">ATIVAR</button>
                    {!! Form::close() !!}
                 @endif
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
  </div>
</div>

@endsection

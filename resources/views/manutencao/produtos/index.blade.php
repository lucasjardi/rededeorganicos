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
  <div class="bg-white p-3">
    <div style="width:{{$isMobile?'210px':'327px'}};margin: 0 auto">
      <div class="btn-group pb-3">
          <label class="btn btn-outline-dark" 
                onclick="location.href='{{ Request::is('*/desativados')?route('manutencao.produtos'):route('manutencao.produtos.desativados') }}'" >
              <i class="fa fa-list"></i> Listar {{Request::is('*/desativados')?"Ativados":"Desativados" }}
          </label>
          <label class="btn btn-outline-primary" onclick="location.href='{{ route('manutencao.novo.produto') }}'" >
              @if ($isMobile)
                <i class="fa fa-plus"></i>
              @else
                <i class="fa fa-plus"></i> Cadastrar Novo
              @endif
          </label>
      </div>
    </div>
      <table id="produtos" class="table table-striped table-hover">
        <thead class="thead-dark">
          <th scope="col">#</th>
          <th scope="col">Nome</th>
          <th scope="col">Unidade</th>
          <th scope="col">Grupo</th>
          <th class="text-right" scope="col">Ações</th>
        </thead>

        <tbody>
          @foreach ($produtos as $produto)
            <tr>
              <th scope="row">{{ $produto->codigo }}</th>
              <td onclick="location.href='{{ url('manutencao/produto/' . $produto->codigo . '/editar') }}'"
            style="cursor: pointer">{{ $produto->nome }}</td>
              <td>{{ $produto->unidade->descricao }}</td>
              <td>{{ $produto->grupo->descricao }}</td>
              <td class="text-right">
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

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
      <table id="produtos" class="table table-striped table-hover">
        <thead class="thead-dark">
          <th scope="col">#</th>
          <th scope="col">Nome</th>
          <th scope="col">E-mail</th>
          <th scope="col">Tipo de Cadastro</th>
          <th>Ações</th>
        </thead>

        <tbody>
          @foreach ($solicitacoes as $solicitacao)
            <tr>
              <th scope="row">{{ $solicitacao->codigo }}</th>
              <td>{{ $solicitacao->name }}</td>
              <td>{{ $solicitacao->email }}</td>
              <td>{{ $solicitacao->nivel == 4 ? "Produtor" : "Cliente" }}</td>
              <td>
                <a href="{{ url('solicitacao/' . $solicitacao->codigo . '/aceitar') }}">
                  <i class="fas fa-thumbs-up" title="Confirmar"></i>
                </a>&nbsp;
                 {!! Form::open(['method' => 'DELETE', 'url' => 'solicitacao/' . $solicitacao->codigo . '/rejeitar', 'style' => 'display: inline']) !!}
                <button type="submit" style="border: none; background: none;cursor: pointer;"><i class="fas fa-thumbs-down text-danger" title="Rejeitar"></i></button>
                {!! Form::close() !!}
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
  </div>
</div>

@endsection

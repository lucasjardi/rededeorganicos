@extends('layouts.app')

@section('content')
<div class="{{!$isMobile?'shadow-sm container p-3 rounded':''}}" style="background: rgba(255,255,255,0.2)">
  <div class="bg-white pt-5 {{!$isMobile?'p-3':''}}">
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
      
      <table id="produtos" class="table table-striped table-hover">
        <thead class="thead-dark">
          <th scope="col">#</th>
          <th scope="col">Nome</th>
          <th scope="col">E-mail</th>
          <th scope="col">Tipo</th>
          <th scope="col">Registro</th>
          {{-- <th scope="col">Acréscimo de Valor (R$)</th> --}}
          <th class="text-right">Ações</th>
        </thead>

        <tbody>
          @foreach ($users as $user)
          @php 
            $userDetails = new stdClass();
            $userDetails->name = $user->name;
            $userDetails->email = $user->email;
            $userDetails->tipo = $user->nivel->descricao; 
            $userDetails->criado_ha = $user->created_at->diffForHumans();
            if(isset($user->cliente) || isset($user->produtor)) {
              $userDetails->telefone = $user->codNivel == 4 ? $user->produtor->telefone : $user->cliente->telefone;
              $userDetails->endereco = $user->codNivel == 4 ? $user->produtor->endereco : $user->cliente->endereco;
              $userDetails->cidade = $user->codNivel == 4 ? $user->produtor->cidade->descricao : $user->cliente->cidade->descricao;
              if($user->codNivel==5) $userDetails->cpf = $user->cliente->cpf;
            }
          @endphp
          <tr onclick="showDetails({{json_encode($userDetails)}})" style="cursor:pointer">
            <th scope="row">{{ $user->id }}</th>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->nivel->descricao }}</td>
            <td>{{ $user->created_at->diffForHumans() }}</td>
            <td class="text-right" onclick="event.stopPropagation()">
              <a href="{{ url('manutencao/users/' . $user->id . '/editar') }}">
                <i class="fa fa-edit text-primary"></i>
              </a>&nbsp;
               {{-- {!! Form::open(['method' => 'DELETE', 'url' => 'users/'.$user->id, 'style' => 'display: inline']) !!}
              <button type="submit" style="border: none; background: none;cursor: pointer;"><i class="fa fa-times text-danger"></i></button>
              {!! Form::close() !!} --}}
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
  </div>
</div>
@endsection
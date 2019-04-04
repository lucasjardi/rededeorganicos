@extends('layouts.app')

@section('content')
<div class="logo mb-2">
        <img id="logo" src="{{ asset('img/logo.jpg') }}">
    </div>
<div class="rounded p-3 m-auto" style="width: 600px; background: rgba(255,255,255,0.9)">
    @if( Session::has('mensagem_sucesso') )
      <div class="alert alert-success alert-dismissible fade show">
          {{ Session::get('mensagem_sucesso') }}
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
          </button>
      </div>
    @else
    <form action="{{ route('solicitarCadastro') }}" method="POST" class="mt-3">
        @csrf

        <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="name">Nome:</label>
            <div class="col-sm-10">
                <input id="email" type="name" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" required>
            </div>
            @if ($errors->has('name'))
                <span id="ajuda_email" class="vermelho">
                    <strong>{{ $errors->first('name') }}</strong>
                </span>
            @endif
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="email">Email:</label>
            <div class="col-sm-10">
                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required>
            </div>
            @if ($errors->has('email'))
                <span id="ajuda_email" class="vermelho">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
            @endif
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="senha">Senha:</label>
            <div class="col-sm-10">
                <input id="email" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>
            </div>
            @if ($errors->has('password'))
                <span id="ajuda_email" class="vermelho">
                    <strong>{{ $errors->first('password') }}</strong>
                </span>
            @endif
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="senha">Confirmar Senha:</label>
            <div class="col-sm-10">
                <input id="email" type="password" class="form-control" name="password_confirmation" required>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="senha">Tipo de Cadastro: </label>
            <div class="col-sm-10">
                <select name="nivel" class="form-control">
                    <option value="5">Cliente</option>
                    <option value="4">Produtor</option>
                </select>
            </div>
        </div>
        <button class="login" type="submit">Solicitar Cadastro</button>
    </form>

    <div class="mt-3 text-center">
        <a href="{{ route('login') }}">Já possui cadastro?</a>
    </div>
     @endif
</div>
@endsection

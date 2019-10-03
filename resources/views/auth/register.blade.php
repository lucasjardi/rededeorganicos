@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row justify-content-center pb-3">
		<div class="col-md-8">
			<div class="m-auto" style="max-width: 200px">
				<img id="logo" src="{{ asset('img/logo.jpg') }}">
			</div>
		</div>
	</div>
	<div class="row justify-content-center pb-3">
		<div class="col-md-8 col-xl-6">
			<div class="rounded p-3 bg-white">
                @if( Session::has('mensagem_sucesso') )
                    <div class="alert alert-success alert-dismissible fade show">
                        {{ Session::get('mensagem_sucesso') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <a href="{{url('/')}}" class="btn btn-success btn-block">Ir para o início</a>
                @else
				<form action="{{ route('solicitarCadastro') }}" method="POST" class="mt-3">
                    @csrf
            
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label" for="name">Nome:</label>
                        <div class="col-sm-10">
                            <input id="email" type="name" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" required>
                        </div>
                        @if ($errors->has('name'))
                            <span class="col text-danger">
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
                            <span class="col text-danger">
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
                            <span class="col text-danger">
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
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label" for="cidade">Cidade: </label>
                        <div class="col-sm-10">
                            <select id="cidade" name="codCidade" class="form-control">
                                @foreach (App\Cidade::all() as $cidade)
                                    <option value="{{$cidade->codigo}}">{{$cidade->descricao}}</option>                                    
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                            <label class="col-sm-2 col-form-label" for="telefone">Telefone:</label>
                            <div class="col-sm-10">
                                <input id="telefone" type="text" class="form-control" name="telefone" required>
                            </div>
                    </div>
                    <div class="form-group row">
                            <label class="col-sm-2 col-form-label" for="endereco">Endereço:</label>
                            <div class="col-sm-10">
                                <input id="endereco" type="text" class="form-control" name="endereco" required>
                            </div>
                    </div>
                    <button class="btn btn-primary w-100" type="submit">Solicitar Cadastro</button>
                </form>

                <div class="mt-3 text-center">
                    <a href="{{ route('login') }}">Já possui cadastro?</a>
                </div>
                @endif
			</div>
		</div>
	</div>
</div>
@endsection

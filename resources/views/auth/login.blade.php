@extends('layouts.app')

@section('content')
<div class="logo mb-2">
		<img id="logo" src="{{ asset('img/logo.jpg') }}">
</div>
<div class="rounded p-3 m-auto" style="width: 400px; background: rgba(255,255,255,0.9)">
	<form action="{{ route('login') }}" method="POST">
		@csrf

		<div class="form-group row">
			<label for="email" class="col-sm-2 col-form-label">Email:</label>
			<div class="col-sm-10">
				<input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" placeholder="E-mail" required autofocus>
			</div>
			@if ($errors->has('email'))
            <span id="ajuda_email" class="vermelho">
                {{ $errors->first('email') }}
            </span>
        	@endif
		</div>
		<div class="form-group row">
			<label for="senha" class="col-sm-2 col-form-label">Senha:</label>
			<div class="col-sm-10">
				<input id="senha" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" placeholder="Senha" required>
			</div>
			@if ($errors->has('password'))
	            <span id="ajuda_email" class="vermelho">
	                {{ $errors->first('password') }}
	            </span>
	        @endif
		</div>
		<div class="row">
			<div class="col">
				<a id="esqueci" href="{{ route('password.request') }}">Esqueci a senha!</a>
			</div>
		</div>
		<div class="form-group row">
			<div class="col">
				<button class="btn btn-primary w-100" type="submit">Entrar</button>
				<div class="dropdown-divider"></div>
				<a class="btn btn-warning w-100" href="{{ route('register') }}">Cadastre-se</a>
			</div>
		</div>
	</form>
</div>
@endsection
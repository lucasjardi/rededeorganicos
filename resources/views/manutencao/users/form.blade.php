@extends('layouts.app')

@section('content')
<div class="{{!$isMobile?'shadow-sm container p-3 rounded':''}}" style="background: rgba(255,255,255,0.2)">
  <div class="bg-white p-3">
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


      <a href="{{ route('manutencao.users') }}">« Voltar</a>

      @if( Request::is('*/editar'))
        {{ Form::model($user, ['method' => 'PATCH', 'url' => 'users/' . $user->id]) }}
      @else
        {!! Form::open(['url' => 'users']) !!}
      @endif

      {!! Form::label('name','Nome (Obrigatório)') !!}
      {!! Form::input('text','name',null, ['class' => 'form-control', 'placeholder' => 'Nome do Usuario']) !!}
      {!! Form::label('email','E-mail (Obrigatório)') !!}
      {!! Form::input('text','email',null, ['class' => 'form-control', 'placeholder' => 'E-mail do Usuario']) !!}
      <hr>
      @if ($user && $user->codNivel == 4)
        {!! Form::label('produtor[cidade][codigo]','Cidade (Obrigatório)') !!}
        {!! Form::select('produtor[cidade][codigo]',$cidades, null, ['class' => 'form-control']) !!}
        {!! Form::label('produtor[telefone]','Telefone (Obrigatório)') !!}
        {!! Form::input('text','produtor[telefone]',null, ['class' => 'form-control', 'placeholder' => '(51)99999-9999']) !!}
        {!! Form::label('produtor[endereco]','Endereço (Obrigatório)') !!}
        {!! Form::input('text','produtor[endereco]',null, ['class' => 'form-control']) !!}
      @elseif ($user && $user->codNivel == 5)
        {!! Form::label('cliente[cpf]','CPF (Obrigatório)') !!}
        {!! Form::input('text','cliente[cpf]',null, ['class' => 'form-control', 'placeholder' => '000.000.000-00']) !!}
        {!! Form::label('cliente[cidade][codigo]','Cidade (Obrigatório)') !!}
        {!! Form::select('cliente[cidade][codigo]',$cidades, null, ['class' => 'form-control']) !!}
        {!! Form::label('cliente[telefone]','Telefone (Obrigatório)') !!}
        {!! Form::input('text','cliente[telefone]',null, ['class' => 'form-control', 'placeholder' => '(51)99999-9999']) !!}
        {!! Form::label('cliente[endereco]','Endereço (Obrigatório)') !!}
        {!! Form::input('text','cliente[endereco]',null, ['class' => 'form-control']) !!}
      @endif
      
      {!! "<br><br>" !!}
      {!! Form::submit('Salvar', ['class' => 'btn btn-primary']) !!}

      {!! Form::close() !!}
  </div>
</div>

@endsection
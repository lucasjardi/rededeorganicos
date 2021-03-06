@extends('layouts.app')

@section('content')
<div class="shadow-sm container p-3 rounded" style="background: rgba(255,255,255,0.2)">
  <div class="bg-light p-4 mb-3">
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
    <h1 class="font-italic">Preencher Informações</h1>

    @if ($produtor != null)
      {{ Form::model($produtor, ['method' => 'PATCH', 'url' => 'user/produtor/' . $produtor->codigo]) }}
    @elseif($cliente != null)
      {{ Form::model($cliente, ['method' => 'PATCH', 'url' => 'user/cliente/' . $cliente->codigo]) }}
    @endif

    @if ($cliente != null)
      {!! Form::label('cpf','CPF') !!}
      {!! Form::input('text','cpf',null, ['class' => 'form-control', 'placeholder' => '000.000.000-000']) !!}
    @endif
    {!! Form::label('telefone','Telefone') !!}
    {!! Form::input('text','telefone',null, ['class' => 'form-control', 'placeholder' => '(XX)XXXXX-XXXX']) !!}
    {!! Form::label('endereco','Endereço') !!}
    {!! Form::input('text','endereco',null, ['class' => 'form-control', 'placeholder' => 'Av. Tal, 222, Bairro']) !!}
    {!! Form::label('codCidade','Cidade') !!}
    {{ Form::select('codCidade',$cidades,null, ['class' => 'form-control']) }}
    {!! "<br>" !!}
    {!! Form::submit('Salvar', ['class' => 'btn btn-primary']) !!}

  </div>
</div>
@endsection

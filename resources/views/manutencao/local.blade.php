@extends('layouts.app')

@section('content')
<div class="shadow-sm container p-3 rounded" style="background: rgba(255,255,255,0.2)">
  <div class="bg-white p-5">
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


      <a href="{{ route('manutencao.locais') }}">« Voltar</a>


      @if( Request::is('*/editar'))
        {{ Form::model($destino, ['method' => 'PATCH', 'url' => 'locais/' . $destino->codigo]) }}
      @else
        {!! Form::open(['url' => 'locais/salvar']) !!}
      @endif

      {!! Form::label('descricao','Descrição') !!}
      {!! Form::input('text','descricao',null, ['class' => 'form-control', 'placeholder' => 'Nome do Local']) !!}
      {!! Form::label('acrescimo','Acréscimo') !!}
      {!! Form::input('text','acrescimo',null, ['class' => 'form-control', 'placeholder' => '0.00']) !!}
      
      {!! "<br><br>" !!}
      {!! Form::submit('Salvar', ['class' => 'btn btn-primary']) !!}

      {!! Form::close() !!}
  </div>
</div>

@endsection

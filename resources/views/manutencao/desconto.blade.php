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


      <a href="{{ route('manutencao.descontos') }}">« Voltar</a>


      @if( Request::is('*/editar'))
        {{ Form::model($desconto, ['method' => 'PATCH', 'url' => 'descontos/' . $desconto->id]) }}
      @else
        {!! Form::open(['url' => 'descontos/salvar']) !!}
      @endif

      {!! Form::label('destino_id','Destino (Obrigatório)') !!}
      {{ Form::select('destino_id',$destinos,null, ['class' => 'form-control']) }}
      {!! Form::label('porcentagem','Porcentagem % (Obrigatório)') !!}
      {!! Form::input('number','porcentagem',null, ['class' => 'form-control', 'placeholder' => '10']) !!}
      {!! Form::label('descricao','Descrição do Desconto (Opcional)') !!}
      {!! Form::input('text','descricao',null, ['class' => 'form-control', 'placeholder' => 'Nome do desconto']) !!}
      
      {!! "<br><br>" !!}
      {!! Form::submit('Salvar', ['class' => 'btn btn-primary']) !!}

      {!! Form::close() !!}
  </div>
</div>

@endsection

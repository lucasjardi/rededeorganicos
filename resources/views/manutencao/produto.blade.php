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


      <a href="{{ route('manutencao.produtos') }}">« Voltar</a>


      @if( Request::is('*/editar'))
        {{ Form::model($produto, ['method' => 'PATCH', 'url' => 'produtos/' . $produto->codigo,'files' => 'true']) }}
      @else
        {!! Form::open(['url' => 'produtos/salvar', 'files' => 'true']) !!}
      @endif

      {!! Form::label('nome','Nome') !!}
      {!! Form::input('text','nome',null, ['class' => 'form-control', 'placeholder' => 'Nome do Produto']) !!}
      {!! Form::label('codUnidade','Unidade') !!}
      {{ Form::select('codUnidade',$unidades,null, ['class' => 'form-control']) }}
      {!! Form::label('codGrupo','Grupo') !!}
      {{ Form::select('codGrupo', $grupos,null, ['class' => 'form-control']) }}
      {!! Form::label('descricao','Descrição') !!}
      {!! Form::textarea('descricao',null, ['class' => 'form-control', 'placeholder' => 'Descrição do Produto']) !!}
      {!! Form::label('imagem','Imagem do Produto') !!}
      {!! Form::file('imagem', ['class' => 'form-control', 'accept' => 'image/*']) !!}      
      @if( Request::is('*/editar'))
          <label>Foto Atual</label><br>
          @if ($produto->imagem)
            <img src="{!! url('/storage/' . $produto->imagem) !!}" style="width: 200px" />
          @else
          <span class="bg-warning p-2">Sem Foto</span>
          @endif
      @endif
      {!! "<br><br>" !!}
      {!! Form::submit('Salvar', ['class' => 'btn btn-primary']) !!}

      {!! Form::close() !!}
  </div>
</div>

@endsection

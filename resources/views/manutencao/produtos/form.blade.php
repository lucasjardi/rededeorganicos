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


      <a href="{{ route('manutencao.produtos') }}">« Voltar</a>


      @if( Request::is('*/editar'))
        {{ Form::model($produto, ['method' => 'PATCH', 'url' => 'produtos/' . $produto->codigo,'files' => 'true']) }}
      @else
        {!! Form::open(['url' => 'produtos/salvar', 'files' => 'true']) !!}
      @endif

      {!! Form::label('nome','Nome (Obrigatório)') !!}
      {!! Form::input('text','nome',null, ['class' => 'form-control', 'placeholder' => 'Nome do Produto']) !!}
      {!! Form::label('codUnidade','Unidade (Obrigatório)') !!}
      {{ Form::select('codUnidade',$unidades,null, ['class' => 'form-control']) }}
      {!! Form::label('codGrupo','Grupo (Obrigatório)') !!}
      {{ Form::select('codGrupo', $grupos,null, ['class' => 'form-control']) }}
      <a href="" id="criarNovoGrupo">Criar novo grupo</a><br><br>
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
      {!! "<br>" !!}
      {!! Form::submit('Salvar', ['class' => 'btn btn-primary']) !!}

      {!! Form::close() !!}
  </div>
</div>

<div class="modal" tabindex="-1" role="dialog" id="criarGrupo">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Novo Grupo</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              
              <div class="form-group">
                  <label for="descricao">Nome do Grupo: </label>
                  <input type="text" id="grupoDescricao" class="form-control" placeholder="Chás">
              </div>
            </div>
            <div class="modal-footer">
              <button id="btnSalvarGrupo" type="button" class="btn btn-primary">Salvar</button>
            </div>
          </div>
        </div>
      </div>
@endsection

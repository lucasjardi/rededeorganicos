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


        {{ Form::model($horariosAcessoProdutor, ['method' => 'PATCH', 'url' => 'horarioacessoprodutor']) }}

        <h3>Horários Permitidos para Produtor</h3>
        <br>
        <div class="row">
            <div class="col">
            {!! Form::select('diaInicio',[1 => 'Domingo',2 => 'Segunda-Feira',3 => 'Terça-Feira',4 => 'Quarta-Feira',5 => 'Quinta-Feira',6 => 'Sexta-Feira',7 => 'Sábado'],null, ['class' => 'form-control']) !!}
            </div>
            <div class="col">
            {!! Form::input('time','horasInicio',null,['class' => 'form-control']) !!}
            </div>
            <div class="col text-center" style="font-size: 1.5rem">
                <b>ATÉ</b>
            </div>
            <div class="col">
            {!! Form::select('diaFim',[1 => 'Domingo',2 => 'Segunda-Feira',3 => 'Terça-Feira',4 => 'Quarta-Feira',5 => 'Quinta-Feira',6 => 'Sexta-Feira',7 => 'Sábado'],null, ['class' => 'form-control']) !!}
            </div>
            <div class="col">
            {!! Form::input('time','horasFim',null,['class' => 'form-control']) !!} 
            </div>
        </div>

        {!! "<br><br>" !!}
        {!! Form::submit('Salvar', ['class' => 'btn btn-primary btn-block']) !!}

        {!! Form::close() !!}
  </div>
</div>

@endsection
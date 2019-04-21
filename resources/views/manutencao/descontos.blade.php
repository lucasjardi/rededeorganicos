@extends('layouts.app')

@section('content')
<div class="{{!$isMobile?'shadow-sm container p-3 rounded':''}}" style="background: rgba(255,255,255,0.2)">
  <div class="bg-white pt-5 {{!$isMobile?'p-3':''}}">
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
      
      <div class="m-auto pb-3" style="{{!$isMobile?'width: 150px':''}}">
          <a href="{{ route('manutencao.novo.desconto') }}" class="btn btn-outline-primary {{$isMobile?'btn-block':''}}" style="margin-top: -40px"><i class="fa fa-plus"></i> Cadastrar Novo</a>
       </div>
      <table id="produtos" class="table table-striped table-hover">
        <thead class="thead-dark">
          <th scope="col">#</th>
          <th scope="col">Destino</th>
          <th scope="col">Porcentagem %</th>
          <th scope="col">Descrição</th>
          <th class="text-right">Ações</th>
        </thead>

        <tbody>
          @foreach ($descontos as $desconto)
            <tr>
              <th scope="row">{{ $desconto->id }}</th>
              <td>{{$desconto->destino->descricao}}</td>
              <td>{{$desconto->porcentagem}}</td>
              <td>{{ $desconto->descricao }}</td>
              <td class="text-right">
                <a href="{{ url('manutencao/desconto/' . $desconto->codigo . '/editar') }}">
                  <i class="fa fa-edit text-primary"></i>
                </a>&nbsp;
                 {!! Form::open(['method' => 'DELETE', 'url' => 'descontos/'.$desconto->codigo, 'style' => 'display: inline']) !!}
                <button type="submit" style="border: none; background: none;cursor: pointer;"><i class="fa fa-times text-danger"></i></button>
                {!! Form::close() !!}
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
  </div>
</div>
@endsection

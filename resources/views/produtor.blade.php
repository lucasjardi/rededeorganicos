@extends('layouts.app')

@section('content')

<div class="shadow-sm container p-3 rounded" style="background: rgba(255,255,255,0.2)">
  <div class="bg-light p-3 mb-3">
    <h1>Selecione o que você tem disponível</h1>
    <p class="font-italic">Clique nos itens para inserir as informações como Unidade e Preço. <br>
    Após ter selecionado tudo, clique no botão <b class="text-success">PRONTO</b></p>
    @if( Session::has('mensagem_sucesso') )
          <div class="alert alert-success alert-dismissible fade show">
              {{ Session::get('mensagem_sucesso') }}
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
              </button>
          </div>
    @endif
  </div>

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


  <form id="produtorForm" method="POST" action="{{ url('/produto_produzido') }}">
    @csrf

    @isset ($produtos)
    <div class="accordion" id="accordionExample">
        @forelse ($produtos as $produto)
        <card inline-template>
          <div class="card">
            <div 
            class="card-header {{array_key_exists($produto->codigo,$prodsjaselecionados) ? 'bg-light' : 'bg-white'}}" 
            id="headingOne">
              <span style="padding-left: 30px"></span>
            <input onchange="mudaEstiloCardCheckbox(this)" id="{{$produto->codigo}}" type="checkbox" name="produtos[{{$produto->codigo}}]" class="form-check-input checkboxGrandinha" style="margin-top: 13px;" :checked="{{array_key_exists($produto->codigo,$prodsjaselecionados) ? 'true' : 'false'}}">
              <h5 class="mb-0 d-inline">
                <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#produto{{$produto->codigo}}" aria-expanded="true" aria-controls="produto{{$produto->codigo}}">
                  {{-- <span style="padding-left: 50px"></span> --}}
                  {{-- <i class="fas fa-shopping-basket"></i>  --}}
                  {{$produto->nome}}
                </button>
              </h5>
              <div class="d-inline float-right mt-2" style="cursor: pointer;" onclick="location.href='/removeProdutoProdutor/{{$produto->codigo}}'">
                <span class="badge badge-secondary badge-pill">Remover do Início</span>
              </div>
            </div>

            <div id="produto{{$produto->codigo}}" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
              <div class="card-body">
                <div class="row">
                  <div class="col-md-4">
                    <label>Forma Vendida: </label>
                    <select name="unidades[{{$produto->codigo}}]" class="custom-select" {{ $produto->codUnidade !== 1 ? "readonly tabindex=-1 aria-disabled=true" : ""}}>
                      @foreach ($unidades as $unidade)
                        @if (array_key_exists($produto->codigo, $prodsjaselecionados))
                        <option value="{{$unidade->codigo}}" {{ $unidade->codigo == $prodsjaselecionados[$produto->codigo] ? "selected" : ""}}>
                          {{ $unidade->descricao }}
                        </option>
                        @else
                        <option value="{{$unidade->codigo}}" {{ $unidade->codigo == $produto->codUnidade ? "selected" : ""}}>
                          {{ $unidade->descricao }}
                        </option>
                        @endif
                      @endforeach
                    </select>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-4">
                    <label>Preço: (R$)</label>
                    <input id="valor" type="text" class="form-control valor" name="valor[{{$produto->codigo}}]"
                    value="{{!!$produto->valorultimasemana?str_replace('.',',',$produto->valorultimasemana->valor):''}}" placeholder="0,00">
                  </div>
                </div>
                <br>
                <div class="row">
                  <div class="col-md-4">
                    <label for="{{$produto->codigo}}" class="btn btn-primary">Selecionar</label>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </card>
        @empty
        <div class="alert alert-warning" role="alert">
          Você ainda não escolheu produtos.
          <a href="{{ route('produtos') }}">Clique aqui para escolher!</a>
        </div>
        @endforelse   
      </div>
    @endisset

    <div class="card shadow-lg float-button">
      <button type="submit" class="btn btn-success"><i class="fas fa-check-circle"></i> Pronto</button>
    </div>

  </form>
</div>

@if ($preencher === true)
  <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Aviso</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          Você precisa preencher seu perfil com suas informações. Para acessar a página de Perfil clique no botão abaixo</div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" onclick="location.href='{{route('users.informacoes')}}'">Preencher Perfil</button>
        </div>
      </div>
</div>
@endif
@endsection

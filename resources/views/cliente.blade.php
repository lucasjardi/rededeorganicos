@extends('layouts.app')

@section('content')
<div class="shadow-sm container p-3 rounded" style="background: rgba(255,255,255,0.2)">
  <div class="bg-white p-4 mb-3">
    <h1>Produtos Disponíveis</h1>
    <p class="font-italic">Escolha um local de retirada e clique em <b>Aplicar</b></p>
    @if(Session::has('localSelected'))
    <div class="alert alert-info">
      <div class="row">
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-8">
            Você já selecionou o local de entrega: <b>{{$destino}}</b>
        </div>
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-8 text-right">
            <span>
                <a href="{{ url('selectItensAgain') }}" style="color: #155724">
                  <u>Selecionar Outro</u></a>
              </span>
        </div>
      </div>
    </div>
    @else
    <form action="{{url('/retirar/')}}">
        <label>Local de Retirada: </label>
        <select id="selectlocalretirada" class="custom-select w-50" name="destino">
            @foreach ($locaisDeRetirada as $local)
              <option value="{{$local->codigo}}">{{$local->descricao}}</option>
            @endforeach
        </select>
        <button id="btnlocalretirada" class="btn btn-primary">Aplicar</button>
    </form>
    @endif
    @if(isset($descontos) && count($descontos) > 0)
    <div class="alert alert-info mt-3">
        @foreach ($descontos as $desconto)
          DESCONTO DE <b>{{$desconto->porcentagem}}%</b> PARA RETIRADAS EM <b>{{strtoupper($desconto->destino->descricao)}}</b><br>
        @endforeach
    </div>
    @endif
  </div>

  @if(Session::has('local_not_visible'))
    <div class="alert alert-danger mt-3">
      {{ Session::get('local_not_visible') }}
    </div>
  @endif

  @if(Session::has('localSelected'))
  <div class="bg-white p-3 mb-3">
  <div class="row">
      <div class="col" style="flex: 1;display: flex;align-items: center;margin-left: 1.5rem;padding: 0">
        <i class="fas fa-filter"></i>
        <span style="margin-left: 8px">FILTROS</span>
      </div>
      <div style="display: flex;justify-content: flex-end;margin-right: 1.5rem;max-width: -webkit-fill-available;">
        <div style="width: 200px;margin-right: 8px;margin-left: 1.5rem">
          <div class="form-group" style="border: 1px solid #ced4da;border-radius: .25rem;margin-bottom: 0">
            <select id="grupos" class="selectpicker form-control" multiple data-live-search="true" data-title="Categoria" name="grupos[]">
                @foreach ($grupos as $index => $grupo)
                  <option value="{{$grupo->codigo}}"
                    {{ null !== \Request::get('grupos') && in_array($grupo->codigo,explode(',', \Request::get('grupos'))) ? "selected" : "" }}>
                    {{$grupo->descricao}}</option>  
                @endforeach
            </select>
          </div>
        </div>
        <div style="margin-right: 8px">
          <input id="searchProducts"
                 type="text"
                 class="form-control"
                 placeholder="Nome"
                 onkeydown="searchForProducts(event)"
                 value="{{\Request::get('search')}}">
        </div>
        <div>
          <button onclick="searchForProducts({keyCode: 13})" class="btn btn-primary">Filtrar</button>
        </div>
        @if (!!\Request::get('grupos') || !!\Request::get('search'))
          <div style="display: flex;align-items: center">
            <a class="text-danger" style="margin-left: 8px; cursor: pointer" onclick="location.href='/'" title="LIMPAR FILTROS"><i class="fas fa-times"></i></a>
          </div>
        @endif
      </div>
    </div>
  </div>
  @endif

  @isset ($produtos)
    <div class="accordion" id="accordionExample">
        @forelse ($produtos as $produto)
        <card inline-template>
          <div class="card">
            <div class="card-header bg-white" id="headingOne">
              <h5 class="mb-0 d-inline">
                <button class="btn btn-link text-left" type="button" data-toggle="collapse" data-target="#produto{{$produto->prod_produzido_codigo}}" aria-expanded="true" aria-controls="produto{{$produto->prod_produzido_codigo}}">
                  <span style="white-space: pre-wrap;text-align: left;"><i class="fas fa-shopping-basket" style="margin-right: 8px;"></i>{{$produto->nome}}</span>
                  <span style="margin-left: 8px" class="badge badge-success badge-pill">R$ @dinheiro($produto->valor)/{{$produto->unidade}}</span>
                </button>
              </h5>
            </div>

            <div id="produto{{$produto->prod_produzido_codigo}}" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
              <div class="card-body">
                <p>
                  @if ($produto->descricao)<b>Descrição:</b> {{$produto->descricao}}<br /> @endif
                  <b>Valor:</b> R$ @dinheiro($produto->valorpuro)
                </p>
                <input type="text" ref="codProdutor" value="{{$produto->codProdutor}}" style="display:none">
                <input class="qtdProd" type="number" v-model="quantidade" style="width: 100px;" placeholder="Quantidade" min="0" {{$produto->unidade == "Kg" ? "step=0.1" : ""}} />
                <button id="addNaCesta" 
                        @click="adicionarNaCesta({{$produto->codigo}},{{$produto->valor}})" 
                        class="btn btn-primary btn-sm" style="margin-top: -5px"
                        :disabled="btnDisabled">
                        <i class="fas fa-shopping-basket"></i> ADICIONAR NA CESTA
                </button>
              </div>
            </div>
          </div>
        </card>
        @empty
          <h5>Nenhum produto encontrado.</h5>
        @endforelse   
      </div>
  @endisset
</div>
<div class="card shadow-lg float-button">
  <a href="/cesta" class="btn btn-success"><i class="fas fa-check-circle"></i> Finalizar Pedido</a>
</div>





{{-- Modal --}}
@if (isset($preencher) && $preencher === true)
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
</div>
@endif
@endsection

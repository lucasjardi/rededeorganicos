@extends('layouts.app')

@section('content')
<div class="shadow-sm container p-3 rounded" style="background: rgba(255,255,255,0.2)">
  <div class="bg-white p-4 mb-3">
    <h1>Produtos Disponíveis</h1>
    <p class="font-italic">Escolha um local de retirada e clique em <b>Aplicar</b></p>
    @if(Session::has('localSelected'))
    <div class="alert alert-success">
      <div class="row">
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-8">
            Você já selecionou o local de entrega: <b>{{Session::get('localSelected')}}</b>
        </div>
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-8 text-right">
            <span>
                <a href="" onclick="event.preventDefault();confirmSelectLocalAgain()" style="color: #155724">
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
  </div>

  @isset ($produtos)
    <div class="accordion" id="accordionExample">
        @forelse ($produtos as $produto)
        <card inline-template>
          <div class="card">
            <div class="card-header bg-white" id="headingOne">
              <h5 class="mb-0 d-inline">
                <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#produto{{$produto->codigo}}" aria-expanded="true" aria-controls="produto{{$produto->codigo}}">
                  <i class="fas fa-shopping-basket"></i> 
                  {{$produto->nome}} <span class="badge badge-success badge-pill">R$ {{$produto->valor}}/{{$produto->unidade}}</span>
                </button>
              </h5>
            </div>

            <div id="produto{{$produto->codigo}}" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
              <div class="card-body">
                @if ($produto->descricao) <p>{{$produto->descricao}}</p> @endif
                <p>Valor: R$ {{$produto->valorpuro}} <br>
                  {{-- Acréscimo para Local de Entrega: R$ {{$produto->acrescimo}} <br> --}}
                  <b>Total: R$ {{$produto->valor}}</b>
                </p>
                <input class="qtdProd" type="number" v-model="quantidade" style="width: 100px;" placeholder="Quantidade" min="1" {{$produto->unidade == "Kg" ? "step=0.1" : ""}} />
                <button id="addNaCesta" 
                        @click="adicionarNaCesta({{$produto->codigo}},{{$produto->valor}})" 
                        class="btn btn-primary btn-sm" style="margin-top: -5px"
                        :disabled="btnDisabled">
                  adicionar na cesta
                </button>
              </div>
            </div>
          </div>
        </card>
        @empty
          <h5>Não há produtos ainda...</h5>
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

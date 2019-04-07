@extends('layouts.app')

@section('content')
<div class="shadow-sm container p-3 rounded" style="background: rgba(255,255,255,0.2)">
    @isset ($produtos)
      <form action="{{ route('pesquisaProduto') }}" method="get">
        <div class="row no-gutters">
          <div class="col">
              <div class="form-group">
              <input type="text" name="nome" placeholder="Pesquisar" class="form-control" value="{{isset($pesquisa) ? $pesquisa : ''}}">
              </div>
          </div>
          <div class="col">
            <div class="form-group">
                <button class="btn btn-primary">
                  <i class="fa fa-search"></i>
                  @if(!$isMobile)<span>Pesquisar</span>@endif
                </button>
            </div>
          </div>
          <div class="col text-right">
            <div class="form-group">
                <a href="{{ url('/produtos') }}" class="btn btn-light">
                  <i class="fa fa-times"></i> Limpar
                </a>
            </div>
          </div>
        </div>
      </form>
    <ul class="list-group">
    @foreach ($produtos as $produto)
      <li class="list-group-item">
        {{$produto->nome}}
        @if (Auth::user()->codNivel == 4)
        <botaoadicionar inline-template>
          <div class="d-inline">
            <span v-if="!jaAdicionado" @click="addProdutoAoProdutor({{$produto->codigo}})" style="cursor: pointer;">
              <span class="badge badge-primary badge-pill">Adicionar ao Início</span>
            </span>
            <span v-if="jaAdicionado" class="badge badge-success badge-pill">Adicionado!</span>
          </div>
        </botaoadicionar>
        @endif
      </li>
    @endforeach
    </ul>
    @endif

    <br>
    {{ isset($init) ? $produtos->links() : '' }}
</div>

{{-- Modal --}}
@if (isset($aviso) && $aviso)
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Informação</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          Para escolher um produto, basta clicar em <span class="badge badge-primary badge-pill">Adicionar ao Início</span>, ao lado do nome do produto. <br>
          Após escolher todos os produtos que você quer, basta clicar em Início na barra superior.
          <img class="w-100" src="{{ asset('img/aux_inicio.png') }}" alt="">
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" data-dismiss="modal">Entendi</button>
        </div>
      </div>
  </div>
</div>
@endif
@endsection

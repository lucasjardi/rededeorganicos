@extends('layouts.app')

@section('content')
<div class="shadow-sm container p-3 rounded" style="background: rgba(255,255,255,0.2)">
    @isset ($produtos)
      <form action="{{ route('pesquisaProduto') }}" method="get">
        <div class="row no-gutters">
          <div class="col-6">
              <div class="form-group">
              <input type="text" name="nome" placeholder="Pesquisar" class="form-control" value="{{isset($pesquisa) ? $pesquisa : ''}}">
              </div>
          </div>
          <div class="col-2">
            <div class="form-group">
                <button class="btn btn-primary">Pesquisar</button>
            </div>
          </div>
          <div class="col-2">
            <div class="form-group">
                <a href="{{ url('/produtos') }}" class="btn btn-light"><i class="fa fa-times"></i> Limpar Pesquisa</a>
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
@endsection

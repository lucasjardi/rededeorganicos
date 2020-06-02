@extends('layouts.app')

@section('content')
<div class="shadow bg-white p-4 rounded">
    <h1>Relatório de Pedidos</h1>

    <form action="{{ url('gerarRelatorio') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col">
                <div class="form-group">
                    <label for="exampleFormControlInput1">Data de início <span class="text-danger font-weight-bold">*</span></label>
                    <input type="date" name="dataPedidoInicio" value="{{ old('dataPedidoInicio') }}" class="form-control" required>
                </div>
            </div>
            <div class="col">
                <div class="form-group">
                    <label for="exampleFormControlInput1">Data de fim <span class="text-danger font-weight-bold">*</span></label>
                    <input type="date" name="dataPedidoFim" class="form-control" value="{{ old('dataPedidoFim') }}" required>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">R$</div>
                        </div>
                        <input type="text" name="valorInicial" class="form-control valor" id="inlineFormInputGroupUsername" placeholder="Valor Inicial" value="{{ old('valorInicial') }}">
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">R$</div>
                        </div>
                        <input type="text" name="valorFinal" value="{{ old('valorFinal') }}" class="form-control valor" id="inlineFormInputGroupUsername" placeholder="Valor Final">
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="form-group">
                        <select id="clientes" class="selectpicker form-control" multiple data-live-search="true" data-title="Clientes" name="clientes[]">
                            @foreach ($clientes as $index => $cliente)
                                <option value="{{$cliente->codigo}}" {{ null !== old("clientes") && in_array($cliente->codigo,old("clientes")) ? "selected" : "" }}>{{$cliente->usuario->name}}</option>  
                            @endforeach
                        </select>
                </div>
            </div>
            <div class="col">
                <div class="form-group">
                        <select id="produtores" class="selectpicker form-control" multiple data-live-search="true" data-title="Produtores" name="produtores[]">
                            @foreach ($produtores as $index => $produtor)
                                <option value="{{$produtor->codigo}}" {{ null !== old("produtores") && in_array($produtor->codigo,old("produtores")) ? "selected" : "" }}>{{$produtor->usuario->name}}</option>  
                            @endforeach
                        </select>
                </div>
            </div>
            <div class="col">
                    <div class="form-group">
                        <select class="selectpicker form-control" multiple data-live-search="true" data-title="Locais de entrega" name="destinos[]">
                            @foreach ($destinos as $destino)
                                <option value="{{$destino->codigo}}" {{ null !== old("destinos") && in_array($destino->codigo,old("destinos")) ? "selected" : "" }}>{{$destino->descricao}}</option>  
                            @endforeach
                        </select>
                    </div>
            </div>
            <div class="col">
                <div class="form-group">
                    <select class="selectpicker form-control" multiple data-live-search="true" data-title="Status de Pedido" name="statuses[]">
                        @foreach ($statuses as $status)
                            <option value="{{$status->codigo}}" {{ null !== old("statuses") && in_array($status->codigo,old("statuses")) ? "selected" : "" }}>{{$status->descricao}}</option>  
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col">
                <button type="submit" class="btn btn-block btn-primary">Gerar Relatório</button>
            </div>
        </div>
    </form>


    @isset($pedidos)
    <div class="row justify-content-end">
        {{-- <div class="col-3">
                <form action="{{url('gerarCSV')}}" method="POST">
                    @csrf
                    <input type="hidden" name="pedidos" value="{{ json_encode($pedidos) }}">
                    <button class="btn btn-outline-primary btn-block" type="submit">Gerar CSV</button>
                </form>
        </div> --}}
        <div class="col-3">
            <form action="{{url('imprimir_pedidos')}}" method="POST" target="_blank">
                @csrf
                <input type="hidden" name="pedidos" value="{{ json_encode($pedidos) }}">
                <button class="btn btn-outline-primary btn-block" type="submit"><i class="fa fa-print"></i> Imprimir Pedidos</button>
            </form>
        </div>
    </div>
    <div class="row">
        <div class="col-3">
            <div class="card">
                <div class="card-body">
                  <b>{{$countPedidos}}</b>
                  @if($countPedidos > 1)<span>Pedidos</span>
                  @else<span>Pedidos</span>@endif no Período
                </div>
            </div>
        </div>
        <div class="col-3">
            <div class="card">
                <div class="card-body">
                  Valor Total no Período: <span class="text-success font-weight-bold">R$ @dinheiro($valorTotalPeriodo)</span>
                </div>
            </div>
        </div>
    </div>
    <div class="row p-4">
        <div class="col border-bottom border-primary" style="border-bottom-width: 4px !important">
            <h5>Código</h5>
        </div>
        <div class="col border-bottom border-primary" style="border-bottom-width: 4px !important">
            <h5>Cliente</h5>
        </div>
        <div class="col border-bottom border-primary" style="border-bottom-width: 4px !important">
            <h5>Destino</h5>
        </div>
        <div class="col border-bottom border-primary" style="border-bottom-width: 4px !important">
            <h5>Registro</h5>
        </div>
        <div class="col border-bottom border-primary" style="border-bottom-width: 4px !important">
            <h5>Valor</h5>
        </div>
        <div class="col border-bottom border-primary" style="border-bottom-width: 4px !important">
            <h5>Status</h5>
        </div>
    </div>

    <div class="content">
        @forelse ($pedidos as $pedido)
        <div class="row p-2 hoverable" style="cursor:pointer" onclick="document.querySelector('#linkEditarPedido').click()">
            <a id="linkEditarPedido" href="{{url('/manutencao/pedido/'.$pedido->codigo.'/editar')}}" target="_blank"></a>
            <div class="col pb-3">
                {{$pedido->codigo}}
            </div>
            <div class="col pb-3">
                {{$pedido->usuario->name}}
            </div>
            <div class="col pb-3">
                {{$pedido->destino->descricao}}
            </div>
            <div class="col pb-3">
                @datetime($pedido->dataPedido)
            </div>
            <div class="col pb-3">
                R$ @dinheiro($pedido->valor)
            </div>
            <div class="col pb-3">
                {{$pedido->st->descricao}}
            </div>
        </div>
        @empty
        <div class="row justify-content-center">
            <div class="col text-center">
                Nenhum pedido
            </div>
        </div>
        @endforelse
    </div>
    @endisset
</div>
@endsection

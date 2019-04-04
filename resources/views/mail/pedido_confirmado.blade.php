<div>
    Olá, {{$pedido->usuario->name}}! <br>
    Seu pedido feito em <b>{{date('d/m/Y H:i:s', strtotime($pedido->dataPedido))}}</b>,
    com destino para <b>{{$pedido->destino->descricao}}</b>,
    acaba de ser confirmado para retirada!! :) <br>
    <br>

    <b>Itens:</b> <br>
    @foreach ($pedido->itens as $item)
        - {{$item->descricao}} | R$ {{$item->valorTotal}} <br>
    @endforeach

    -------------------------------- 
    <br>Total: R$ {{$pedido->valor}}
</div>
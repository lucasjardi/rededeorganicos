@extends('layouts.app')

@section('content')
@isset($pedido)
<div class="shadow bg-white p-4 rounded">
  <h1>Solicitação de Pedido:</h1>
  <i class="fa fa-print fa-2x text-info" style="cursor:pointer" onclick="print()"></i>
  <hr>
  <h4>Código do pedido: {{$pedido->codigo}}</h4>
  <p>
      @php $subTotal = 0; @endphp
      <b>Itens do Pedido:</b> <br>
      @foreach ($pedido->itens as $item)
        - {{$item->descricao}} | R$ @dinheiro($item->valorTotal) <br>
        @php $subTotal += $item->valorTotal; @endphp
      @endforeach
    <br>
    <b>SubTotal:</b> R$ @dinheiro($subTotal) <br>
    @if(!!$pedido->destino->desconto)<b>Desconto:</b> {{$pedido->destino->desconto->porcentagem}}%<br/>@endif
    <b>Valor Total:</b> R$ @dinheiro($pedido->valor) <br>
    <b>Local de entrega:</b> {{$pedido->destino->descricao}} <br>
    <b>Data de processamento:</b> @datetime($pedido->dataPedido) <br>
    <b>Observações:</b> {{$pedido->descricao}} <br>
  </p>
  <hr>
  <div class="alert alert-primary">
    <h4 class="alert-heading">Algumas Informações: </h4>
    Você será notificado quando seu pedido estiver pronto para ser buscado. Fique de olho na sua caixa de e-mail!
  </div>
</div>
@endisset
@endsection

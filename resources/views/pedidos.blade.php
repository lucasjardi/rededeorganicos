@extends('layouts.app')

@section('content')
<div class="container">
	<div class="bg-light p-3">
		@if($model==='cliente') <h1>Meus Pedidos</h1> @endif
		@if($model==='produtor') <h1>Pedidos com produtos de {{Auth::user()->name}}</h1> @endif
		    <div class="accordion" id="accordionExample">
              @forelse ($pedidos as $pedido)
			  	<div class="card">
                    <div 
                        class="card-header {{$pedido->status == 1 ? 'bg-analise' : 
                                                ($pedido->status == 2 ? 'bg-aguardando' : 
                                                ($pedido->status == 3 ? 'bg-confirmado' : 
                                                'bg-cancelado')) }}" 
                        id="headingOne">
				      <h5 class="mb-0 d-inline">
				        <button class="btn btn-link text-dark" type="button" data-toggle="collapse" data-target="#pedido{{$pedido->codigo}}" aria-expanded="true" aria-controls="pedido{{$pedido->codigo}}">
				          <i class="fas fa-shopping-basket"></i> 
				          @datetime($pedido->dataPedido) - Total de R$ @dinheiro($pedido->valor)
				        </button>
				      </h5>
				      <span class="float-right text-secondary bg-white p-1 font-weight-bold">
                           {{$pedido->st->descricao}}
				        </span>
				    </div>

				    <div id="pedido{{$pedido->codigo}}" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
				      <div class="card-body">
                      <h3>{{$pedido->st->descricao}}</h3>
                      <hr>
				      	<p>
							@php $subTotal = 0; @endphp
				      		<b>Itens do Pedido:</b> <br>
					      	@foreach ($pedido->itens as $item)
								  - {{$item->descricao}} | R$ @dinheiro($item->valorTotal) <br>
								  @php $subTotal += $item->valorTotal; @endphp
					      	@endforeach
				      	</p>
						  -------------------------------- 
						<p>
							<b>Subtotal:</b> R$ @dinheiro($subTotal) <br/>
							@if(!!$pedido->destino->desconto)<b>Desconto:</b> {{$pedido->destino->desconto->porcentagem}}%<br/>@endif
							<b>Total:</b> R$ @dinheiro($pedido->valor)
						</p>
				       	--------------------------------
				       	<p><b>Destino:</b> {{$pedido->destino->descricao}}</p>
				      </div>
				    </div>
			 	</div>
			  @empty
			  	<p class="font-italic">Não há pedidos...</p>
			  @endforelse		
			</div>
        </div>
</div>
@endsection
@extends('layouts.app')

@section('content')
<div class="container">
	<div class="bg-light p-3">
		<h1>Pedidos para análise</h1>
		    <div class="accordion" id="accordionExample">
			  @forelse ($pedidos as $pedido)
			  	<div class="card">
				    <div class="card-header bg-white" id="headingOne">
				      <h5 class="mb-0 d-inline">
				        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#pedido{{$pedido->codigo}}" aria-expanded="true" aria-controls="pedido{{$pedido->codigo}}">
				          <i class="fas fa-shopping-basket"></i> 
				          <b>#{{$pedido->codigo}}</b> - Pedido de {{ $pedido->usuario->name }}
				        </button>
				      </h5>
				      <span class="float-right text-secondary">
				          	{{ $pedido->dataPedido->diffForHumans() }}
				      	</span>
				    </div>

				    <div id="pedido{{$pedido->codigo}}" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
				      <div class="card-body">
				      	<p>
							@php $subTotal = 0; @endphp
				      		<b>Itens do Pedido:</b> <br>
					      	@foreach ($pedido->itens as $item)
								<span style="display: flex">
									@if (!$item->available)<s>@endif
									- {{$item->descricao}} | R$ @dinheiro($item->valorTotal) <br>
									@if (!$item->available)</s>&nbsp;(Não disponível na lista no momento do pedido)@endif
								</span>
								@php if($item->available) { $subTotal += $item->valorTotal; } @endphp
							@endforeach
				      	</p>
						  -------------------------------- 
						<p>
							<b>Subtotal:</b> R$ @dinheiro($subTotal) <br/>
							@if(!!$pedido->destino->desconto)<b>Desconto:</b> {{$pedido->destino->desconto->porcentagem}}%<br/>@endif
							<b>Total:</b> R$ @dinheiro($pedido->valor)
						</p>
						  --------------------------------
						<p>
							<b>Observações:</b> {{$pedido->descricao}} <br>
						</p>
						--------------------------------
				       	<p>
				       		<b>Solicitado por:</b> {{$pedido->usuario->name}} <br>
				       		<b>CPF:</b> {{$pedido->cliente->cpf}} <br>
					       	<b>Telefone:</b> {{$pedido->cliente->telefone}} <br>
					       	<b>Endereço:</b> {{$pedido->cliente->endereco}}
				       	</p>
				       	--------------------------------
								 <p><b>Destino:</b> {{$pedido->destino->descricao}}</p>

				       	<div class="row no-gutters">
									 <div class="col">
											{!! Form::open(['method' => 'PATCH', 'url' => 'pedidos/'.$pedido->codigo, 'style' => 'display: inline']) !!}
												<input type="hidden" name="fromHomePage" value="true">
												<input type="hidden" name="status" value="3">
												<button class="btn btn-success btn-block" type="submit">Confirmar</button>
											{!! Form::close() !!}
									 </div>

									 <div class="col">
											{!! Form::open(['method' => 'PATCH', 'url' => 'pedidos/'.$pedido->codigo, 'style' => 'display: inline']) !!}
												<input type="hidden" name="fromHomePage" value="true">
												<input type="hidden" name="status" value="4">
												<button class="btn btn-danger btn-block" type="submit">Cancelar</button>
											{!! Form::close() !!}
									 </div>

									 <div class="col">
										<a href="{{ url('/manutencao/pedido/'.$pedido->codigo.'/editar') }}" class="btn btn-primary btn-block">Editar Pedido</a>
									 </div>
								 </div>
				      </div>
				    </div>
			 	</div>
			  @empty
			  	<p class="font-italic">Não há pedidos...</p>
			  @endforelse		
			</div>
	</div>	
	{{$pedidos->links()}}
</div>
@endsection
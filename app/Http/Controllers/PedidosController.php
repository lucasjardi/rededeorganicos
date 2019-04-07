<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

use App\Mail\PedidoConfirmado;
use Carbon\Carbon;
use App\Pedido;
use App\Cesta;
use App\ItemPedido;
use App\Unidade;
use App\Produto;
use App\Destino;
use Auth;

class PedidosController extends Controller
{
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function save(Request $request)
    {
        $request->merge(['valor' => str_replace(",",".",$request->valor)]);
        $this->validate($request, [
            'codCliente' => 'required',
            'valor' => 'required'
        ]);

        $pedido = Pedido::create( $request->all() + ["dataPedido"=> Carbon::now()]);

        if ($request->has("codProduto")) {
            $i = 0;
            foreach ($request->codProduto as $codigoProduto) {
                if (!($request->qtdProduto[$i]=="" && $request->valorTotal[$i]=="")) {
                    $produto = Produto::with('unidade')->find($codigoProduto);
                    $descricao = $request->qtdProduto[$i]." ". $produto->unidade->descricao ." de ". $produto->nome;
                    ItemPedido::create([
                        'codPedido' => $pedido->codigo,
                        'codProduto' => $codigoProduto,
                        'quantidade' => $request->qtdProduto[$i],
                        'valorTotal' => $request->valorTotal[$i],
                        'descricao' => $descricao
                    ]);
                }
            }
        }

        \Session::flash('mensagem_sucesso', 'Pedido criado com sucesso!');

        return redirect('manutencao/pedido/'.$pedido->codigo.'/editar');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'local_de_retirada' => 'required'
        ]);


        $valor = Cesta::where('user_id',$request->user()->id)->sum('subtotal');

        $destinoId = Destino::where('descricao', $request->local_de_retirada)->first();
        
        $pedido = new Pedido([
            'codCliente' => $request->user()->id,
            'codDestino' => $destinoId->codigo,
            'dataPedido' => Carbon::now(),
            'valor' => $valor
        ]);

        if($pedido->save()){ // se salvou o pedido, salva os itens do pedido
            $cestaUser = Cesta::where('user_id',$request->user()->id)->get();
            foreach ($cestaUser as $cesta) {
                $descricao = $cesta->quantidade." ". $cesta->unidade ." de ". $cesta->produto->nome;
                ItemPedido::create([
                    'codPedido' => $pedido->codigo,
                    'codProduto' => $cesta->produto->codigo,
                    'quantidade' => $cesta->quantidade,
                    'valorTotal' => $cesta->subtotal,
                    'descricao' => $descricao
                ]);
            }

            foreach ($cestaUser as $cesta) {
                $cesta->delete();
            }

            return \Redirect::to('/solicitado')
            ->with('message','Seu Pedido foi Solicitado! Você será contatado(a) 
                                        quando estiver pronto para buscá-lo!');
            // return ["message" => "inserted"];
        }

        return \Redirect::to('/solicitado')->with('message','Não Foi Possível Fazer o Pedido. Tente Novamente...');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Pedido  $pedido
     * @return \Illuminate\Http\Response
     */
    public function show(Pedido $pedido)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Pedido  $pedido
     * @return \Illuminate\Http\Response
     */
    public function edit(Pedido $pedido)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Pedido  $pedido
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pedido $pedido)
    {
        $this->validate($request, [
            'valor' => 'required',
            'codCliente' => 'required'
        ]);
        $enviarEmailConfirmado = $pedido->status != 3 && $request->status == 3;
        $request->merge(['valor' => str_replace(",",".",$request->valor)]);
        $pedido->update($request->all());

        if( $enviarEmailConfirmado ) {
            $pedido->load('usuario', 'destino', 'itens');
            Mail::to($pedido->usuario->email)->send(new PedidoConfirmado($pedido)); 
        }

        if ($request->has("codProduto")) {
            $i = 0;
            foreach ($request->codProduto as $codigoProduto) {
                if (!($request->qtdProduto[$i]=="" && $request->valorTotal[$i]=="")) {
                    $produto = Produto::with('unidade')->find($codigoProduto);
                    $descricao = $request->qtdProduto[$i]." ". $produto->unidade->descricao ." de ". $produto->nome;
                    ItemPedido::create([
                        'codPedido' => $pedido->codigo,
                        'codProduto' => $codigoProduto,
                        'quantidade' => $request->qtdProduto[$i],
                        'valorTotal' => $request->valorTotal[$i],
                        'descricao' => $descricao
                    ]);
                }
            }
        }

        \Session::flash('mensagem_sucesso', 'Pedido atualizado com sucesso!');

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Pedido  $pedido
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pedido $pedido)
    {
        $pedido->delete();

        \Session::flash('mensagem_sucesso', 'Pedido deletado com sucesso!');

        return back();
    }



    public function solicitado()
    {
       return view('solicitado');
    }
}

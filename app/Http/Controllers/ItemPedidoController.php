<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ItemPedido;
use App\Pedido;
use App\Produto;
use App\Unidade;

class ItemPedidoController extends Controller
{

    public function save(Pedido $pedido, Request $request)
    {
        $request->merge(['valorTotal' => str_replace(",",".",$request->valorTotal)]);
        $this->validate($request, [
            "codProduto" => "required|numeric",
            "quantidade" => "required|numeric",
            "valorTotal" => "required|numeric",
            "unidade" => "different:-1"
        ]);

        $produto = Produto::find($request->codProduto);

        $descricao = $request->quantidade . " ". $request->unidade . " de " .$produto->nome;

        ItemPedido::create($request->all() + ["codPedido" => $pedido->codigo, "descricao" => $descricao]);

        $pedido->update([
            // "valor" => $pedido->valor + ($request->valorTotal * $request->quantidade)
            "valor" => $pedido->valor + $request->valorTotal // ja tem que inserir o preco somado
        ]);

        return back();
    }

    public function remove($item)
    {
        $item = ItemPedido::findOrFail($item);
        
        $pedido = Pedido::find($item->codPedido);

        $pedido->update([
            "valor" => $pedido->valor - $item->valorTotal
        ]);

    	$item->delete();

        return back();
    }
}

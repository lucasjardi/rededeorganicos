<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ProdutorProduz;
use App\ProdutoProduzido;
use App\ValorUltimaSemana;

class ProdutorProduzController extends Controller
{
    
    public function store(Request $request)
    {
    	if (ProdutorProduz::where('codProdutor', $request->user()->id)
    					->where('codProduto', $request->produto_id)->count() == 0 ) {
    		ProdutorProduz::create([
	    		'codProduto' => $request->produto_id,
	    		'codProdutor' => $request->user()->id
	    	]);

    		return ["message" => "ok"];
    	}
    }

    public function destroy($codProduto, Request $request)
    {
        $produtorProduzObject = ProdutorProduz::where('codProdutor', $request->user()->id)->where('codProduto', $codProduto);
        if ( $produtorProduzObject->exists() ) {
            $produtorProduzObject->delete();
            ValorUltimaSemana::where('codProduto', $codProduto)->where('codProdutor', $request->user()->id)->delete();
            ProdutoProduzido::where('codProduto', $codProduto)->where('codProdutor', $request->user()->id)->delete();
    		return redirect('/');
    	}
    }

    public function get(Request $request)
    {
        $produtos = ProdutorProduz::where('codProdutor', $request->user()->id)->pluck('codProduto');
        // $prods = array();
        // foreach ($produtos as $prod) {
        //     array_push($prods, $prod->codProduto);
        // }

        return ["message" => $produtos];
    }
}

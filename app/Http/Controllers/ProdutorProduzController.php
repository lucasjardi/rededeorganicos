<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ProdutorProduz;

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

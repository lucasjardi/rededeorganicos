<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Cesta;
use App\Produto;
use App\ProdutoProduzido;
use App\Unidade;
use App\Destino;

class CestaController extends Controller
{

    public function index()
    {
        $cestaUser = Cesta::where('user_id',Auth::user()->id)
                    ->with('produto')
                    ->get();

        $subtotal = $cestaUser->reduce(function ($carry, $item) {
            return $carry + $item->subtotal;
        });

        $total = $subtotal;

        $destino = \Session::get('localSelected');
        $desconto = DB::table('descontos')
                        ->where('destino_id', $destino)
                        ->pluck('porcentagem')
                        ->first();

        $descontado = 0;
        if(!empty($desconto) || !$desconto==null){
            $descontado = $total *  ($desconto/100);
            $total = $total - $descontado;
        }

        return view('cesta')->with(["cesta" => $cestaUser, 
                                    "destino" => Destino::find($destino),
                                    "subtotal" => $subtotal,
                                    "total" => $total,
                                    "desconto" => $descontado]);
    }

    public function adicionaNaCesta(Request $request)
    {
    	$produto = $request->json('produto');
    	$prod = Produto::findOrFail($produto["produto_codigo"]);
        $unidade = ProdutoProduzido::where('codProduto',$produto["produto_codigo"])->pluck('codUnidade')->first();
        // $valor = ProdutoProduzido::where('codProduto',$produto["produto_codigo"])->pluck('valor')->first();
        $valor = $produto["valor"];
        $formaASerVendida = Unidade::find($unidade);
		Cesta::create([
			'user_id' => $request->user()->id,
			'produto_id' => $produto["produto_codigo"],
			'quantidade' => $produto["quantidade"],
            'unidade' =>  $formaASerVendida->descricao,
			'subtotal' => ($produto["quantidade"] * $valor)
		]);

    	return ["message" => "Ok"];
    }

    public function getCesta(Request $request)
    {
    	return Cesta::where('user_id',$request->user()->id)->with('produto')->get();
    }


    public function getCestaLength(Request $request)
    {
    	return Cesta::where('user_id',$request->user()->id)->count();
    }

    public function delete(Cesta $cesta)
    {
        $cesta->delete();

        return back();
    }

    public function limparCesta(Request $request)
    {
        $cestaUser = Cesta::where('user_id',$request->user()->id)->get();
        foreach ($cestaUser as $cesta) {
            $cesta->delete();
        }

        return back();
    }
}

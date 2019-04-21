<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Destino;
use Auth;
use Illuminate\Support\Facades\DB;

class DestinosController extends Controller
{
    
    public function store(Request $request)
    {
        $this->validate($request, [
            'descricao' => 'required'
        ]);
    	Destino::create( [
            "descricao" => $request->descricao,
            "acrescimo" => $request->acrescimo != '' ? $request->acrescimo : 0
        ] );

    	\Session::flash('mensagem_sucesso', 'Local de Entrega criado com sucesso!');

        return back();
    }


    public function update(Request $request, Destino $destino)
    {
        $this->validate($request, [
            'descricao' => 'required'
        ]);
    	$destino->update( [
            "descricao" => $request->descricao,
            "acrescimo" => $request->acrescimo != '' ? $request->acrescimo : 0
        ] );

    	\Session::flash('mensagem_sucesso', 'Local de Entrega atualizado com sucesso!');

        return back();
    }

    public function destroy(Destino $destino)
    {
    	$destino->delete();

    	\Session::flash('mensagem_sucesso', 'Local de Entrega removido com sucesso!');

        return back();

    }


    // acrescimo de local

    public function setLocalRetirada(Request $request)
    {
        if(Auth::user()->codNivel == 5) {
            $produtos = DB::table('produto')
                    ->join('prod_produzido', 'produto.codigo', '=', 'prod_produzido.codProduto')
                    ->join('unidade', 'prod_produzido.codUnidade', '=', 'unidade.codigo')
                    ->join('destino', 'destino.codigo', '=', DB::raw($request->destino))
                    ->select('produto.codigo','produto.nome','produto.descricao','unidade.descricao AS unidade','prod_produzido.valor as valorpuro','destino.acrescimo', DB::raw("prod_produzido.valor AS valor"))
                    ->where('produto.ativo',1)
                    ->get();

            $destino = Destino::find($request->destino);
            $destinoNome = $destino->descricao;
            $request->session()->put('localSelected',$request->destino);

            return view('cliente')->with(['produtos' => $produtos, 'destino' => $destinoNome]);
        }
    }
}

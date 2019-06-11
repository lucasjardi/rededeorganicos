<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\ProdutoProduzido;
use Illuminate\Http\Request;
use App\ValorUltimaSemana;
use App\Cesta;

class ProdutoProduzidoController extends Controller
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
    public function store(Request $request)
    {
        $errors = array();

        if ($request->has('produtos') && $request->has('unidades') && $request->has('valor')) {

            $produtosProduzidos = array_intersect_key($request->unidades,$request->produtos);

            if (! in_array("1",$produtosProduzidos) ) {

                $user_id = $request->user()->id;

                // deleta tudo que produtor tinha produzido
                ProdutoProduzido::where('codProdutor',$user_id)->delete();

                $valores = $request->valor;

                foreach ($produtosProduzidos as $produto_id => $unidade) {
                    // insere ou atualiza um produto produzido pelo produtor
                    
                    if( ProdutoProduzido::where('codProdutor', $user_id)
                                        ->where('codProduto', $produto_id)
                                        ->count() == 0
                      ) {
                        ProdutoProduzido::create([
                            'codProdutor' => $user_id,
                            'codProduto' => $produto_id,
                            'codUnidade' => $unidade,
                            'valor' => str_replace(",",".",$valores[$produto_id])
                        ]);
                    } else {
                        ProdutoProduzido::where('codProdutor', $user_id)
                                        ->where('codProduto', $produto_id)
                                        ->update(['codUnidade' => $unidade,
                                                  'valor' => str_replace(",",".",$valores[$produto_id])]);
                    }

                    // inserir ou atualizar valor da ultima semana

                    if( ValorUltimaSemana::where('codProdutor', $user_id)
                                        ->where('codProduto', $produto_id)
                                        ->count() == 0
                      ) {
                        ValorUltimaSemana::create([
                            'codProdutor' => $user_id,
                            'codProduto' => $produto_id,
                            'valor' => str_replace(",",".",$valores[$produto_id])
                        ]);
                    } else {
                        ValorUltimaSemana::where('codProdutor', $user_id)
                                        ->where('codProduto', $produto_id)
                                        ->update(['valor' => str_replace(",",".",$valores[$produto_id])]);
                    }
                }
                
                $request->session()->put('hasSelected', 'Você Já Selecionou os itens.');
            } else {
                array_push($errors, "As opções que estão escritas: 'Produtor Escolhe' devem ser preenchidas.");
            }

        } else {
            ProdutoProduzido::where('codProdutor',$request->user()->id)->delete();
        }

        if(count($errors) > 0) return \Redirect::to('home')->withErrors($errors);
        else return \Redirect::to('home');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ProdutoProduzido  $produtoProduzido
     * @return \Illuminate\Http\Response
     */
    public function show(ProdutoProduzido $produtoProduzido)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ProdutoProduzido  $produtoProduzido
     * @return \Illuminate\Http\Response
     */
    public function edit(ProdutoProduzido $produtoProduzido)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ProdutoProduzido  $produtoProduzido
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProdutoProduzido $produtoProduzido)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ProdutoProduzido  $produtoProduzido
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $prod = ProdutoProduzido::where('codProduto',$id)->first();
        return $prod;
        // $prod->delete();
        // return ["ok"];
    }


    public function killSessionAndRedirect(Request $request)
    {
        if(\Session::has('hasSelected')){
            \Session::forget('hasSelected');
        }
        if(\Session::has('localSelected')){
            // $cestaUser = Cesta::where('user_id',$request->user()->id)->get();
            // foreach ($cestaUser as $cesta) {
            //     $cesta->delete();
            // }
            \Session::forget('localSelected');
        }
        return \Redirect::to('home');
    }

    public function str_replace( $search , $replace , $str ) {
        if( ( $pos = strrpos( $str , $search ) ) !== false ) {
            $search_length  = strlen( $search );
            $str    = substr_replace( $str , $replace , $pos , $search_length );
        }
        return $str;
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Produto;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Auth;

class ProdutosController extends Controller
{

    public function get()
    {
    	// $produtos = Produto::with('unidade')->paginate(10);
        $produtos = null;
        $aviso = false;
        
    	if (Auth::user()->codNivel == 4) {
            $produtos = DB::table("produto")->select('*')
            ->whereNOTIn('codigo',function($query){
               $query->select('codProduto')->from('produtor_produz');
            })
            ->where('ativo',1)
            ->paginate(40);

            $countProdutorProduz = DB::table('produtor_produz')
            ->where('codProdutor',Auth::user()->id)
            ->count();
            $aviso = $countProdutorProduz == 0 ? true : false;
        } else {
            $produtos = Produto::where('ativo',1)->paginate(40);
        }
    	return view('produtos')->with(['produtos' => $produtos, 'init' => true, 'aviso' => $aviso]);
    }


    public function store(Request $request)
    {
        $this->validate($request, [
            'nome' => 'required',
            'imagem' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        
        $prod = Produto::create($request->all() + ['observacao1' => 1, 'observacao2' => 1, 'ativo' => 1]);
        if($request->hasFile('imagem')){
            $path = $request->file('imagem')->store('produtos_imagens','public');
            $prod->imagem = $path;
            $prod->save();
        }

        \Session::flash('mensagem_sucesso', 'Produto criado com sucesso!');

        return back();
    }


    public function update(Request $request,Produto $produto)
    {
        $this->validate($request, [
            'nome' => 'required',
            'imagem' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $produto->update($request->all());
        $file = $produto->imagem;

        if($request->hasFile('imagem')){
            Storage::disk('public')->delete($file);

            $path = $request->file('imagem')->store('produtos_imagens','public');

            $produto->imagem = $path;
            $produto->save();
        }

        \Session::flash('mensagem_sucesso', 'Produto atualizado com sucesso!');

        return back();
    }

    public function desativarProduto(Produto $produto)
    {
        $produto->ativo = 0;
        $produto->save();

        \Session::flash('mensagem_sucesso', 'Produto desativado!');

        return back();
    }

    public function ativarProduto(Produto $produto)
    {
        $produto->ativo = 1;
        $produto->save();

        \Session::flash('mensagem_sucesso', 'Produto ativado!');

        return back();
    }



    public function pesquisaPorNome(Request $request)
    {
        $produtos = Produto::
                    where( 'nome', 'LIKE', '%' . $request->nome . '%' )
                    ->get();
        return view('produtos')->with(['produtos' => $produtos, 'pesquisa' => $request->nome]);
    }
}

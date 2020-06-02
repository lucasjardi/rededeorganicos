<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Destino;
use App\Grupo;
use Auth;
use Illuminate\Support\Facades\DB;
use Jenssegers\Agent\Agent;

class DestinosController extends Controller
{
    private $agent;
    public function __construct()
    {
        $this->middleware(['auth','isadmin'],['except' => 'setLocalRetirada']);
        $this->agent = new Agent();
    }

    public function index()
    {
        $destinos = Destino::all();
        return view('manutencao.destinos.index',['destinos' => $destinos, 'isMobile' => $this->agent->isMobile()]);
    }

    public function create()
    {
        return view('manutencao.destinos.form',['isMobile' => $this->agent->isMobile()]);
    }

    public function edit(Destino $destino)
    {
        return view('manutencao.destinos.form',['destino' => $destino, 'isMobile' => $this->agent->isMobile()]);
    }
    
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
        $request->merge(['acrescimo' => str_replace(",",".",$request->acrescimo)]);
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

    public function setLocalRetirada(Request $request)
    {
        if(Auth::user()->codNivel == 5) {
            $produtos = DB::table('produto')
                    ->join('prod_produzido', 'produto.codigo', '=', 'prod_produzido.codProduto')
                    ->join('unidade', 'prod_produzido.codUnidade', '=', 'unidade.codigo')
                    ->join('destino', 'destino.codigo', '=', DB::raw($request->destino))
                    ->select('produto.codigo','produto.nome','produto.descricao','unidade.descricao AS unidade','prod_produzido.valor as valorpuro','destino.acrescimo', DB::raw("prod_produzido.valor AS valor"),'prod_produzido.codigo as prod_produzido_codigo','prod_produzido.codProdutor')
                    ->where('produto.ativo',1)
                    ->when(request('search'), function($query) {
                        $query->where('produto.nome','like','%'.request('search').'%')
                            ->orWhere('prod_produzido.valor','like','%'.request('search').'%');
                    })
                    ->when(request('grupos'), function ($query) {
                        $query->whereIn('produto.codGrupo', explode(',',request('grupos')));
                    })
                    ->orderBy('produto.nome')
                    ->get();

            $destino = Destino::find($request->destino);
            $destinoNome = $destino->descricao;
            $request->session()->put('localSelected',$request->destino);

            return view('cliente')->with(['produtos' => $produtos, 'destino' => $destinoNome, 'grupos' => Grupo::all()]);
        }
    }

    public function visibility(Destino $destino)
    {
        $destino->visibility = !$destino->visibility;
        $destino->save();
        return back();
    }
}

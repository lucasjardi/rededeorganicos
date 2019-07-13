<?php

namespace App\Http\Controllers;

use Jenssegers\Agent\Agent;
use Illuminate\Http\Request;
use App\Destino;
use App\Produto;
use App\Pedido;
use App\Unidade;
use App\Grupo;
use App\Solicitacao;
use App\StatusPedido;
use App\User;
use App\Desconto;
use Illuminate\Support\Facades\DB;

class ManutencaoController extends Controller
{
    private $agent;

    public function __construct() {
        $this->agent = new Agent();
    }

    public function editPedido(Pedido $pedido)
    {
        $ped = Pedido::with('itens','usuario')->find($pedido->codigo);
        $destinos = Destino::all();
        $destinosNome = array();
        foreach ($destinos as $destino){
            $destinosNome[ $destino->codigo ] = $destino->descricao;
        }

        $statuses = StatusPedido::all();
        $statusesNome = array();
        foreach ($statuses as $status){
            $statusesNome[ $status->codigo ] = $status->descricao;
        }

        $produtos = Produto::all();

        $unidades = Unidade::all();

        return view('manutencao.pedido')->with(['pedido' => $ped, 'destinos' => $destinosNome, 'statuses' => $statusesNome, 'produtos' => $produtos, 'unidades' => $unidades, 'isMobile' => $this->agent->isMobile()]);
    }

    public function solicitacoes()
    {
        $solicitacoes = Solicitacao::all();
        return view('manutencao.solicitacoes')->with(['solicitacoes' => $solicitacoes, 'isMobile' => $this->agent->isMobile()]);
    }

    public function pedidos()
    {
        $pedidos = Pedido::with('usuario','destino','st')->orderBy('dataPedido',false)->get();
    	return view('manutencao.pedidos')->with(['pedidos' => $pedidos, 'isMobile' => $this->agent->isMobile()]);
    }

    public function usuarios()
    {
    	return view('manutencao.usuarios')->with(['isMobile' => $this->agent->isMobile()]);;
    }


    public function saveGroup(Request $request)
    {
        return Grupo::create($request->all());
    }

    public function saveUnidade(Request $request)
    {
        return Unidade::create($request->all());
    }

    public function horariosAcessoCliente()
    {
        $horariosAcesso = DB::table('horariosacesso')
                                    ->where('nivel_id',5)
                                    ->first();
        return view('manutencao.horariosAcessoCliente', ['horariosAcessoCliente' => $horariosAcesso,'isMobile' => $this->agent->isMobile()]);
    }
    public function horariosAcessoProdutor()
    {
        $horariosAcesso = DB::table('horariosacesso')
                                    ->where('nivel_id',4)
                                    ->first();
        return view('manutencao.horariosAcessoProdutor', ['horariosAcessoProdutor' => $horariosAcesso,'isMobile' => $this->agent->isMobile()]);
    }
}

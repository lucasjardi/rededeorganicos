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

class ManutencaoController extends Controller
{
    private $agent;

    public function __construct() {
        $this->agent = new Agent();
    }
    
    public function produtos()
    {
        $produtos = Produto::with('unidade','grupo')->where('ativo',1)->get();
    	return view('manutencao.produtos')->with(['produtos' => $produtos, 'isMobile' => $this->agent->isMobile()]);
    }

    public function produtosdesativados()
    {
        $produtosDesativados = Produto::with('unidade','grupo')->where('ativo',0)->get();
        return view('manutencao.produtos')->with(['produtos' => $produtosDesativados, 'isMobile' => $this->agent->isMobile()]);
    }

    public function novoProduto()
    {
        $unidades = Unidade::all();
        $unidadesNome = array();
        foreach ($unidades as $unidade){
            $unidadesNome[ $unidade->codigo ] = $unidade->descricao;
        }

        $grupos = Grupo::all();
        $gruposNome = array();
        foreach ($grupos as $grupo){
            $gruposNome[ $grupo->codigo ] = $grupo->descricao;
        }

         return view('manutencao.produto')->with(['unidades' => $unidadesNome, 'grupos' => $gruposNome, 'isMobile' => $this->agent->isMobile()]);
    }

    public function editProduto(Produto $produto)
    {
        $unidades = Unidade::all();
        $unidadesNome = array();
        foreach ($unidades as $unidade){
            $unidadesNome[ $unidade->codigo ] = $unidade->descricao;
        }

        $grupos = Grupo::all();
        $gruposNome = array();
        foreach ($grupos as $grupo){
            $gruposNome[ $grupo->codigo ] = $grupo->descricao;
        }

        return view('manutencao.produto')->with(['produto' => $produto, 'unidades' => $unidadesNome, 'grupos' => $gruposNome, 'isMobile' => $this->agent->isMobile()]);
    }

    public function editPedido(Pedido $pedido)
    {
        $ped = Pedido::with('itens')->find($pedido->codigo);
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

        $clientes = User::where('codNivel',5)->get();
        $clientesNome = array();
        foreach ($clientes as $cliente){
            $clientesNome[ $cliente->id ] = $cliente->name;
        }

        $produtos = Produto::all();

        $unidades = Unidade::all();

        return view('manutencao.pedido')->with(['pedido' => $ped, 'destinos' => $destinosNome, 'statuses' => $statusesNome, 'clientes' => $clientesNome, 'produtos' => $produtos, 'unidades' => $unidades, 'isMobile' => $this->agent->isMobile()]);
    }

    public function novoLocal()
    {
        return view('manutencao.local')->with(['isMobile' => $this->agent->isMobile()]);
    }

    public function editLocal(Destino $destino)
    {
        return view('manutencao.local')->with(['destino' => $destino, 'isMobile' => $this->agent->isMobile()]);
    }

    public function solicitacoes()
    {
        $solicitacoes = Solicitacao::all();
        return view('manutencao.solicitacoes')->with(['solicitacoes' => $solicitacoes, 'isMobile' => $this->agent->isMobile()]);
    }

    public function pedidos()
    {
        $pedidos = Pedido::with('usuario','destino')->get();
    	return view('manutencao.pedidos')->with(['pedidos' => $pedidos, 'isMobile' => $this->agent->isMobile()]);
    }

    public function usuarios()
    {
    	return view('manutencao.usuarios')->with(['isMobile' => $this->agent->isMobile()]);;
    }

    public function locais()
    {
        $destinos = Destino::all();
       return view('manutencao.locais')->with(['destinos' => $destinos, 'isMobile' => $this->agent->isMobile()]);
    }


    public function saveGroup(Request $request)
    {
        return Grupo::create($request->all());
    }
}

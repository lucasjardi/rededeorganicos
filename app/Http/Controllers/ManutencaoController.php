<?php

namespace App\Http\Controllers;

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
    
    public function produtos()
    {
        $produtos = Produto::with('unidade','grupo')->where('ativo',1)->get();
    	return view('manutencao.produtos')->with(['produtos' => $produtos]);
    }

    public function produtosdesativados()
    {
        $produtosDesativados = Produto::with('unidade','grupo')->where('ativo',0)->get();
        return view('manutencao.produtos')->with(['produtos' => $produtosDesativados]);
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

         return view('manutencao.produto')->with(['unidades' => $unidadesNome, 'grupos' => $gruposNome]);
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

        return view('manutencao.produto')->with(['produto' => $produto, 'unidades' => $unidadesNome, 'grupos' => $gruposNome]);
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

        return view('manutencao.pedido')->with(['pedido' => $ped, 'destinos' => $destinosNome, 'statuses' => $statusesNome, 'clientes' => $clientesNome, 'produtos' => $produtos, 'unidades' => $unidades]);
    }

    public function novoLocal()
    {
        return view('manutencao.local');
    }

    public function editLocal(Destino $destino)
    {
        return view('manutencao.local')->with(['destino' => $destino]);
    }

    public function solicitacoes()
    {
        $solicitacoes = Solicitacao::all();
        return view('manutencao.solicitacoes')->with(['solicitacoes' => $solicitacoes]);
    }

    public function pedidos()
    {
        $pedidos = Pedido::with('usuario','destino')->get();
    	return view('manutencao.pedidos')->with(['pedidos' => $pedidos]);
    }

    public function usuarios()
    {
    	return view('manutencao.usuarios');
    }

    public function locais()
    {
        $destinos = Destino::all();
       return view('manutencao.locais')->with(['destinos' => $destinos]);
    }
}

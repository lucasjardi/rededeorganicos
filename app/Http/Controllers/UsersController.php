<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Solicitacao;
use App\Mail\SolicitacaoCadastrada;
use Illuminate\Support\Facades\Mail;
use App\Produtor;
use App\Cliente;
use App\Cidade;
use App\Pedido;
use App\StatusPedido;
use App\User;
use Auth;

class UsersController extends Controller
{

    public function solicitarCadastro(Request $request)
    {
    	$this->validate($request, [
    		'name' => 'required',
    		'email' => 'required|email|unique:users',
        	'password' => 'required|string|min:6|confirmed',
        	'nivel' => 'required'
        ]);

    	$solicitacao = Solicitacao::create($request->all());

        Mail::to("rededeorganicososorio@gmail.com")->send(new SolicitacaoCadastrada($solicitacao)); // mudar depois para email admin

    	\Session::flash('mensagem_sucesso', 'Solicitação Enviada! Assim que seu cadastro estiver liberado você irá receber por email um aviso!');

    	return back();

    }

    public function paginaPreencherInformacoes()
    {
        if (Auth::user()->codNivel <= 3) {
            return \Redirect::to('');
        }
        $produtor =  null; $cliente = null;
        if(Auth::user()->codNivel == 4)
            $produtor = Produtor::find(Auth::user()->id);
        if(Auth::user()->codNivel == 5)
            $cliente = Cliente::find(Auth::user()->id);

        $cidades = Cidade::all();
        $cidadesNome = array();
        foreach ($cidades as $cidade){
            $cidadesNome[ $cidade->codigo ] = $cidade->descricao;
        }

        return view('informacoesUsuario')->with(['produtor' => $produtor, 'cliente' => $cliente,'cidades' => $cidadesNome]);
    }



    public function updateProdutor(Produtor $produtor, Request $request)
    {
        $this->validate($request, [
            // 'cpf' => 'required',
            'telefone' => 'required',
            'endereco' => 'required',
        ]);

        $produtor->update($request->all());

        \Session::flash('mensagem_sucesso', 'Dados Atualizados!');

        return back();
    }


    public function updateCliente(Cliente $cliente, Request $request)
    {
        $this->validate($request, [
            'cpf' => 'required',
            'telefone' => 'required',
            'endereco' => 'required',
        ]);

        $cliente->update($request->all());

        \Session::flash('mensagem_sucesso', 'Dados Atualizados!');

        return back();
    }


    public function getPedidosUser(Request $request)
    {
        $pedidos = Pedido::where('codCliente',$request->user()->id)
                            ->with('itens','st')
                            ->orderBy('dataPedido', false)
                            ->get();
        // $statusespedidos = StatusPedido::all()->toArray();

        return view('pedidos')->with(['pedidos' => $pedidos]);
    }


    public function getClientes()
    {
        $term = $_GET['term'];
        $clientes = User::where('codNivel',5)->where('name','like','%'.$term.'%')->get();
        $clientesNome = array();
        $clients = array();
        foreach ($clientes as $cliente){
            // $clientesNome[ $cliente->id ] = $cliente->name;
            $clientesNome["id"] = $cliente->id;
            $clientesNome["value"] = $cliente->name;
            array_push($clients, $clientesNome);
        }
        return $clients;
    }
}

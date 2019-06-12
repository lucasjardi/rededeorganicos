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
use App\ItemPedido;
use App\Cesta;
use App\StatusPedido;
use App\User;
use Auth;
use Carbon\Carbon;

class UsersController extends Controller
{
    public function solicitarPedido(Request $request)
    {
        $this->validate($request, [
            'local_de_retirada' => 'required'
        ]);
        
        $pedido = new Pedido([
            'codCliente' => $request->user()->id,
            'codDestino' => $request->local_de_retirada,
            'dataPedido' => Carbon::now(),
            'valor' => $request->total
        ]);

        if($pedido->save()){ // se salvou o pedido, salva os itens do pedido
            $cestaUser = Cesta::where('user_id',$request->user()->id)->get();
            foreach ($cestaUser as $cesta) {
                $descricao = $cesta->quantidade." ". $cesta->unidade ." de ". $cesta->produto->nome;
                ItemPedido::create([
                    'codPedido' => $pedido->codigo,
                    'codProduto' => $cesta->produto->codigo,
                    'quantidade' => $cesta->quantidade,
                    'valorTotal' => $cesta->subtotal,
                    'descricao' => $descricao
                ]);
            }

            foreach ($cestaUser as $cesta) {
                $cesta->delete();
            }

            return \Redirect::to('/solicitado')
            ->with('message','Seu Pedido foi Solicitado! Você será contatado(a) 
                                        quando estiver pronto para buscá-lo!');
            // return ["message" => "inserted"];
        }

        return \Redirect::to('/solicitado')->with('message','Não Foi Possível Fazer o Pedido. Tente Novamente...');
    }

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
        $pedidos = null;
        $model = '';
        if($request->user()->codNivel === 5){
            $pedidos = Pedido::where('codCliente',$request->user()->id)
                            ->with('itens','st')
                            ->orderBy('dataPedido', false);
            $model='cliente';
        }
        if($request->user()->codNivel === 4){
            $pedidos = Pedido::with('itens','st')->whereHas('itens', function ($query){
                $query->whereHas('produto', function ($query){
                    $query->whereHas('prod_produzido', function ($query){
                        $query->where('codProdutor',Auth::id());
                    });
                });
            })->whereHas('st', function ($query){
                $query->where('descricao','Confirmado');
            });
            $model='produtor';
        }
        return view('pedidos')->with(['pedidos' => $pedidos->get(),'model'=>$model]);
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

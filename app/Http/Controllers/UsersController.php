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
use App\Destino;
use App\ProdutoProduzido;
use Auth;
use Carbon\Carbon;
use Jenssegers\Agent\Agent;

class UsersController extends Controller
{
    protected $agent;

    public function __construct()
    {
        $this->agent = new Agent();
    }

    public function index()
    {
        return view('manutencao.users.index', [
            'users' => User::with('nivel','produtor.cidade','cliente.cidade')->whereHas('nivel')->where('email','!=','rededeorganicososorio@gmail.com')->latest()->get(),
            'isMobile'=>$this->agent->isMobile()
        ]);
    }

    public function edit(User $user)
    {
        if($user->codNivel==4)
            $user->load('produtor.cidade');
        elseif($user->codNivel==5)
            $user->load('cliente.cidade');
        
        $cidades = Cidade::all();
        $cidadesNome = [];
        foreach($cidades as $cidade){
            $cidadesNome[$cidade->codigo] = $cidade->descricao;
        }

        return view('manutencao.users.form', [
            'user' => $user,
            'niveis' => [4=>'Produtor',5=>'Cliente'],
            'cidades' => $cidadesNome,
            'isMobile'=>$this->agent->isMobile()
        ]);
    }

    public function update(User $user, Request $request)
    {
        $user->fill($request->all());
        if( $user->save() ){
            if($request->produtor && $request->produtor['telefone'] && $request->produtor['endereco']) {
                if($user->produtor()->count() > 0)
                    $produtor = Produtor::find($user->id);
                else
                    $produtor = new Produtor;
                $produtor->codigo = $user->id;
                $produtor->codCertificado = 1;
                $produtor->codCidade = $request->produtor['cidade']['codigo'];
                $produtor->telefone = $request->produtor['telefone'];
                $produtor->endereco = $request->produtor['endereco'];
                $produtor->save();
            }
            if($request->cliente) {
                if($user->cliente()->count() > 0)
                    $cliente = Cliente::find($user->id);
                else
                    $cliente = new Cliente;
                $cliente->codigo = $user->id;
                $cliente->codCidade = $request->cliente['cidade']['codigo'];
                $cliente->cpf = $request->cliente['cpf'];
                $cliente->telefone = $request->cliente['telefone'];
                $cliente->endereco = $request->cliente['endereco'];
                $cliente->save();
            }
        }

        \Session::flash('mensagem_sucesso','Usuário Atualizado com Sucesso!');

        return back();
    }

    public function destroy(User $user)
    {
        $user->delete();

        \Session::flash('mensagem_sucesso','Usuário Deletado com Sucesso!');

        return back();
    }

    public function solicitarPedido(Request $request)
    {
        $this->validate($request, [
            'local_de_retirada' => 'required'
        ]);

        $destino = Destino::where('codigo', $request->local_de_retirada)->first();

        if ($destino && $destino->visibility === 0) {
            \Session::flash('local_not_visible', 'Este local não está mais disponível, por favor tente outro local.');

            if (\Session::has('localSelected')) {
                \Session::forget('localSelected');
            }

            return back();
        }
        
        $pedido = new Pedido([
            'codCliente' => $request->user()->id,
            'codDestino' => $request->local_de_retirada,
            'dataPedido' => Carbon::now(),
            'valor' => $request->total,
            'descricao' => $request->descricao
        ]);

        if($pedido->save()){ // se salvou o pedido, salva os itens do pedido
            $cestaUser = Cesta::leftJoin('prod_produzido', function ($query) { // só pega os produtos que tem na lista da semana
                $query->on('prod_produzido.codProduto','=','cesta.produto_id')
                    ->on('prod_produzido.codProdutor','=','cesta.codProdutor');
            })->where('user_id',$request->user()->id)
            ->select('cesta.*','prod_produzido.codigo')
            ->get();

            foreach ($cestaUser as $cesta) {
                $descricao = $cesta->quantidade." ". $cesta->unidade ." de ". $cesta->produto->nome;
                ItemPedido::create([
                    'codPedido' => $pedido->codigo,
                    'codProduto' => $cesta->produto_id,
                    'codProdutor' => $cesta->codProdutor,
                    'quantidade' => $cesta->quantidade,
                    'valorTotal' => $cesta->subtotal,
                    'descricao' => $descricao,
                    'available' => $cesta->codigo ? 1 : 0
                ]);
            }

            foreach ($cestaUser as $cesta) {
                $cesta->delete();
            }

            $pedido->load('itens','destino.desconto');

            $pedidosNotAvailableSoma = 0;
            $pedidosNotAvailableSoma = $pedido->itens->reduce(function ($carry, $item) {
                return !$item->available ? ($carry + $item->valorTotal) : $carry + 0;
            });

            if ($pedidosNotAvailableSoma > 0) {
                $pedido->valor = $request->total - $pedidosNotAvailableSoma;
                $pedido->save();
            }

            return view('solicitado',['pedido'=>$pedido]);
        }

        return \Redirect::to('/solicitado')->with('message','Não Foi Possível Fazer o Pedido. Tente Novamente...');
    }

    public function solicitarCadastro(Request $request)
    {
    	$this->validate($request, [
    		'name' => 'required',
    		'email' => 'required|email|unique:users',
        	'password' => 'required|string|min:6|confirmed',
            'nivel' => 'required',
            'codCidade' => 'required',
            'telefone' => 'required',
            'endereco' => 'required'
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
                            ->with(['itens' => function ($query) {
                                $query->where('available', 1)->with('produtor.usuario:id,name');
                            },'st','usuario'])
                            ->orderBy('dataPedido', false);
            $model='cliente';
        }
        if($request->user()->codNivel === 4){
            $pedidos = Pedido::with(['itens' => function ($query) {
                $query->where('available', 1)->with('produtor');
            },'st','usuario'])
            ->whereHas('itens', function ($query){
                $query->whereHas('produtor', function ($query){
                    $query->where('codigo', Auth::id());
                })->where('available', 1);
            })->whereHas('st', function ($query){
                $query->where('descricao','Confirmado');
            })
            ->orderBy('dataPedido', false);
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

    public function getProdutores()
    {
        $term = $_GET['term'];
        $produtores = User::where('codNivel',4)->where('name','like','%'.$term.'%')->get();
        $produtoresNome = array();
        $produtoresResponse = array();
        foreach ($produtores as $produtor){
            $produtoresNome["id"] = $produtor->id;
            $produtoresNome["value"] = $produtor->name;
            array_push($produtoresResponse, $produtoresNome);
        }
        return $produtoresResponse;
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Produto;
use App\Pedido;
use App\Unidade;
use Illuminate\Support\Facades\DB;
use Auth;
use App\Destino;
use App\Cliente;
use App\Produtor;
use App\ProdutoProduzido;
use App\Desconto;

class HomeController extends Controller
{

    public function __construct()
    {
        $this->middleware('verificarhorarioacesso');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $nivel = Auth::user()->codNivel;

        if ( $nivel < 3 ) { // super usuarios
            $pedidos = Pedido::with('usuario', 'cliente','itens','destino')->where('status',1)->orderBy('dataPedido',false)->paginate(10);
            return view('admin')->with(['pedidos' => $pedidos]);
        }
        else if( $nivel == 4 ) { // produtor

            $prods = DB::table("produto")
                ->join('produtor_produz', 'produto.codigo','=','produtor_produz.codProduto')
                ->leftJoin('valores_produtos_ultima_semana', 'produto.codigo','=','valores_produtos_ultima_semana.codProduto')
                ->select('produto.*','valores_produtos_ultima_semana.valor')
                ->where('produtor_produz.codProdutor',Auth::user()->id)
                ->get();
            
            $prodsAux = ProdutoProduzido::where('codProdutor',Auth::user()->id)->get();
            $prodsJaSelecionados = array();
            foreach ($prodsAux as $produto){
                $prodsJaSelecionados[ $produto->codProduto ] = $produto->codUnidade;
            }

            $userID = Auth::user()->id;
            $produtor = Produtor::find($userID);

            $preencherInformacoes = false;

            if($produtor->endereco=="" && $produtor->telefone=="")
                $preencherInformacoes = true;

            $unidades = Unidade::all();
            return view('produtor')->with(['produtos' => $prods, 'unidades' => $unidades, 'preencher' =>  $preencherInformacoes, 'prodsjaselecionados' => $prodsJaSelecionados]);
        }
        else if( $nivel == 5 ) { // cliente
               
            if (\Session::has('localSelected')) {
                $destino = Destino::where('codigo',\Session::get('localSelected'))->first();
                return \Redirect::to('/retirar?destino='.$destino->codigo);
            }

            $userID = Auth::user()->id;
            $cliente = Cliente::find($userID);

            $preencherInformacoes = false;

            if($cliente->cpf == "" && $cliente->endereco=="" && $cliente->telefone=="")
                $preencherInformacoes = true;

            $locaisDeRetirada = Destino::all();
            

            return view('cliente')
                    ->with([
                        'locaisDeRetirada' => $locaisDeRetirada, 
                        'preencher' => $preencherInformacoes,
                        'descontos' => Desconto::with('destino')->get()
                        ]);
        }
    }

}

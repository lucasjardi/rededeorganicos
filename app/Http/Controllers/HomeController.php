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
use App\ValorUltimaSemana;

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
            $pedidos = Pedido::with('usuario', 'cliente','itens','destino.desconto')->where('status',1)->orderBy('dataPedido',false)->paginate(10);
            return view('admin')->with(['pedidos' => $pedidos]);
        }
        else if( $nivel == 4 ) { // produtor

            $prodsProduzidos = Produto::whereHas('prod_produzido', function ($query){
                $query->where('codProdutor', Auth::id());
            });

            $prods = Produto::whereHas('produtor_produz', function ($query){
                $query->where('codProdutor', Auth::id());
            })
            ->with(['valorultimasemana'=>function ($query){
                $query->where('codProdutor', Auth::id());
            }])
            ->union($prodsProduzidos)
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

            if($cliente->cpf == "" || $cliente->endereco=="" || $cliente->telefone=="")
                $preencherInformacoes = true;

            $locaisDeRetirada = Destino::where('visibility',1)->get();
            

            return view('cliente')
                    ->with([
                        'locaisDeRetirada' => $locaisDeRetirada, 
                        'preencher' => $preencherInformacoes,
                        'descontos' => Desconto::with('destino')->get()
                        ]);
        }
    }

}

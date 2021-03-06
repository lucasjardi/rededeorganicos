<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Produto;
use App\Unidade;
use App\Grupo;
use App\Cliente;
use App\Destino;
use App\Pedido;
use App\Cesta;
use App\ItemPedido;
use App\StatusPedido;
use Carbon\Carbon;
use App\Produtor;
use App\ProdutorProduz;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Jenssegers\Agent\Agent;
use Auth;

class ProdutosController extends Controller
{

    private $agent;
    public function __construct()
    {
        $this->middleware(['auth','isadmin'],['except' => ['index','pesquisaPorNome']]);
        $this->agent = new Agent();
    }

    public function index(Request $request, $ativo = 1)
    {
        $produtos = Produto::with('unidade','grupo')->where('ativo',$ativo)
        ->when(Auth::user()->codNivel==4, function ($query){
            $query->whereNOTIn('codigo',function($query){
                $query->select('codProduto')->from('produtor_produz')->where('codProdutor',Auth::id());
            });
        });

        $countProdutorProduz = ProdutorProduz::where('codProdutor',Auth::user()->id)->count();

        $isManutencao = $request->is('manutencao/*');
        $view = $isManutencao ? 'manutencao.produtos.index' : 'produtos';

    	return view($view,[
            'produtos' => $isManutencao?$produtos->get():$produtos->paginate(40), 
            'init' => true, 
            'aviso' => $countProdutorProduz == 0 ? true : false, 
            'isMobile' => $this->agent->isMobile()
        ]);
    }

    public function create($produto = null)
    {
        return view('manutencao.produtos.form',[
            'produto' => $produto,
            'unidades' => Unidade::allAsArray(), 
            'grupos' => Grupo::allAsArray(), 
            'isMobile' => $this->agent->isMobile()
        ]);
    }

    public function edit(Produto $produto)
    {
        return $this->create($produto);
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

    public function produtosdesativados(Request $request)
    {
        return $this->index( $request, 0 );
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



    //LariMoro
    public function relatoriosView()
    {
        return view('relatorios-produtos', [
            'clientes' => Cliente::with('usuario')->get(),
            'produtores' => Produtor::with('usuario')->get(),
            'destinos' => Destino::all(),
            'statuses' => StatusPedido::all()
        ]);
    }


    public function relatorio(Request $request)
    {

        /*SELECT codProduto, produto.nome as prodnome, SUM(quantidade) as total 
         FROM `item_pedido`
         INNER JOIN produto 
         on produto.codigo=item_pedido.codProduto 
         WHERE codProdutor = 570 
         GROUP BY codProduto */

        $dataPedidoFim = request('dataPedidoFim');
        if(Carbon::today()->toDateString() === request('dataPedidoFim')){
            $dataPedidoFim = $dataPedidoFim . ' 23:59:59';
        }



        $itemPedido = DB::table('item_pedido')
           //->whereBetween('dataPedido', [request('dataPedidoInicio'), $dataPedidoFim])
         
           ->select('codProduto','pedido.codCliente as coduser', DB::raw('group_concat(destino.descricao separator " | ") as destino'),'item_pedido.descricao', 'users.name as produtornome', 'produto.nome as prodnome', DB::raw('SUM(quantidade) as total'))
           ->join('produto', 'produto.codigo', '=', 'item_pedido.codProduto')

           ->join('users', 'users.id', '=', 'item_pedido.codProdutor')
           ->join('pedido', 'pedido.codigo', '=', 'item_pedido.codPedido')
           ->join('destino', 'destino.codigo', '=', 'pedido.codDestino')

           ->groupBy('item_pedido.codProduto');


           if(request('produtor')!==null){
           // $itemPedido->where('codProdutor','=',request('produtor'));

            
            foreach (request('produtor') as $value) {
              //  var_dump($value);
                $itemPedido->orWhere('codProdutor','=',$value);
            }
            //$users->join('users', 'users.id', '=', 'item_pedido.codProdutor');
           }

          
           if(request('destinos')!==null){
            $itemPedido->whereIn('codDestino',request('destinos'));
           }
           if(request('statuses')!==null){
            $itemPedido->whereIn('status',request('statuses'));
           }
        
           $itemPedido =  $itemPedido->get();


           

        $request->flash();

        return view('relatorios-produtos', [
            'pedidos'=>$itemPedido,
           // 'cliente' => $cliente,
            'clientes' => Cliente::with('usuario')->get(),
            'destinos' => Destino::all(),
            'produtores' => Produtor::with('usuario')->get(),
            'statuses' => StatusPedido::all()
        ]);
    }
  //------LariMoro





    public function pesquisaPorNome(Request $request)
    {
        $term = isset($_GET['term']) ? $_GET['term'] : $request->nome;
        $produtos = Produto::
                    where( 'nome', 'LIKE', '%' . $term . '%' )
                    ->where('ativo',1)
                    ->get();
        if(isset($_GET['term'])) {
            $produtosNome = array();
            $prods = array();
            foreach ($produtos as $produto) {
                $produtosNome["id"] = $produto->codigo;
                $produtosNome["value"] = $produto->nome;
                array_push($prods, $produtosNome);
            }

            return $prods;
        } else {
            $produtos = DB::table("produto")->select('*')
                ->where( 'nome', 'LIKE', '%' . $term . '%' )
                ->whereNOTIn('codigo',function($query){
                    $query->select('codProduto')->from('produtor_produz')->where('codProdutor',Auth::id());
                })
                ->where('ativo',1)
                ->paginate(40);

            return view('produtos')
                    ->with([
                            'produtos' => $produtos, 
                            'pesquisa' => $request->nome, 
                            'isMobile' => $this->agent->isMobile()
                            ]);
        }
    }


    public function gerarCSV2(Request $request)
    {
        $url = 'https://json-csv.com/api/getcsv';
        $email = 'negeralej@uber-mail.com';
        $json = $request->input('pedidos');

        $postdata = http_build_query(
            array(
                'email' => $email,
                'json' => $json
            )
        );

        $opts = array(
            'http' => array(
                'method'  => 'POST',
                'header'  => 'Content-type: application/x-www-form-urlencoded',
                'content' => $postdata
            )
        );
        $filename='relatorio_pedidos_'.date("d_m_y");
        // Download the file
        header('Content-Disposition: attachment; filename="'.$filename.'.csv"');
        header("Content-Type: text/csv");

        $context  = stream_context_create($opts);
        $result = file_get_contents($url, false, $context);

        print $result;
    }
}

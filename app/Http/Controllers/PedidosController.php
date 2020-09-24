<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

use App\Mail\PedidoConfirmado;
use Carbon\Carbon;
use App\Pedido;
use App\Cesta;
use App\ItemPedido;
use App\Unidade;
use App\Produto;
use App\Destino;
use App\StatusPedido;
use Auth;
use Jenssegers\Agent\Agent;
use App\Cliente;

class PedidosController extends Controller
{

    private $agent;
    public function __construct()
    {
        $this->middleware(['auth','isadmin']);
        $this->agent = new Agent();
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pedidos = Pedido::with('usuario','destino','st')->orderBy('dataPedido',false)->get();
    	return view('manutencao.pedidos.index')->with(['pedidos' => $pedidos, 'isMobile' => $this->agent->isMobile()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($pedido=null)
    {
        // $ped = Pedido::with('itens','usuario')->find($pedido->codigo);
        if(!!$pedido) $pedido->load('itens.user','usuario');

        return view('manutencao.pedidos.form',[
            'pedido' => $pedido, 
            'destinos' => Destino::allAsArray(), 
            'statuses' => StatusPedido::allAsArray(), 
            'produtos' => Produto::all(), 
            'unidades' => Unidade::all(), 
            'isMobile' => $this->agent->isMobile()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        $request->merge(['valor' => str_replace(",",".",$request->valor)]);
        $this->validate($request, [
            'codCliente' => 'required',
            'valor' => 'required'
        ]);

        $pedido = Pedido::create( $request->all() + ["dataPedido"=> Carbon::now()]);

        if ($request->has("codProduto")) {
            $i = 0;
            foreach ($request->codProduto as $codigoProduto) {
                if (!($request->qtdProduto[$i]=="" && $request->valorTotal[$i]=="")) {
                    $produto = Produto::with('unidade')->find($codigoProduto);
                    $descricao = $request->qtdProduto[$i]." ". $produto->unidade->descricao ." de ". $produto->nome;
                    ItemPedido::create([
                        'codPedido' => $pedido->codigo,
                        'codProduto' => $codigoProduto,
                        'quantidade' => $request->qtdProduto[$i],
                        'valorTotal' => $request->valorTotal[$i],
                        'descricao' => $descricao
                    ]);
                }
            }
        }

        \Session::flash('mensagem_sucesso', 'Pedido criado com sucesso!');

        return redirect('manutencao/pedido/'.$pedido->codigo.'/editar');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Pedido  $pedido
     * @return \Illuminate\Http\Response
     */
    public function show(Pedido $pedido)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Pedido  $pedido
     * @return \Illuminate\Http\Response
     */
    public function edit(Pedido $pedido)
    {
        return $this->create($pedido);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Pedido  $pedido
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pedido $pedido)
    {
        if (!$request->has('fromHomePage')) {
            $this->validate($request, [
                'valor' => 'required',
                'codCliente' => 'required'
            ]);
            $request->merge(['valor' => str_replace(",",".",$request->valor)]);
        }
        $enviarEmailConfirmado = $pedido->status != 3 && $request->status == 3;
        $pedido->update($request->all());

        if( $enviarEmailConfirmado ) {
            $pedido->load('usuario', 'destino', 'itens');
            Mail::to($pedido->usuario->email)->send(new PedidoConfirmado($pedido)); 
        }

        if ($request->has("codProduto")) {
            $i = 0;
            foreach ($request->codProduto as $codigoProduto) {
                if (!($request->qtdProduto[$i]=="" && $request->valorTotal[$i]=="")) {
                    $produto = Produto::with('unidade')->find($codigoProduto);
                    $descricao = $request->qtdProduto[$i]." ". $produto->unidade->descricao ." de ". $produto->nome;
                    ItemPedido::create([
                        'codPedido' => $pedido->codigo,
                        'codProduto' => $codigoProduto,
                        'quantidade' => $request->qtdProduto[$i],
                        'valorTotal' => $request->valorTotal[$i],
                        'descricao' => $descricao
                    ]);
                }
            }
        }

        \Session::flash('mensagem_sucesso', 'Pedido atualizado com sucesso!');

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Pedido  $pedido
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pedido $pedido)
    {
        $pedido->delete();

        \Session::flash('mensagem_sucesso', 'Pedido deletado com sucesso!');

        return back();
    }

    public function relatoriosView()
    {
        return view('relatorios', [
            'clientes' => Cliente::with('usuario')->get(),
           
            'destinos' => Destino::all(),
            'statuses' => StatusPedido::all()
        ]);
    }

    public function relatorio(Request $request)
    {
        $dataPedidoFim = request('dataPedidoFim');
        if(Carbon::today()->toDateString() === request('dataPedidoFim')){
            $dataPedidoFim = $dataPedidoFim . ' 23:59:59';
        }
        $pedidos = Pedido::with('cliente','destino','st','usuario')
        ->whereBetween('dataPedido', [request('dataPedidoInicio'), $dataPedidoFim])
        ->when(request('valorInicial')!==null, function ($query){
            $query->where('valor','>=',request('valorInicial'));
        })
        ->when(request('valorFinal')!==null, function ($query){
            $query->where('valor','<=',request('valorFinal'));
        })
        ->when(request('clientes'), function ($query){
            $query->whereIn('codCliente',request('clientes'));
        })
        ->when(request('destinos'), function ($query){
            $query->whereIn('codDestino',request('destinos'));
        })
        ->when(request('statuses'), function ($query){
            $query->whereIn('status',request('statuses'));
        })
        ->get();

        $request->flash();

        return view('relatorios', [
            'pedidos'=>$pedidos,
            'clientes' => Cliente::with('usuario')->get(),
            'destinos' => Destino::all(),
            'statuses' => StatusPedido::all()
        ]);
    }

    public function gerarCSV(Request $request)
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

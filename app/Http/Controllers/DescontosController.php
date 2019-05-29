<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Desconto;
use App\Destino;
use Jenssegers\Agent\Agent;

class DescontosController extends Controller
{
    private $agent;
    public function __construct()
    {
        $this->middleware(['auth','isadmin']);
        $this->agent = new Agent();
    }

    public function index()
    {
        $descontos = Desconto::all();
        return view('manutencao.descontos.index', ['descontos'=>$descontos,'isMobile' => $this->agent->isMobile()]);
    }

    public function create()
    {
        $destinos = Destino::all();
        $destinosNome = array();
        foreach ($destinos as $destino){
            $destinosNome[ $destino->codigo ] = $destino->descricao;
        }
        return view('manutencao.descontos.form',['destinos'=>$destinosNome, 'isMobile' => $this->agent->isMobile()]);
    }

    public function edit(Desconto $desconto)
    {
        $destinos = Destino::all();
        $destinosNome = array();
        foreach ($destinos as $destino){
            $destinosNome[ $destino->codigo ] = $destino->descricao;
        }
        return view('manutencao.descontos.form', ['destinos'=>$destinosNome,'desconto'=>$desconto,'isMobile' => $this->agent->isMobile()]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'destino_id' => 'required',
            'porcentagem' => 'required',
            'descricao' => 'required'
        ]);

    	Desconto::create($request->all());

    	\Session::flash('mensagem_sucesso', 'Desconto criado com sucesso!');

        return back();
    }


    public function update(Request $request, Desconto $desconto)
    {
        $this->validate($request, [
            'destino_id' => 'required',
            'porcentagem' => 'required',
            'descricao' => 'required'
        ]);
    	$desconto->update($request->all());

    	\Session::flash('mensagem_sucesso', 'Desconto atualizado com sucesso!');

        return back();
    }

    public function destroy(Desconto $desconto)
    {
    	$desconto->delete();

    	\Session::flash('mensagem_sucesso', 'Desconto removido com sucesso!');

        return back();

    }
}

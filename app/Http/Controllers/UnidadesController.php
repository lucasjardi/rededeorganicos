<?php

namespace App\Http\Controllers;

use App\Unidade;
use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;

class UnidadesController extends Controller
{
    protected $agent;

    public function __construct()
    {
        $this->agent = new Agent();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $unidades = Unidade::all();
    	return view('manutencao.unidades.index')->with(['unidades' => $unidades, 'isMobile' => $this->agent->isMobile()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('manutencao.unidades.form', ['isMobile' => $this->agent->isMobile()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'descricao' => 'required'
        ]);
        Unidade::create($request->all());
        \Session::flash('mensagem_sucesso', 'Unidade criado com sucesso!');
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Unidade  $unidade
     * @return \Illuminate\Http\Response
     */
    public function show(Unidade $unidade)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Unidade  $unidade
     * @return \Illuminate\Http\Response
     */
    public function edit(Unidade $unidade)
    {
        return view('manutencao.unidades.form', ['isMobile' => $this->agent->isMobile(), 'unidade' => $unidade]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Unidade  $unidade
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Unidade $unidade)
    {
        $this->validate($request, [
            'descricao' => 'required'
        ]);
        $unidade->update($request->all());
        \Session::flash('mensagem_sucesso', 'Unidade criado com sucesso!');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Unidade  $unidade
     * @return \Illuminate\Http\Response
     */
    public function destroy(Unidade $unidade)
    {
        $unidade->delete();
        \Session::flash('mensagem_sucesso', 'Unidade deletada com sucesso!');
        return back();
    }
}

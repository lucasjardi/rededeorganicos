<?php

namespace App\Http\Controllers;

use App\Grupo;
use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;

class GruposController extends Controller
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
        $grupos = Grupo::all();
    	return view('manutencao.grupos.index')->with(['grupos' => $grupos, 'isMobile' => $this->agent->isMobile()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('manutencao.grupos.form', ['isMobile' => $this->agent->isMobile()]);
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
        Grupo::create($request->all());
        \Session::flash('mensagem_sucesso', 'Grupo criado com sucesso!');
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Grupo  $grupo
     * @return \Illuminate\Http\Response
     */
    public function show(Grupo $grupo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Grupo  $grupo
     * @return \Illuminate\Http\Response
     */
    public function edit(Grupo $grupo)
    {
        return view('manutencao.grupos.form', ['isMobile' => $this->agent->isMobile(), 'grupo' => $grupo]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Grupo  $grupo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Grupo $grupo)
    {
        $this->validate($request, [
            'descricao' => 'required'
        ]);
        $grupo->update($request->all());
        \Session::flash('mensagem_sucesso', 'Grupo criado com sucesso!');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Grupo  $grupo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Grupo $grupo)
    {
        $grupo->delete();
        \Session::flash('mensagem_sucesso', 'Grupo deletado com sucesso!');
        return back();
    }
}

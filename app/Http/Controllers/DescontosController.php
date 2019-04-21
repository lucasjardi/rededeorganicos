<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Desconto;

class DescontosController extends Controller
{
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

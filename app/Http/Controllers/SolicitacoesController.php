<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;
use App\Mail\SolicitacaoAprovada;
use Illuminate\Http\Request;
use App\Solicitacao;
use App\User;
use App\Cliente;
use App\Produtor;

class SolicitacoesController extends Controller
{
    
    public function aceitar(Solicitacao $solicitacao)
    {
    	$user = User::create([
    			'name' => $solicitacao->name,
    			'email' => $solicitacao->email,
    			'password' => bcrypt($solicitacao->password),
    			'codNivel' => $solicitacao->nivel,
    			'ativo' => 1
    	]);
    	if ($solicitacao->nivel == 4) {
    		Produtor::create([
    			'codigo' => $user->id,
    			'codCertificado' => 1,
    			'codCidade' => $solicitacao->codCidade,
    			'telefone' => $solicitacao->telefone,
    			'endereco' => $solicitacao->endereco
    		]);
    	}

    	if ($solicitacao->nivel == 5) {
    		Cliente::create([
    			'codigo' => $user->id,
                'codCidade' => $solicitacao->codCidade,
    			'telefone' => $solicitacao->telefone,
    			'endereco' => $solicitacao->endereco,
    			'cpf' => ""
    		]);
    	}

        Mail::to($solicitacao->email)->send(new SolicitacaoAprovada($solicitacao));

    	$solicitacao->delete();

    	\Session::flash('mensagem_sucesso', 'Solicitação aprovada! Usuário Cadastrado!');

    	return back();
    }

    public function rejeitar(Solicitacao $solicitacao)
    {
    	$solicitacao->delete();

        \Session::flash('mensagem_sucesso', 'Solicitação deletada!');

        return back();
    }
}

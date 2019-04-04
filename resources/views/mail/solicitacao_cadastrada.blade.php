<div>
	Uma nova Solicitação de Cadastro chegou!<br>
	Tipo de Conta: {{ $solicitacao->nivel == 4 ? "PRODUTOR" : "CLIENTE" }} <br>
	Nome: {{ $solicitacao->name }}<br>
	E-mail: {{ $solicitacao->email }} <br><br>
	Para ACEITAR ou REJEITAR esse cadastro clique <a href="{{ url('/manutencao/solicitacoes') }}">nesse link</a> ou vá até o Painel e acesse o menu Manutenção > Solicitações.
</div>
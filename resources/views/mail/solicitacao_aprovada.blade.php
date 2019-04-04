<div>
	Olá {{ $solicitacao->name }}, sua Solicitação de Cadastro foi Aprovada! <br>
	Seu LOGIN: <br>
	E-mail: {{ $solicitacao->email }} <br>
	Senha: {{ $solicitacao->password }} <br>
	Tipo de Conta: {{ $solicitacao->nivel == 4 ? "Produtor" : "Cliente" }}
</div>
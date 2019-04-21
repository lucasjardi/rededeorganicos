<?php

Auth::routes();

Route::get('/', 'HomeController@index')->middleware('auth');
Route::get('/home', 'HomeController@index')->name('home')->middleware('auth');
Route::get('/retirar', 'DestinosController@setLocalRetirada')->middleware('auth','verificarhorarioacesso');
Route::post('/solicitarPedido','PedidosController@store')->middleware('auth','verificarhorarioacesso');
Route::get('/solicitado','PedidosController@solicitado')->middleware('auth','verificarhorarioacesso');
Route::post('/produto_produzido','ProdutoProduzidoController@store')->middleware('auth','verificarhorarioacesso');
Route::get('/selectItensAgain', 'ProdutoProduzidoController@killSessionAndRedirect')->middleware('auth','verificarhorarioacesso');
Route::post('/addProdutoAoProdutor','ProdutorProduzController@store')->middleware('auth','verificarhorarioacesso');
Route::get('/getProdutosProdutorProduz', 'ProdutorProduzController@get')->middleware('auth','verificarhorarioacesso');
Route::post('/solicitarCadastro','UsersController@solicitarCadastro')->name('solicitarCadastro');

Route::get('itempedido/{pedido}','ItemPedidoController@remove')->middleware('auth','verificarhorarioacesso');
Route::post('itempedido/{pedido}','ItemPedidoController@save')->middleware('auth','verificarhorarioacesso');

Route::prefix('cesta')->middleware('auth','verificarhorarioacesso')->group(function (){
    Route::get('', 'CestaController@index')->name('cesta');
    Route::post('', 'CestaController@adicionaNaCesta');
    Route::delete('/{cesta}','CestaController@delete');
    Route::get('/get','CestaController@getCesta');
    Route::get('/getLength','CestaController@getCestaLength');
    Route::get('/limpar','CestaController@limparCesta');
});


Route::prefix('manutencao')->middleware('auth','isadmin')->group(function (){
    Route::get('/produtos','ManutencaoController@produtos')->name('manutencao.produtos');
    Route::get('/produtos/desativados','ManutencaoController@produtosdesativados')->name('manutencao.produtos.desativados');
    Route::get('/produto/{produto}/editar','ManutencaoController@editProduto');
    Route::get('/produto/novo','ManutencaoController@novoProduto')->name('manutencao.novo.produto');
    Route::get('/pedido/novo','ManutencaoController@editPedido')->name('manutencao.novo.pedido');
    Route::get('/pedidos','ManutencaoController@pedidos')->name('manutencao.pedidos');
    Route::get('/pedido/{pedido}/editar','ManutencaoController@editPedido');
    Route::get('/solicitacoes','ManutencaoController@solicitacoes')->name('manutencao.solicitacoes');
    Route::get('/locais','ManutencaoController@locais')->name('manutencao.locais');
    Route::get('/local/novo','ManutencaoController@novoLocal')->name('manutencao.novo.local');
    Route::get('/local/{destino}/editar','ManutencaoController@editLocal');
    Route::get('/descontos','ManutencaoController@descontos')->name('manutencao.descontos');
    Route::get('/desconto/novo','ManutencaoController@novoDesconto')->name('manutencao.novo.desconto');
    Route::get('/desconto/{destino}/editar','ManutencaoController@editDesconto');
    Route::get('horarios-de-acesso/cliente','ManutencaoController@horariosAcessoCliente')->name('manutencao.horariosacessocliente');
    Route::get('horarios-de-acesso/produtor','ManutencaoController@horariosAcessoProdutor')->name('manutencao.horariosacessoprodutor');
});

Route::prefix('locais')->middleware('auth','isadmin')->group(function (){
    Route::post('/salvar','DescontosController@store');
    Route::patch('/{destino}','DescontosController@update');
    Route::delete('/{destino}','DescontosController@destroy');
});

Route::prefix('descontos')->middleware('auth','isadmin')->group(function (){
    Route::post('/salvar','DescontosController@store');
    Route::patch('/{destino}','DescontosController@update');
    Route::delete('/{destino}','DescontosController@destroy');
});



Route::prefix('produtos')->middleware('auth')->group(function (){
	Route::get('', 'ProdutosController@get')->name('produtos');
	Route::post('/salvar','ProdutosController@store')->middleware('isadmin');
    Route::patch('/{produto}','ProdutosController@update')->middleware('isadmin');
    Route::patch('/{produto}/desativar','ProdutosController@desativarProduto')->middleware('isadmin');
    Route::patch('/{produto}/ativar','ProdutosController@ativarProduto')->middleware('isadmin');

    Route::get('/pesquisa', 'ProdutosController@pesquisaPorNome')->name('pesquisaProduto');
});

Route::prefix('pedidos')->middleware('auth','isadmin')->group(function (){
    Route::post('','PedidosController@save');
	Route::delete('/{pedido}/remover','PedidosController@destroy');
    Route::patch('/{pedido}','PedidosController@update');
});


Route::prefix('solicitacao')->middleware('auth','isadmin')->group(function (){
	Route::get('/{solicitacao}/aceitar','SolicitacoesController@aceitar');
	Route::delete('/{solicitacao}/rejeitar','SolicitacoesController@rejeitar');
});

Route::prefix('user')->middleware('auth','verificarhorarioacesso')->group(function (){
    Route::get('/informacoes','UsersController@paginaPreencherInformacoes')->name('users.informacoes');
    Route::patch('/produtor/{produtor}','UsersController@updateProdutor');
    Route::patch('/cliente/{cliente}','UsersController@updateCliente');
    Route::get('/pedidos','UsersController@getPedidosUser')->name('user.pedidos');
});

Route::post('/grupos','ManutencaoController@saveGroup');

Route::get('/clientesAutoComplete','UsersController@getClientes');
Route::get('/produtosAutoComplete','ProdutosController@pesquisaPorNome');

Route::view('forbidden','forbidden');

Route::patch('horarioacessocliente','HorariosAcessoController@setHorarioAcessoCliente');
Route::patch('horarioacessoprodutor','HorariosAcessoController@setHorarioAcessoProdutor');
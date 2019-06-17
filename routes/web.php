<?php

Auth::routes();

Route::view('/historia', 'historia');

Route::get('/', 'HomeController@index')->middleware('auth');
Route::get('/home', 'HomeController@index')->name('home')->middleware('auth');
Route::get('/retirar', 'DestinosController@setLocalRetirada')->middleware('auth','verificarhorarioacesso');
Route::post('/solicitarPedido','UsersController@solicitarPedido')->middleware('auth','verificarhorarioacesso');
Route::view('/solicitado','solicitado')->middleware('auth','verificarhorarioacesso');
Route::post('/produto_produzido','ProdutoProduzidoController@store')->middleware('auth','verificarhorarioacesso');
Route::get('/selectItensAgain', 'ProdutoProduzidoController@killSessionAndRedirect')->middleware('auth','verificarhorarioacesso');
Route::post('/addProdutoAoProdutor','ProdutorProduzController@store')->middleware('auth','verificarhorarioacesso');
Route::get('/removeProdutoProdutor/{codProduto}','ProdutorProduzController@destroy')->middleware('auth','verificarhorarioacesso');
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
    Route::get('/produtos','ProdutosController@index')->name('manutencao.produtos');
    Route::get('/produtos/desativados','ProdutosController@produtosdesativados')->name('manutencao.produtos.desativados');
    Route::get('/produto/{produto}/editar','ProdutosController@edit');
    Route::get('/produto/novo','ProdutosController@create')->name('manutencao.novo.produto');
    Route::get('/pedido/novo','PedidosController@create')->name('manutencao.novo.pedido');
    Route::get('/pedidos','PedidosController@index')->name('manutencao.pedidos');
    Route::get('/pedido/{pedido}/editar','PedidosController@edit');
    Route::get('/solicitacoes','ManutencaoController@solicitacoes')->name('manutencao.solicitacoes');
    Route::get('/destinos','DestinosController@index')->name('manutencao.locais');
    Route::get('/destinos/novo','DestinosController@create')->name('manutencao.novo.local');
    Route::get('/destinos/{destino}/editar','DestinosController@edit');
    Route::get('/descontos','DescontosController@index')->name('manutencao.descontos');
    Route::get('/desconto/novo','DescontosController@create')->name('manutencao.novo.desconto');
    Route::get('/desconto/{desconto}/editar','DescontosController@edit');
    Route::get('horarios-de-acesso/cliente','ManutencaoController@horariosAcessoCliente')->name('manutencao.horariosacessocliente');
    Route::get('horarios-de-acesso/produtor','ManutencaoController@horariosAcessoProdutor')->name('manutencao.horariosacessoprodutor');
});

Route::resource('destinos', 'DestinosController');
Route::resource('pedidos','PedidosController');
Route::resource('descontos', 'DescontosController');

Route::get('/produtos/pesquisa', 'ProdutosController@pesquisaPorNome')->name('pesquisaProduto');
Route::resource('produtos', 'ProdutosController');
Route::get('/produtos', 'ProdutosController@index')->name('produtos');
Route::patch('/produtos/{produto}/desativar','ProdutosController@desativarProduto')->middleware('isadmin');
Route::patch('/produtos/{produto}/ativar','ProdutosController@ativarProduto')->middleware('isadmin');

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
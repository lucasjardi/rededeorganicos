$(function () {
	if($('.alert-success').length){
		swal({title: "Salvo!",icon: "success"});
	}
	$('#produtos').DataTable({"order": []});
	$('[data-toggle="tooltip"]').tooltip();
	$('#exampleModal').modal('show');

	$('.valor').mask('#.##0,00', {reverse: true});
	$('#valorItemPedido').mask('#.##0,00', {reverse: true});
	$('#acrescimo').mask('#.##0,00', {reverse: true});
	$('#cpf').mask('000.000.000-00', {reverse: true});
	$('#telefone').mask('(00) 00000-0000');

	$('.selectpicker').selectpicker();

	$("#nomeCliente" ).autocomplete({
		source: "/clientesAutoComplete",
		select: function( event, ui ) {
			$("#codCliente").val(ui.item.id);
		}
	});

	$("#nomeProduto" ).autocomplete({
		source: "/produtosAutoComplete",
		select: function( event, ui ) {
			$("#codProduto").val(ui.item.id);
		}
	});

	$("#produtorItem" ).autocomplete({
		source: "/produtoresAutoComplete",
		select: function( event, ui ) {
			$("#codProdutor").val(ui.item.id);
		}
	});

	if ($(window).width() < 768) {
	    // do something for small screens
	    $("#produtos, #pedidos").addClass("table-responsive");
	}
	else if ($(window).width() >= 768 &&  $(window).width() <= 992) {
	    // do something for medium screens
	    $("#produtos, #pedidos").addClass("table-responsive");
	}
	else if ($(window).width() > 992 &&  $(window).width() <= 1200) {
	    // do something for big screens
	    $("#produtos, #pedidos").removeClass("table-responsive");
	}
	else  {
	    // do something for huge screens
	    $("#produtos, #pedidos").removeClass("table-responsive");
	}

	const url = new URL(location.href);
	if (url.searchParams.has('sortBy') && url.searchParams.has('sortDirection')) {
		if (url.searchParams.get('sortDirection') === 'asc') {
			document.getElementById(url.searchParams.get('sortBy')).children[0].classList = 'fas fa-sort-up';
		}

		if (url.searchParams.get('sortDirection') === 'desc') {
			document.getElementById(url.searchParams.get('sortBy')).children[0].classList = 'fas fa-sort-down';
		}
	}
});

$("#addNaCesta").click(function() {
	$('#selectlocalretirada').prop('disabled', 'disabled');
$('#btnlocalretirada').prop('disabled', 'disabled');
});

$("#criarNovoGrupo").click(function () {
	$('#criarGrupo').modal('show');

	event.preventDefault();
});

$("#criarNovaUnidade").click(function () {
	$('#criarUnidade').modal('show');

	event.preventDefault();
});

$("#btnSalvarGrupo").click(function () {
	let descricao = $("#grupoDescricao").val();
	if(descricao) {
		axios.post('/save-group-only', {
			descricao: descricao
		})
		.then(function (response) {
			console.log(response);
	
			let o = new Option(response.data.descricao, response.data.codigo);
			/// jquerify the DOM object 'o' so we can use the html method
			$(o).html(response.data.descricao);
			$("#codGrupo").append(o);
			$('#criarGrupo').modal('toggle');
			$("#grupoDescricao").val("");
			$('#codGrupo').val(response.data.codigo);
		})
		.catch(function (error) {
			alert("Ocorreu um erro. Recarregue a pagina e tente de novo.");
		});
	}
});

$("#btnSalvarUnidade").click(function () {
	let descricao = $("#unidadeDescricao").val();
	if(descricao) {
		axios.post('/save-unidade-only', {
			descricao: descricao
		})
		.then(function (response) {
			console.log(response);
	
			let o = new Option(response.data.descricao, response.data.codigo);
			/// jquerify the DOM object 'o' so we can use the html method
			$(o).html(response.data.descricao);
			$("#codUnidade").append(o);
			$('#criarUnidade').modal('toggle');
			$("#unidadeDescricao").val("");
			$('#codUnidade').val(response.data.codigo);
		})
		.catch(function (error) {
			alert("Ocorreu um erro. Recarregue a pagina e tente de novo.");
		});
	}
});

$(window).resize(function(){
	if ($(window).width() < 768) {
	    // do something for small screens
	    $("#produtos").addClass("table-responsive");
	}
	else if ($(window).width() >= 768 &&  $(window).width() <= 992) {
	    // do something for medium screens
	    $("#produtos").addClass("table-responsive");
	}
	else if ($(window).width() > 992 &&  $(window).width() <= 1200) {
	    // do something for big screens
	}
	else  {
	    // do something for huge screens
	}
});


function confirmSelectLocalAgain() {
	if(confirm("Selecionar Novamente o Local irá deletar toda sua Cesta! Você deseja continuar?"))
		location.href="/selectItensAgain";
}

function mudaEstiloCardCheckbox(cb) {
	if ( $(cb).is(":checked") ) {
		$(cb).parent().removeClass("bg-white");
		$(cb).parent().addClass("bg-seen");
	} else{
		$(cb).parent().removeClass("bg-seen");
		$(cb).parent().addClass("bg-white");
	}
}

function selectOnlyIfNotSelected(codigo){
	if(!$('#'+codigo).is(":checked")){
		$('#'+codigo).prop('checked', true);
		$('#'+codigo).parent().removeClass("bg-white");
		$('#'+codigo).parent().addClass("bg-seen");
	}
}

function showDetails(object) {
	let p = document.querySelector('#modalInfoParagraph');
	for (let prop in object) {
		if (object.hasOwnProperty(prop) && object[prop]) {
			p.innerHTML+= '<b>' + prop + '</b> : ' + object[prop] + '<br>';
		}
	}
	$('#myModal').modal();
}

function closeModalDetails() {
	document.querySelector('#modalInfoParagraph').innerHTML='';
}

function orderBy(type, $event) {
	const sortIconClass = $event.children[0].classList[1];
	let orderDirection = '';
	
	if (sortIconClass === 'fa-sort') {
		$event.children[0].classList = 'fas fa-sort-down';
		orderDirection = 'desc';
	}

	if (sortIconClass === 'fa-sort-down') {
		$event.children[0].classList = 'fas fa-sort-up';
		orderDirection = 'asc';
	}

	if (sortIconClass === 'fa-sort-up') {
		$event.children[0].classList = 'fas fa-sort';
		type = undefined;
		orderDirection = undefined;
	}

	let url = new URL(location.href);
	if (!!type && !!orderDirection) {
		url.searchParams.set('sortBy', type);
		url.searchParams.set('sortDirection', orderDirection);
	} else {
		url.searchParams.delete('sortBy');
		url.searchParams.delete('sortDirection');
	}

	location.href = url.href;
}

function search(e) {
	const url = new URL(location.href);

	if (!e) {
		document.getElementById('search').value = '';
		url.searchParams.delete('search');
		url.searchParams.delete('sortBy');
		url.searchParams.delete('sortDirection');
		location.href = url.href;
	} else if (e.keyCode === 13) {
		if (!!document.getElementById('search').value) {
			url.searchParams.set('search', document.getElementById('search').value);
		} else {
			url.searchParams.delete('search');
		
		}
		location.href = url.href;
	}
}

function searchForProducts(e) {
	if (e.keyCode === 13) {
		const url = new URL(location.href);
		url.searchParams.set('search',  $('#searchProducts').val());
		url.searchParams.set('grupos', $('#grupos').val());

		location.href = url.href;
	}
}
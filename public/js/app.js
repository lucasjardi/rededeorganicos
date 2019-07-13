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
	    $("#produtos").removeClass("table-responsive");
	}
	else  {
	    // do something for huge screens
	    $("#produtos").removeClass("table-responsive");
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
$(function () {
	$('#produtos').DataTable();
	$('[data-toggle="tooltip"]').tooltip();
	$('#exampleModal').modal('show');

	$('#valor').mask('#.##0,00', {reverse: true});
	$('#acrescimo').mask('#.##0,00', {reverse: true});

	$("#nomeCliente" ).autocomplete({
		source: "/clientes",
		select: function( event, ui ) {
			// console.log("Selected: " + ui.item.id);
			$("#codCliente").val(ui.item.id);
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
		axios.post('/grupos', {
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
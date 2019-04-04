$(function () {
	$('#produtos').DataTable();
	$('[data-toggle="tooltip"]').tooltip();
	$('#exampleModal').modal('show');
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
// ! Your application

$(document).ready(function() {
	$('input[type=date]').datepicker();
	$('input.date').datepicker();

	setupTooltip();
});

function setupTooltip() {
	$('.tooltip').tooltip({ position: { my: "center top+15", at: "center" } });
}

function showLoadingScreen()	{
	$('#loadingnew-overlay').show();
	$('#loadingnew').show();
}

function hideLoadingScreen()	{
	$('#loadingnew-overlay').fadeOut(500);
	$('#loadingnew').fadeOut(500);
}

function showErrorMessage(message) {
	$(".alert.success").hide();
	$(".alert.warning").hide();
	$(".alert.error").show();
	
	$(".alert.error .message").html(message);
}

function showWarningMessage(message) {
	$(".alert.success").hide();
	$(".alert.warning").show();
	$(".alert.error").hide();
	
	$(".alert.warning .message").html(message);
}

function showSuccessMessage(message) {
	$(".alert.success").show();
	$(".alert.warning").hide();
	$(".alert.error").hide();
	
	$(".alert.success .message").html(message);
}

$.fn.table = function(opts) {
	var defaultOptions = {
        "bPaginate": true,
        "bLengthChange": true,
        "bFilter": true,
        "bSort": true,
        "bInfo": true,
        "bAutoWidth": true,
        "iDisplayLength": 20,
		"sDom": 't<"footer"ip>',
		"sPaginationType": 'full_numbers'
	};
	
	if(opts == 'undefined') {
		opts = {};
	}
	
	var useOpts = $.extend({},defaultOptions,opts);
	
	$(this).dataTable(useOpts);
}
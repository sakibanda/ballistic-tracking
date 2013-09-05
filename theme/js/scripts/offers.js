function initDataTables(){
    /*
    $('#offers_table').table({
        "aoColumns": [
            null,
            null,
            null,
            null,
            { "bSortable": false },
            { "bSortable": false }
        ]
    });
    */
}

$(function() {
	"use strict";

    refreshOffersTable();

    $("#add_offer_form_holder").dialog({
        autoOpen: false,
        modal: true,
        width: 800,
        resizable: false,
        open: function(){ $(this).parent().css('overflow', 'visible'); }
    }).find('button.cancel').click(function(){
        var $el = $(this).parents('.ui-dialog-content');
        $el.find('form')[0].reset();
        $el.dialog('close');
    }).end().find('button.submit').click(function(){
        if($("#add_offer_form").valid()){
            $.post('/ajax/offers/save',$('#add_offer_form').serialize(true),
                function(data) {
                    if(data === "success") {
                        $(".alert.success .message").text("Offer added");
                        $(".alert.success").show();
                        $(".alert.error").hide();
                    }
                    else {
                        $(".alert.error .message").text("An error occurred while adding your redirect url");
                        $(".alert.error").show();
                        $(".alert.success").hide();
                    }
                    refreshOffersTable();
                }
            );
            var $el = $(this).parents('.ui-dialog-content');
            $el.find('form')[0].reset();
            $el.dialog('close');
        }
    });

    $("#add_offer_btn").click(function(e){
        e.preventDefault();
        $("#add_offer_form_holder").dialog("open");
        return false;
    });
});

function refreshOffersTable(){
	var sendData = null;
	var network = getURLParameter("network");
	if (network!="undefined"){ //filter by network
		sendData = {network: network};
	}

	$.get('/offers/list',sendData,function(data){
		$("#offers_table tbody").html(data);
        $(".delete_offer").click(function(e) {
			e.preventDefault();
			var id = $(this).prop('rel');
			if(confirm("Are you sure you want to delete this offer?")) {
				$.post('/offers/delete',{offer_id:id},function(data) {
                    $(".alert.success .message").text("Redirect Deleted");
                    $(".alert.success").show();
                    $(".alert.error").hide();
                    refreshOffersTable();
				});
			}
		});
	});
}
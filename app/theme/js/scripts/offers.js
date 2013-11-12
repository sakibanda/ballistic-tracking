$(function() {
	"use strict";

    var network = getURLParameter("network");
    if (network=="undefined"){ //filter by network
        network='';
    }

    var aryColTableChecked = ["Offer ID", "Network", "Offer Name", "Payout", "URL", "Actions"];
    var aryJSONColTable = [];

    for (var i=0; i < aryColTableChecked.length; i++ ) {
        aryJSONColTable.push({
            "sTitle": aryColTableChecked[i],
            "aTargets": [i]
        });
    };

    var oTable = $("#offers_table").dataTable({
        "aoColumnDefs": aryJSONColTable,
        "bServerSide": true, //only ajax
        "sAjaxSource": '../ajax/offers/list?network='+network,
        "bDeferRender": true, //will only create the nodes required for each individual display
        "aoColumns": [
            {"sClass":"center","bSearchable": false,"sType": "numeric"},
            {"sClass":"center","sType": "string"},
            {"sType": "string"},
            {"sClass":"center","sType": "numeric"},
            {"bSortable": false,"sClass":"center","bSearchable": false},
            {"bSortable": false,"bSearchable": false}
        ],
        fnDrawCallback: function() {
            $(".delete_offer").click(function(e) {
                e.preventDefault();
                var id = $(this).prop('rel');
                if(confirm("Are you sure you want to delete this offer?")) {
                    $.post('/offers/delete',{offer_id:id},function(data) {
                        $(".alert.success .message").text("Redirect Deleted");
                        $(".alert.success").show();
                        $(".alert.error").hide();
                        // Example call to reload from original file
                        oTable.fnReloadAjax();
                    });
                }
            });
        },
        "sDom": 'T<"clear"><lf>rtip',
        "oTableTools": {
            "sSwfPath": "/theme/swf/copy_csv_xls_pdf.swf"
        }
    });

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
                        // Example call to reload from original file
                        oTable.fnReloadAjax();
                    }
                    else {
                        $(".alert.error .message").text("An error occurred while adding your redirect url");
                        $(".alert.error").show();
                        $(".alert.success").hide();
                    }
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
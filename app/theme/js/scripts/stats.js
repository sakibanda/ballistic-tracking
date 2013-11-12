$(function() {
    "use strict";

    $('body').addClass('flex_width');

    $.extend($.fn.dataTable.defaults, {
        "bFilter": false,
        "bLengthChange": false,
        "bInfo": false,
        "bPaginate": false,
        "bDeferRender": true,
        "sDom": '<"top"lT>rt<"footer"ip><"clear">'
    });

    var camp_id = $("#camp_id").val();
    $("#campaign_overview").dataTable({
        "sAjaxSource": '/ajax/stats/campaignData?campaign_id='+camp_id,
        "aaSorting": [[ 4, "desc" ]],
        "aoColumns": [
            {"sWidth": "20%"},
            { "sClass":"center","sType": "numeric" },
            //{ "sClass":"center","sType": "numeric" },
            { "sClass":"center","sType": "numeric" },
            { "sClass":"center","sType": "numeric" },
            { "sClass":"center","sType": "numeric" },
            { "sClass":"center","sType": "numeric" },
            { "sClass":"center","sType": "numeric" },
            { "sClass":"center","sType": "numeric" },
            { "sClass":"center","sType": "numeric" },
            { "sClass":"center","sType": "numeric" },
            { "sClass":"center","sType": "numeric" },
            { "sClass":"center","sType": "numeric" },
            { "sClass":"center","sType": "numeric" }
        ]
    });

    $("#offer_overview").dataTable({
        "sAjaxSource": '/ajax/stats/offerData?campaign_id='+camp_id,
        "aaSorting": [[ 4, "desc" ]],
        "aoColumns": [
            {"sWidth": "20%"},
            { "sClass":"center","sType": "numeric" },
            { "sClass":"center","sType": "numeric" },
            { "sClass":"center","sType": "numeric" },
            { "sClass":"center","sType": "numeric" },
            { "sClass":"center","sType": "numeric" },
            { "sClass":"center","sType": "numeric" },
            { "sClass":"center","sType": "numeric" },
            { "sClass":"center","sType": "numeric" },
            { "sClass":"center","sType": "numeric" },
            { "sClass":"center","sType": "numeric" },
            { "sClass":"center","sType": "numeric" },
            { "sClass":"center","sType": "numeric" }
        ],
        "oTableTools": {
            "sSwfPath": "/theme/swf/copy_csv_xls_pdf.swf"
        }
    });

    $("#lp_overview").dataTable({
        "sAjaxSource": '/ajax/stats/lpData?campaign_id='+camp_id,
        "aaSorting": [[ 4, "desc" ]],
        "aoColumns": [
            {"sWidth": "20%"},
            { "sClass":"center","sType": "numeric" },
            //{ "sClass":"center","sType": "numeric" },
            { "sClass":"center","sType": "numeric" },
            { "sClass":"center","sType": "numeric" },
            { "sClass":"center","sType": "numeric" },
            { "sClass":"center","sType": "numeric" },
            { "sClass":"center","sType": "numeric" },
            { "sClass":"center","sType": "numeric" },
            { "sClass":"center","sType": "numeric" },
            { "sClass":"center","sType": "numeric" },
            { "sClass":"center","sType": "numeric" },
            { "sClass":"center","sType": "numeric" },
            { "sClass":"center","sType": "numeric" }
        ],
        "oTableTools": {
            "sSwfPath": "/theme/swf/copy_csv_xls_pdf.swf"
        }
    });

    $("#subid_overview").dataTable({
        "sAjaxSource": '/ajax/stats/subidData?campaign_id='+camp_id,
        "bInfo": true,
        "aaSorting": [[ 6, "desc" ]],
        "iDisplayLength": 10,
        "bPaginate": true,
        "bLengthChange": true,
        "bServerSide": true, //only ajax
        "sServerMethod": "POST",
        "aoColumns": [
            { "sClass":"center"},
            { "sClass":"center"},
            { "sClass":"center"},
            { "sClass":"center"},
            { "sClass":"center"},
            { "sClass":"center"},
            { "sClass":"center"},
            { "sClass":"center"},
            { "sClass":"center"},
            { "sClass":"center"},
            { "sClass":"center"}
        ],
        "oTableTools": {
            "sSwfPath": "/theme/swf/copy_csv_xls_pdf.swf"
        }
    });

});
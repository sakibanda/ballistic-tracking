$(function() {
    "use strict";

    $('body').addClass('flex_width');

    $.extend($.fn.dataTable.defaults, {
        "bFilter": false,
        "bSort": false,
        "bLengthChange": false,
        "bInfo": false,
        "bPaginate": false,
        "bDeferRender": true
    });

    var camp_id = $("#camp_id").val();
    $("#campaign_overview").dataTable({
        "sAjaxSource": '/ajax/stats/campaignData?campaign_id='+camp_id,
        "aoColumns": [
            {},
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
            { "sClass":"center","sType": "numeric" },
            { "sClass":"center","sType": "numeric" }
        ]
    });

    $("#offer_overview").dataTable({
        "sAjaxSource": '/ajax/stats/offerData?campaign_id='+camp_id,
        "aoColumns": [
            {},
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

    $("#lp_overview").dataTable({
        "sAjaxSource": '/ajax/stats/lpData?campaign_id='+camp_id,
        "aoColumns": [
            {},
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
            { "sClass":"center","sType": "numeric" },
            { "sClass":"center","sType": "numeric" }
        ]
    });

    $("#subid_overview").dataTable({
        "sAjaxSource": '/ajax/stats/subidData?campaign_id='+camp_id,
        "aoColumns": [
            {},
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

});
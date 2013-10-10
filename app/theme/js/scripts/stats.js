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

    $("#campaign_overview").dataTable({
        "sAjaxSource": '/ajax/stats/campaignData',
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
        "sAjaxSource": '/ajax/stats/offerData',
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
        "sAjaxSource": '/ajax/stats/lpData',
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

});
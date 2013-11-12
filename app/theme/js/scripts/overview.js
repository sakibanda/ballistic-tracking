$(function() {
    "use strict";

    $('body').addClass('flex_width');

    $("#overview_table").dataTable({
        aaSorting: [[0,'desc']],
        bServerSide: true,
        bSearchable: false,
        bFilter: false,
        "bPaginate": false,
        iDisplayLength: 10,
        sAjaxSource: "/ajax/overview/dataOverview",
        aoColumns: [
            { "bSortable": true,"sClass":"center","sType": "numeric" }, //id
            { "bSortable": true,"sType": "string" }, //name
            { "bSortable": true,"sClass":"center","sType": "string", "sWidth": "5.5%"}, //type
            { "bSortable": true,"sClass":"center" }, //clicks
            { "bSortable": true,"sClass":"center", "sWidth": "6%" }, //leads
            { "bSortable": true,"sClass":"center", "sWidth": "7%", "sTitle":"LP CVR" }, //Conv %
            //{ "bSortable": true,"sClass":"center" }, //payout
            { "bSortable": true,"sClass":"center", "sWidth": "5.5%" }, //epc
            { "bSortable": true,"sClass":"center", "sWidth": "5.5%" }, //cpc
            { "bSortable": true,"sClass":"center", "sWidth": "6.5%" }, //income
            { "bSortable": true,"sClass":"center" }, //cost
            { "bSortable": true,"sClass":"center" }, //net
            { "bSortable": true,"sClass":"center" }, //roi
            { "bSortable": false } //actions
        ],
        "sDom": '<"top"T>rt<"footer"i><"clear">',
        "oTableTools": {
            "sSwfPath": "/theme/swf/copy_csv_xls_pdf.swf",
            "aButtons": [
                "copy",
                "csv",
                "xls",
                {
                    "sExtends": "pdf",
                    "sPdfOrientation": "landscape",
                    "sPdfMessage": "Your custom message would go here."
                },
                "print"
            ]
        }
    });
});

function exportCsv() {
    var oTable = $('#overview_table').dataTable();
    var oSettings = oTable.fnSettings();

    var sort_col = oSettings.aaSorting[0][0];
    var sort_dir = oSettings.aaSorting[0][1];

    iframe = document.createElement('iframe');
    iframe.style.display = 'none';
    document.body.appendChild(iframe);
    iframe.src = '/overview/exportOverview?iSortCol_0=0&sSortDir_0=desc';
}

function delete_campaign(campaign_id) {
    if(confirm("Are you sure you want to delete campaign ID " + campaign_id + "?")) {
        $.post("/overview/deleteCampaign",{"campaign_id":campaign_id},function(data) {
            loadContent("/overview/viewOverview");
        });
    }
    return false;
}

function clone_campaign(campaign_id){
    if(confirm("Are you sure you want to duplicate this campaign?")) {
        $.post('/overview/copyCampaign',{campaign_id:campaign_id},function() {
            loadContent("/overview/viewOverview");
        });
    }
    return false;
}
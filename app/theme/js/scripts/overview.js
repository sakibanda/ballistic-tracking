$(function() {
    "use strict";

    $('body').addClass('flex_width');

    $("#overview_table").dataTable({
        //aaSorting: [[0,'desc']],
        bServerSide: true,
        bSearchable: false,
        bFilter: false,
        "bPaginate": false,
        iDisplayLength: 10,
        sAjaxSource: "/ajax/overview/dataOverview",
        aoColumns: [
            { "bSortable": true, "sClass":"center" }, //id
            { "bSortable": true }, //name
            { "bSortable": true,"sClass":"center" }, //type
            { "bSortable": false,"sClass":"center" }, //clicks
            { "bSortable": false,"sClass":"center" }, //leads
            { "bSortable": false,"sClass":"center" }, //Conv %
            { "bSortable": false,"sClass":"center" }, //payout
            { "bSortable": false,"sClass":"center" }, //epc
            { "bSortable": false,"sClass":"center" }, //cpc
            { "bSortable": false,"sClass":"center" }, //income
            { "bSortable": false,"sClass":"center" }, //cost
            { "bSortable": false,"sClass":"center" }, //net
            { "bSortable": false,"sClass":"center" }, //roi
            { "bSortable": false } //actions
        ]
    });

    function exportCsv() {
        var oTable = $('#overview_table').dataTable();
        var oSettings = oTable.fnSettings();

        var sort_col = oSettings.aaSorting[0][0];
        var sort_dir = oSettings.aaSorting[0][1];

        iframe = document.createElement('iframe');
        iframe.style.display = 'none';
        document.body.appendChild(iframe);
        iframe.src = '/overview/exportOverview?iSortCol_0=0&sSortDir_0=asc';
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
});
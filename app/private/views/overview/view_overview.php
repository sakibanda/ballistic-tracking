<div class="grid_12">
	<div class="box with-table">
		<div class="header">
			<h2>Campaign Overview</h2>
		</div>
		
		<div class="content">
			<div class="tabletools">
				<div class="left">
					
				</div>
				
				<div class="right">
					<a href="#" onclick="exportCsv(); return false;">CSV</a>
				</div>
			</div>
			
			<table class="dataTable" id="overview_table" cellpadding="0" cellspacing="0">
				<thead>
					<tr>
						<th>ID</th>
						<th>Campaign</th>
						<th>Type</th>
						<th>Clicks</th> 
						<th>Leads</th>
						<th>Conv</th>
						<th>Payout</th>
						<th>EPC</th>
						<th>CPC</th>
						<th>Income</th>
						<th>Cost</th>
						<th>Net</th>
						<th>ROI</th>
						<th style="width: 200px;">Actions</th>
					</tr>
				</thead>
				
				<tfoot>					
					<tr>
						<th>ID</th>
						<th>Campaign</th>
						<th>Type</th>
						<th>Clicks</th> 
						<th>Leads</th>
						<th>Conv</th>
						<th>Payout</th>
						<th>EPC</th>
						<th>CPC</th>
						<th>Income</th>
						<th>Cost</th>
						<th>Net</th>
						<th>ROI</th>
						<th>Actions</th>
					</tr>
				</tfoot>
			</table>
		</div>
	</div>
</div>

<script type="text/javascript">
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

</script>
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
			
			<table class="styled" id="overview_table" cellpadding="0" cellspacing="0">
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
						<th style="width: 150px;">Actions</th>
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
	$("#overview_table").table({
		aaSorting: [[3,'desc']],
		bServerSide: true,
		bSearchable: false,
		bFilter: false,
		iDisplayLength: 50000, 
		sAjaxSource: "/ajax/overview/dataOverview",
		fnInitComplete: function() {
			$(".dataTables_length").hide();
			$(".dataTables_processing").hide();
			$(".dataTables_wrapper .footer").hide();
		},
		fnDrawCallback: function() {			
			$("#overview_table tbody td:nth-child(12)").each(function() {
				colorizeReportTd($(this));
			});
			
			$("#overview_table tbody td:nth-child(13)").each(function() {
				colorizeReportTd($(this));
			});
		},
		aoColumns: [
			{ "bSortable": true }, //id
			{ "bSortable": false }, //name
			{ "bSortable": false }, //type
			{ "bSortable": false }, //clicks
			{ "bSortable": false }, //leads
			{ "bSortable": false }, //Conv %
			{ "bSortable": false }, //payout
			{ "bSortable": false }, //epc
			{ "bSortable": false }, //cpc
			{ "bSortable": false }, //income
			{ "bSortable": false }, //cost
			{ "bSortable": false }, //net
			{ "bSortable": false }, //roi
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
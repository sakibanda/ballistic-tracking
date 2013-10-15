<div class="grid_12">
	<div class="box with-table">
		<div class="header">
			<h2>City Breakdown</h2>
		</div>
		
		<div class="content">
			
		<div class="tabletools">
			<div class="left">
				
			</div>
			
			<div class="right">
				<a href="#" onclick="exportCsv(); return false;">CSV</a>
			</div>
		</div>
			
	<table id="timezone_table" class="timezone dataTable" class="styled" cellpadding="0" cellspacing="0">
		<thead>
		<tr>   
			<th>Country</th>
			<th>State</th>
			<th>City</th>
			<th>Clicks</th>
			<th>Click Throughs</th>
			<th>CTR</th>
			<th>Leads</th>
			<th>Conv %</th>
			<th>Payout</th>
			<th>EPC</th>
			<th>Income</th>
		</tr>  
		</thead>
	</table>  </div></div>
</div>

<script type="text/javascript">


	$("#timezone_table").table({
			bServerSide: true,
			bSearchable: false,
			bFilter: false,
			aaSorting: [[3,'desc']],
			iDisplayLength: 20,
			sAjaxSource: "/ajax/geography/dataCities",
			fnInitComplete: function() {
				$(".dataTables_length").hide();
				$(".dataTables_processing").hide();
			},
			aoColumns: [
				{ "bSortable": true }, //Country
				{ "bSortable": true }, //State
				{ "bSortable": true }, //City
				{ "bSortable": true }, //Clicks
				{ "bSortable": true }, //Click Throughs
				{ "bSortable": true }, //CTR
				{ "bSortable": true }, //Leads
				{ "bSortable": true }, //Conv
				{ "bSortable": true }, //Payout
				{ "bSortable": true }, //EPC
				{ "bSortable": true } //Income
			]
	});
	
	function exportCsv() {
		var oTable = $('#timezone_table').dataTable();
		var oSettings = oTable.fnSettings();
		
		var sort_col = oSettings.aaSorting[0][0];
		var sort_dir = oSettings.aaSorting[0][1];
		
		iframe = document.createElement('iframe');
        iframe.style.display = 'none';
        document.body.appendChild(iframe);
		iframe.src = '/geography/exportCities?iSortCol_0=' + sort_col + '&sSortDir_0=' + sort_dir;
	}
</script>
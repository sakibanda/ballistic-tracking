<div class="grid_12">
	<div class="box with-table">
		<div class="header">
			<h2>Mobile Breakdown</h2>
		</div>
		
		<div class="content">
			
		<div class="tabletools">
			<div class="left">
				
			</div>
			
			<div class="right">
				<a href="#" onclick="exportCsv(); return false;">CSV</a>
			</div>
		</div>
			
	<table id="variables_table" class="variables dataTable" cellpadding="0" cellspacing="0">
		<thead>
		<tr>   
			<th>Breakdown</th>
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


	$("#variables_table").table({
		bServerSide: true,
		bSearchable: false,
		bFilter: false,
		aaSorting: [[1,'desc']],
		iDisplayLength: 50,
		sAjaxSource: "/ajax/platforms/datamobile",
		fnInitComplete: function() {
			$(".dataTables_length").hide();
			$(".dataTables_processing").hide();
		},
		aoColumns: [
			{ "bSortable": false }, //Breakdown
			{ "bSortable": false }, //Clicks
			{ "bSortable": false }, //Click Throughs
			{ "bSortable": false }, //CTR
			{ "bSortable": false }, //Leads
			{ "bSortable": false }, //Conv
			{ "bSortable": false }, //Payout
			{ "bSortable": false }, //EPC
			{ "bSortable": false }, //Income
		],
		 fnRowCallback: function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
			$(nRow).addClass(aData[9]);
			return nRow;
		}
	});
	
function exportCsv() {
	var oTable = $('#variables_table').dataTable();
	var oSettings = oTable.fnSettings();
	
	var sort_col = oSettings.aaSorting[0][0];
	var sort_dir = oSettings.aaSorting[0][1];
	
	iframe = document.createElement('iframe');
	iframe.style.display = 'none';
	document.body.appendChild(iframe);
	iframe.src = '/platforms/exportMobile?iSortCol_0=' + sort_col + '&sSortDir_0=' + sort_dir;
}
</script>
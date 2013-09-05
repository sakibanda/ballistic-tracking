<div class="grid_12">
	<div class="box with-table">
		<div class="header">
			<h2>Analyze IPs</h2>
		</div>
		
		<div class="content">
			
			<div class="tabletools">
			<div class="left">
				
			</div>
			
			<div class="right">
				<a href="#" onclick="exportCsv(); return false;">CSV</a>
			</div>
		</div>
			
	<table id="ips_table" class="styled" cellpadding="0" cellspacing="0">
		<thead>
		<tr>   
			<th class="sortable">IP Address</th>
			<th class="sortable">Clicks</th> 
			<th class="sortable">Leads</th>
			<th class="sortable">Conv %</th>
			<th class="sortable">Payout</th>
			<th class="sortable">EPC</th> 
			<th class="sortable">Income</th>
		</tr>  
		</thead>
	</table></div></div> 
</div>

<script type="text/javascript">
	$("#ips_table").table({dataTable: {
		aaSorting: [[1,'desc']],
		bServerSide: true,
		bSearchable: false,
		bFilter: false,
		iDisplayLength: 50,
		sAjaxSource: "/ajax/analyze/dataips",
		fnInitComplete: function() {
			$(".dataTables_length").hide();
			$(".dataTables_processing").hide();
		},
		aoColumns: [
			{ "bSortable": true }, //ip
			{ "bSortable": true }, //clicks
			{ "bSortable": true }, //leads
			{ "bSortable": true }, //Conv %
			{ "bSortable": true }, //payout
			{ "bSortable": true }, //epc
			{ "bSortable": true } //income
		]
	}
	});
	
	function exportCsv() {
		var oTable = $('#ips_table').dataTable();
		var oSettings = oTable.fnSettings();
		
		var sort_col = oSettings.aaSorting[0][0];
		var sort_dir = oSettings.aaSorting[0][1];
		
		iframe = document.createElement('iframe');
        iframe.style.display = 'none';
        document.body.appendChild(iframe);
		iframe.src = '/analyze/exportIPs?iSortCol_0=' + sort_col + '&sSortDir_0=' + sort_dir;
	}
</script>
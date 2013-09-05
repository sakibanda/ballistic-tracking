<div class="grid_12">
	<div class="box with-table">
		<div class="header">
			<h2>Analyze LPs</h2>
		</div>
		
		<div class="content">
			
		<div class="tabletools">
			<div class="left">
				
			</div>
			
			<div class="right">
				<a href="#" onclick="exportCsv(); return false;">CSV</a>
			</div>
		</div>

	<table id="landing_page_table" class="styled" cellpadding="0" cellspacing="0">
		<thead>
		<tr>   
			<th class="sortable">Landing Page</th>
			<th class="sortable">Clicks</th> 
			<th class="sortable">Click Throughs</th> 
			<th class="sortable">CTR</th> 
			<th class="sortable">Leads</th>
			<th class="sortable">Conv %</th>
			<th class="sortable">Payout</th>
			<th class="sortable">EPC</th> 
			<th class="sortable">Income</th>
		</tr>   
	</thead>
	</table>
		</div></div>
</div>

<script type="text/javascript">
	$("#landing_page_table").table({dataTable: {
		bServerSide: true,
		bSearchable: false,
		bFilter: false,
		aaSorting: [[1,'desc']],
		iDisplayLength: 50,
		sAjaxSource: "/ajax/analyze/datalps",
		fnInitComplete: function() {
			$(".dataTables_length").hide();
			$(".dataTables_processing").hide();
		},
		aoColumns: [
			{ "bSortable": true }, //landing page
			{ "bSortable": true }, //clicks
			{ "bSortable": true }, //click throughs
			{ "bSortable": true }, //ctr
			{ "bSortable": true }, //leads
			{ "bSortable": true }, //Conv %
			{ "bSortable": true }, //payout
			{ "bSortable": true }, //epc
			{ "bSortable": true } //income
		]
	}
	});
	
	function exportCsv() {
		var oTable = $('#landing_page_table').dataTable();
		var oSettings = oTable.fnSettings();
		
		var sort_col = oSettings.aaSorting[0][0];
		var sort_dir = oSettings.aaSorting[0][1];
		
		iframe = document.createElement('iframe');
        iframe.style.display = 'none';
        document.body.appendChild(iframe);
		iframe.src = '/analyze/exportLps?iSortCol_0=' + sort_col + '&sSortDir_0=' + sort_dir;
	}
</script>
<div class="grid_12">
	<div class="box with-table">
		<div class="header">
			<h2>Analyze Variables</h2>
		</div>
		
		<div class="content">
		
		<div class="tabletools">
			<div class="left">
				
			</div>
			
			<div class="right">
				<a href="#" onclick="exportCsv(); return false;">CSV</a>
			</div>
		</div>
			
			<table id="variables_table" class="dataTable" cellpadding="0" cellspacing="0">
				<thead>
				<tr>   
					<th class="sortable">Subid1</th>
					<th class="sortable">Subid2</th>
					<th class="sortable">Subid3</th>
					<th class="sortable">Subid4</th>
					<th class="sortable">Clicks</th>
					<th class="sortable">Click Throughs</th>
					<th class="sortable">Leads</th>
					<th class="sortable">Conv %</th>
					<th class="sortable">Payout</th>
					<th class="sortable">EPC</th>
					<th class="sortable">Income</th>
				</tr>  
				</thead>
			</table> 
		</div>
	</div>
</div>

<script type="text/javascript">			
	$("#variables_table").table({
		dataTable: {
			bServerSide: true,
			aaSorting: [[4,'desc']],
			iDisplayLength: 20,
			bSearchable: false,
			bFilter: false,
			sAjaxSource: "/ajax/analyze/datavariables",
			fnInitComplete: function() {			
				$(".dataTables_length").hide();
				$(".dataTables_processing").hide();
			},
			aoColumns: [
				{ "bSortable": true }, //v1
				{ "bSortable": true }, //v2
				{ "bSortable": true }, //v3
				{ "bSortable": true }, //v4
				{ "bSortable": true }, //Clicks
				{ "bSortable": true }, //Click Throughs
				{ "bSortable": true }, //Leads
				{ "bSortable": true }, //Conv
				{ "bSortable": true }, //Payout
				{ "bSortable": true }, //EPC
				{ "bSortable": true } //Income
			]
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
		iframe.src = '/analyze/exportVariables?iSortCol_0=' + sort_col + '&sSortDir_0=' + sort_dir;
	}
</script>
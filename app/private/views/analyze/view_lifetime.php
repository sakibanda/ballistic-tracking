<div class="grid_12">
	<div class="box with-table">
		<div class="header">
			<h2>Analyze Click Lifetime</h2>
		</div>
		
		<div class="content">
			
			<div class="tabletools">
			<div class="left">
				
			</div>
			
			<div class="right">
				<a href="#" onclick="exportCsv(); return false;">CSV</a>
			</div>
		</div>
			
	<table id="keywords_table" class="dataTable" cellpadding="0" cellspacing="0">
		<thead>
		<tr>   
			<th class="sortable">Lifetime</th>
			<th class="sortable">Leads</th>
			<th class="sortable">Payout</th>
			<th class="sortable">Income</th>
		</tr>  
		</thead>
	</table>  </div></div>
</div>

<script type="text/javascript">
	$("#keywords_table").table({
		dataTable: {
			bServerSide: true,
			bSearchable: false,
			bFilter: false,
			aaSorting: [[0,'asc']],
			iDisplayLength: 50,
			sAjaxSource: "/ajax/analyze/datalifetime",
			fnInitComplete: function() {
				$(".dataTables_length").hide();
				$(".dataTables_processing").hide();
			},
			aoColumns: [
				{ "bSortable": true }, //lifetime
				{ "bSortable": true }, //leads
				{ "bSortable": true }, //payout
				{ "bSortable": true } //income
			]
		}
	});
	
	function exportCsv() {
		var oTable = $('#keywords_table').dataTable();
		var oSettings = oTable.fnSettings();
		
		var sort_col = oSettings.aaSorting[0][0];
		var sort_dir = oSettings.aaSorting[0][1];
		
		iframe = document.createElement('iframe');
        iframe.style.display = 'none';
        document.body.appendChild(iframe);
		iframe.src = '/analyze/exportLifetime?iSortCol_0=' + sort_col + '&sSortDir_0=' + sort_dir;
	}
</script>
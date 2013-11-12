<div class="grid_12">
	<div class="box with-table">
		<div class="header">
			<h2>Week Parting</h2>
		</div>
		
		<div class="content">
			
	<table id="weekparting_table" class="dataTable" cellpadding="0" cellspacing="0">
		<thead>
		<tr>   
			<th class="sortable">Day</th>
			<th class="sortable">Clicks</th> 
			<th class="sortable">Leads</th>
			<th class="sortable">Conv %</th>
			<th class="sortable">Payout</th>
			<th class="sortable">EPC</th>
			<th class="sortable">Avg CPC</th>
			<th class="sortable">Income</th>
			<th class="sortable">Cost</th>
			<th class="sortable">Net</th>
			<th class="sortable">ROI</th>
		</tr>  
		</thead>
	</table></div></div> 
</div>

<script type="text/javascript">
	$("#weekparting_table").dataTable({
		//sDom: '<"filters"fl>rt',
		bProcessing: false,
		aaSorting: [[0,'asc']],
		sPaginationType: 'full_numbers',
		bServerSide: true,
		bSearchable: false,
		bFilter: false,
		iDisplayLength: 50,
		sAjaxSource: "/ajax/dateparting/dataWeek",
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
			{ "bSortable": true }, //cpc
			{ "bSortable": true }, //income
			{ "bSortable": true }, //cost
			{ "bSortable": true }, //net
			{ "bSortable": true } //roi
        ],
        "sDom": 'T<"clear"><lf></lf>rt',
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
</script>

<style type="text/css">
#weekparting_table tbody tr:last-child td{
	background-color: #EEF0F4;
	font-weight: bold;
}
</style>

<div class="grid_12">
	<div class="box with-table">
		<div class="header">
			<h2>Day Parting</h2>
		</div>
		
		<div class="content">
			
	<table id="dayparting_table" class="dataTable" cellpadding="0" cellspacing="0">
		<thead>
		<tr>	 
			<th class="sortable">Hour</th>
			<th class="sortable">Clicks</th> 
			<th class="sortable">Leads</th>
			<th class="sortable">Conv %</th>
			<th class="sortable">Payout</th>
			<th class="sortable">EPC</th>
			<th class="sortable">Income</th>
			<th class="sortable">Cost</th>
			<th class="sortable">Net</th>
			<th class="sortable">ROI</th>
		</tr>	 
		</thead>
		
		<tfoot>
		<tr>	 
			<th class="sortable">Hour</th>
			<th class="sortable">Clicks</th> 
			<th class="sortable">Leads</th>
			<th class="sortable">Conv %</th>
			<th class="sortable">Payout</th>
			<th class="sortable">EPC</th>
			<th class="sortable">Income</th>
			<th class="sortable">Cost</th>
			<th class="sortable">Net</th>
			<th class="sortable">ROI</th>
		</tr>	 
		</tfoot>
		
		<tbody>
			
		</tbody>
	</table></div></div> 
</div>

<script type="text/javascript">
	$("#dayparting_table").dataTable({
		//sDom: '<"filters"fl>rt',
		bProcessing: false,
		aaSorting: [[0,'asc']],
		sPaginationType: 'full_numbers',
		bServerSide: true,
		bSearchable: false,
		bFilter: false,
		iDisplayLength: 50,
		sAjaxSource: "/ajax/dateparting/dataDay",
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
			{ "bSortable": true }, //income
			{ "bSortable": true }, //cost
			{ "bSortable": true }, //net
			{ "bSortable": true } //roi
        ],
        "sDom": 'T<"clear"><lf></lf>rt',
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
</script>

<style type="text/css">
#dayparting_table tbody tr:last-child td{
	background-color: #EEF0F4;
	font-weight: bold;
}
</style>
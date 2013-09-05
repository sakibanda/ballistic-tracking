<?php $this->menu();  ?>

<h1 class="grid_12"><span>View Syslog</span></h1>

<div class="grid_12">
	<div class="box with-table">
		<div class="header"><h2>Syslog</h2></div>
	
		<div class="content">
			<div class="tabletools">
				<div class="left">
					<div style="float:left;text-align:left;width:170px">
						<select name="filtertype" id="filtertype" onchange="$('#syslog_table').dataTable().fnDraw();">
							<option value="all">Show All Processes</option>
							
							<?php
							
							foreach($syslog_types as $type) {
								echo '<option value="' . $type->type . '">' . ucfirst($type->type) . '</option>';
							}
							?>
						</select>
					</div>
					<div style="float:left;text-align:left;width:150px">
						<select name="filterlevel" id="filterlevel" onchange="$('#syslog_table').dataTable().fnDraw();">
							<option value="all">Show All Levels</option>
							<option value="1">Message</option>
							<option value="2">Warning</option>
							<option value="3">Error</option>
							<option value="4">Critical</option>
						</select>
					</div>
				</div>
				<div class="right"></div>
			</div>
		
			<table class="styled with-prev-next" id="syslog_table" cellpadding="0" cellspacing="0">
				<thead>
					<tr>
						<th style="width: 150px !important;">Date</th>
						<th style="width: 100px !important;">Process</th>
						<th style="width: 100px !important;">Level</th>
						<th>Message</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>
	</div>
</div>

<script type="text/javascript">
$(document).ready(function() {
	$("#syslog_table").dataTable({
		sDom: '<"filters"fl>rt<"footer"ip>',
		bProcessing: false,
		sPaginationType: 'full_numbers',
		bServerSide: true,
		bSearchable: false,
		bFilter: false,
		iDisplayLength: 20,
		sAjaxSource: "/ajax/admin/syslog/data_get_syslog",
		aoColumns: [
			{ "bSortable": false }, //date
			{ "bSortable": false }, //type
			{ "bSortable": false }, //level
			{ "bSortable": false } //message
		],
		fnServerParams: function(aoData) {
			aoData.push({"name":"filtertype","value":$("#filtertype").val()});
			aoData.push({"name":"filterlevel","value":$("#filterlevel").val()});
	    },
	});
});
</script>
<?php $this->menu();  ?>

<h1 class="grid_12"><span>Database Stats</span></h1>

<div class="grid_12">
	<p>Database Entries: <span id="num_database_rows"><img src="/theme/img/loader-small.gif" /></span></p>
	
	<p>Database Size: <span id="database_size"><img src="/theme/img/loader-small.gif" /></span></p>
</div>

<h1 class="grid_12"><span>Clear All Click Data</span></h1>

<div class="grid_12">
	<div id="cleardata_wrap">
		<button onclick="showReallySure();">Clear Data</button>
	</div>
	
	<div id="reallysure_wrap" style="display: none;">
		<button onclick="clearClickData();">Are you sure?</button>
	</div>
</div>
		
<script type="text/javascript">
	$(document).ready(function() {
		$.getJSON("/ajax/admin/database/json_database_stats",function(data) {
			$("#num_database_rows").html(data.cnt);
			$("#database_size").html((Math.round(data.size*100)/100) + "MB");
		});
	});
	
	function showReallySure() {
		$("#cleardata_wrap").hide();
		$("#reallysure_wrap").show();
	}
	
	function clearClickData() {
		$.post("/ajax/admin/database/clearData",function(data) {
			alert("Data cleared");
		});
	}
</script>
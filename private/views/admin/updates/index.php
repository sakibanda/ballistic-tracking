<?php $this->menu();  ?>

<h1 class="grid_12">
	<span>Updates</span>
</h1>

<h3 class="grid_12">
	<span>Advanced Redirects</span>
</h3>

<div class="grid_12">
	<p>This will check for updates to the advanced redirect organization list.</p>
	
	<button onclick="checkAdvancedRedirect(this); return false;">Check</button>
	
	<span id="redirectStatus"></span>
</div>

<script type="text/javascript">
	function checkAdvancedRedirect(obj) {
		$(obj).attr("disabled","disabled");
		
		$.get("/admin/updates/updateRedirect",function(data) {
			$("#redirectStatus").html(data);
		});
	}
</script>
<h1 class="grid_12"><span>Analyze Click ID</span></h1>

<div class="grid_12">
	<div class="box">
		<div class="header"><h2>Click ID</h2></div>

		<form action="" id="analyze_clickid_form" method="post" novalidate="novalidate" style="border:0px;">
			<div class="content">
				<div class="row">
					<label for="clickid" style="width: 75px;">Click ID</label>
					<div>
						<input type="text" name="clickid" id="clickid" />
					</div>
				</div>
			</div>

			<div class="actions">
				<div class="right"><input type="submit" value="View Click ID Details" /></div>
			</div>
		</form>
	</div>
</div>	

<div class="grid_12" id="analyze_results"></div>

<script type="text/javascript">
	$(document).ready(function() {
		$("#analyze_clickid_form").submit(function(e) {
			e.preventDefault();
		
			$.post("/ajax/analyze/viewClickid",{'clickid': $("#clickid").val()},function (data) {
				$("#analyze_results").html(data);
			});
		});
	});
</script>
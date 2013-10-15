<?php $this->menu();  ?>

<h1 class="grid_12"><span>Edit Organizations For: <?php echo $cloaker->name; ?></span></h1>

<?php $this->loadTemplate('message_boxes');  ?>

<div class="grid_12">
	<form method="post" action="/cloaker/edit/orgs?id=<?php echo $_GET['id']; ?>" class="box">
		<input type="hidden" name="edit_org" value="1" />
		
		<!-- ORGANIZATIONS -->
		<div class="header"><h2>Organizations</h2></div>
		
		<div class="tabletools">
			<div class="left">
				<a class="open-add-client-dialog" id="add_organization" href="javascript:void(0);">
					<i class="icon-plus"></i>
					Add Organization
				</a>
			</div>
			<div class="right">
			</div>
		</div>
		
		<div class="content">					
			<table class="dataTable" id="exclude_org_holder" cellpadding="0" cellspacing="0">
				<tr class="no_row_borders">
					<th class="tooltip" title="The starting address for the IP range.">Organization</th>
					<th></th>
					<th class="tooltip" title="URL to send the cloaked visitors to.">URL</th>
					<th></th>
				</tr>
			</table>
		</div>
		<!-- END ORGANIZATIONS -->
		
		<div class="actions">
			<div class="right">
				<input type="submit" value="Save" style="" />
			</div>
		</div>
	</form>
</div>

<script type="text/javascript">
function add_organization(defaultOrg, url) {
	if(defaultOrg == undefined) defaultOrg = '';
	if(url == undefined) url = '';

	$.get('/cloaker/viewCloakerOrgs',function(data) {
		var new_html = $(data);
		
		new_html.find("select").val(defaultOrg);
		new_html.find("input").val(url);
	
		$("#exclude_org_holder").append(new_html);

		$(".delete_organization").click(function() {
			var holder = $(this).parents('tr');
			holder.remove();
		});
	});
}

$(document).ready(function() {
	$("#add_organization").click(function() {
		add_organization();
	});
	
	<?php 
		
	if(!$options['organizations']) {
		echo 'add_organization();';
	}
	else {
		foreach($options['organizations'] as $opt) {
			echo 'add_organization("' .  $opt[0] . '","' . $opt[1] . '");';
		}
	}
	?>
});
</script>
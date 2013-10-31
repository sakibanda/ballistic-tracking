<h1 class="grid_12"><span>Traffic Sources</span></h1>

<?php $this->loadTemplate('message_boxes');  ?>

<div class="grid_6">
	<form class="box" method="post" action="/trafficsources">
		<div class="header">
			<h2>New Traffic Source</h2>
		</div>
		
		<div class="content">
			<div class="row">
				<label>Name</label>
				
				<div>
					<input maxlength="70" type="text" value="<?php echo $source->name; ?>" name="name" />
				</div>
			</div>
		</div>
		
		<input type="hidden" name="traffic_source_id" value="<?php echo $source->id(); ?>" />
		
		<div class="actions">
			<div class="right">
				<input type="Submit" value="Save" />
			</div>
		</div>
	</form>
</div>

<div class="grid_6">
	<div class="box with-table">
		<div class="content">
			<table class="dataTable" id="trafficsourcesTable" cellpadding="0" cellspacing="0">
				<thead>
					<tr>
						<th>Name</th>
						<th style="width: 120px;">Actions</th>
					</tr>
				</thead>
				
				<tbody>
					<?php
						if(!$traffic_sources) {
							echo '<tr><td>No traffic sources</td><td></td></tr>';
						}
						else {
							foreach($traffic_sources as $source) {
								printf('<tr><td>%s</td><td>
									   <a class="button small grey" href="/trafficsources/?id=%d"><i class="icon-pencil"></i> Edit</a>
									   <a class="button small grey" href="#" onclick="confirmTrafficSourceDelete(%d);"><i class="icon-remove"></i> Delete</a></td></tr>',$source->name,$source->id(),$source->id());
							}
						}
					?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<script type="text/javascript">
	function confirmTrafficSourceDelete(id) {
		if(confirm('Are You Sure You Want To Delete This Traffic Source?')) {
			window.location.href = "/trafficsources/delete?id=" + id;
		}
	}
    $(function(){
        $('#trafficsourcesTable').dataTable({
            "aoColumns": [
                null,
                { "bSortable": false }
            ]
        });
    });
</script>
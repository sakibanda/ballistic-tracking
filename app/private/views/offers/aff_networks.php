<script type="text/javascript">
	$('table.affnetwork_list').dataTable();
</script>

<h1 class="grid_12"><span>Affiliate Networks</span></h1>

<?php $this->loadTemplate('message_boxes');  ?>

<div class="grid_12">
	<p>What affiliate networks will you be using? Some examples include: ClickBank, Comission Junction, and Amazon.</p>
</div>

<div class="grid_6">
	<form method="post" action="/offers/affnetworks" id="aff_network_form" class="box">
		<div class="header">
			<h2>Add A Network</h2>
		</div>
		
		<div class="content">
			<div class="row">
				<label for="">Affiliate Network</label>
				<div>
					<input  type="text" id="name" name="name" value="<?php echo $network->name;?>" />
					<input type="hidden" id="aff_network_id" name="aff_network_id" value="<?php echo $network->aff_network_id;?>" />
				</div>
			</div>
		</div>
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
			<table id="affnetwork_list" class="dataTable" cellpadding="0" cellspacing="0">
				<thead>
					<tr>
						<th>Network Name</th>
						<th># of Offers</th>
						<th style="width: 120px;">Actions</th>
					</tr>
				</thead>
				<tbody>
					<?php $this->loadView('offers/view_aff_networks'); ?>	
				</tbody>
			</table>
		</div><!-- End of .content -->
	</div>
</div>

<?php

$dialog = new BTDialog();
$dialog->dialog();

?>

<script type="text/javascript">
	function confirmAffNetworkDelete(id) {
		if(confirm('Are You Sure You Want To Delete This Affiliate Network?')) {
			window.location.href = "?delete_aff_network_id=" + id;
		}
	}
    $(function(){
        $('#affnetwork_list').dataTable({
            //"sDom": 'T<"clear">lfrtip',
            /*"oTableTools": {
                "sSwfPath": "/theme/swf/copy_csv_xls_pdf.swf"
            },*/
            "aoColumns": [
                null,
                {"sClass":"center"},
                { "bSortable": false,"sClass":"center" }
            ]
        });
    });
</script>

<script type="text/javascript">

function showAffNetworkForm(id) {
	var params = {aff_network_id:id};

	<?php $dialog->show('/ajax/offers/dialogAffnetworkForm','Edit Affiliate Network',array('onLoad'=>'setupAffiliateNetworkForm')); ?>
}

function closeAffNetworkForm() {
	<?php $dialog->close(); ?>
}

</script>
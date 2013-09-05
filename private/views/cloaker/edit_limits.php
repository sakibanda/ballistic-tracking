<?php $this->menu();  ?>

<h1 class="grid_12"><span>Edit Limits For: <?php echo $cloaker->name; ?></span></h1>

<?php $this->loadTemplate('message_boxes');  ?>

<div class="grid_6">
	<form method="post" action="/cloaker/edit/limits?id=<?php echo $_GET['id']; ?>" class="box">
		
		<!-- IPS -->
		<div class="header"><h2>Limits</h2></div>
		
		<div class="content">			
			<div class="row">
				<label class="tooltip" title="The number of times a user can go through the redirect, before being forced to the safe page.">Click Frequency</label>				
				<div>
					<input type="text" name="clickfrequency" value="<?php echo $options['clickfrequency']; ?>" />
				</div>
			</div>
			
			<div class="row">
				<label class="tooltip" title="Forces this number of clicks to the safe page, before the redirect logic becomes active.">Safety Buffer</label>				
				<div>
					<input type="text" name="expiration" value="<?php echo $options['expiration']; ?>" />
				</div>
			</div>
		</div>
		
		<div class="actions">
			<div class="right">
				<input type="submit" value="Save" name="save" style="" />
			</div>
		</div>
	</form>
</div>
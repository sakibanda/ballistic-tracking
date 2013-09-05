<h1 class="grid_12"><span>Update Your Clicks</span></h1>



<div class="grid_12">
	<p>Here is where you can update your income for Ballistic Tracking, by importing your click ids from your affiliate marketing reports. Please enter one per line.</p>
</div>
	
    
    <?php if ($success == true) { ?>
		<div class="grid_12">
			<div class="alert success">
				<span class="icon"></span>
				<strong>Success:</strong>
				Your submission was successful. Your account income now reflects the click ids from the commissions you just uploaded.
			</div>
		</div>
    <?php } ?>
	
<div class="grid_12">
	<form class="box" id="add_source_form" action="" method="post" novalidate="novalidate">
		<div class="header"><h2>Click IDs</h2></div>
		<div class="content">
			<div class="row">
				<label for="ad_network_name" style="width: 75px;">Click IDs</label>
				<div>
					<textarea name="clickids" style="height: 200px; width: 100%; margin: 10px auto;"><?php echo $_POST['clickids']; ?></textarea>
				</div>
			</div>
			
			<div class="row">
				<label>Update Type</label>
				<div>
					<div class="subrow"><label><input type="radio" checked="checked" name="update_type" value="1"> Mark as conversion</label></div>
					<div class="subrow"><label><input type="radio" name="update_type" value="0"> Clear conversion</label></div>
				</div>
			</div>
		</div>

		<div class="actions">
			<div class="right"><input type="submit" style="display: inline; margin-left: 10px;" value="Update" /></div>
		</div>
	</form>
</div>	
<h1 class="grid_12"><span>Spending</span></h1>

<?php $this->loadTemplate('message_boxes');  ?>

<div class="grid_12">
	<div class="grid_6">
		<p>Here is where you can update your daily spending for Ballistic Tracking. This data is used to calculate your average costs and cpc throughout the system. </p>
	</div>	
</div>

<div class="grid_6">	
	<div class="grid_12">
		<form id="updatecostform" action="" method="post" class="box">
			<div class="header">
				<h2>Add A Spend</h2>
			</div>
			
			<div class="content">
				<div class="row">
					<label for="">Date</label>
					<div>
						<input class="date" type="text" name="date" id="date" value="<?php echo date('m/d/Y',time() - 60*60*24); ?>" tabindex="20"/>
					</div>
				</div>
				
				<div class="row">
					<label for="">Campaign</label>						
					<div>
						<select name="campaign_id" id="campaign_id">
							<?php
								foreach($campaigns as $row) {								
									printf('<option value="%s">%d - %s</option>', $row->id(),$row->id(),$row->name);
								} ?>
						</select>
					</div>
				</div>	
			
				<div class="row">
					<label for="">Amount</label>
					<div>
						<input onkeydown="validateNumber(event);" type="text" id="amount" name="amount" value="" style="display: inline;" />
					</div>
				</div>
			</div>
			
			<div class="actions">
				<div class="left">
					<button class="grey cancel">Cancel</button>
				</div>
				<div class="right">
					<input type="submit" value="Add" />
				</div>
			</div>
		</form>
	</div>
		
		<script>

		</script>	
</div>

<div class="grid_6">
	<div class="grid_12">
		<div class="box with-table">
			<div class="header">
				<h2>Daily Spending</h2>
			</div>
			<div class="content">
				<table id="spending_table" class="dataTable" cellpadding="0" cellspacing="0">
					<thead>
						<tr>
							<th>Date</th>
							<th>Campaign</th>
							<th>Amount</th>
							<th>Actions</th>
						</tr>
					</thead>
				</table>
			</div><!-- End of .content -->
		</div><!-- End of .box -->
	</div><!-- End of .grid_12 -->
</div>

<script type="text/javascript" src="/theme/js/scripts/spending.js"></script>
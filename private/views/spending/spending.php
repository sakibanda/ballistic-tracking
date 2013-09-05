<h1 class="grid_12"><span>Update Daily Spending</span></h1>

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
						<input type="text" id="amount" name="amount" value="" style="display: inline;" />
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
		$(document).ready(function() {		
			$("#spending_table").dataTable({
				sDom: '<"filters"fl>rt<"footer"ip>',
				bProcessing: false,
				sPaginationType: 'full_numbers',
				bServerSide: true,
				bSearchable: false,
				bFilter: false,
				iDisplayLength: 100,
				sAjaxSource: "/ajax/spending/data_spending",
				aoColumns: [
					{ "bSortable": false }, //date
					{ "bSortable": false }, //campaign
					{ "bSortable": false }, //spend
					{ "bSortable": false } //actions
				],
				fnDrawCallback: function() {
					$(".delete_spend").click(function(e) {
						e.preventDefault();
						
						if(!confirm("Are you sure you want to delete this entry?")) {
							return;
						}
				
						var spending_id = $(this).parents('tr').find('.spending_id').val();
						var postdata = {spending_id:spending_id};
						
						$.post("/ajax/spending/postDelete",postdata,function(data) {
							if(data != 1) {
								alert("An error ocurred while deleting the entry");
							}
							else {
								$("#spending_table").dataTable().fnDraw();
							}
						});
					});
				}
			});
		});
		</script>	
</div>

<div class="grid_6">
	<div class="grid_12">
		<div class="box with-table">
		
			<div class="header">
				<h2>Daily Spending</h2>
			</div>
			
			<div class="content">
				<table id="spending_table" class="styled" cellpadding="0" cellspacing="0">
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
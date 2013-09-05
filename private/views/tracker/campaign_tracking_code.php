<h1 class="grid_12">
	<span>Tracking Code</span>
</h1>
	
<div class="grid_12">
	<div class="box">
		<div class="header">
			<h2>Tracking Code</h2>
		</div>
		
		<div class="content">
			<?php if($campaign->type == 2) { ?>
				<p>Your tracking URL is: </p>
	
				<p><textarea><?php echo $campaign->getUrl(); ?></textarea></p>
			<?php } else { ?>
				<p>Your tracking URL is: </p>
	
				<p><textarea><?php echo $campaign->getUrl(); ?></textarea></p>
			<?php } ?>
		</div>
	</div>
</div>

<?php if($campaign->type == 1) { ?>
<div class="grid_12">
	<div class="box">
		<div class="header">
			<h2>Outbound Offer Links</h2>
		</div>
		
		<div class="content">
			<?php
				foreach($campaign->offers as $campoffer) {
					printf("<p>%s:</p>",@$campoffer->offer->name);
					
					printf("<p><textarea>%s</textarea></p>",$campoffer->getUrl());
				}
			?>
		</div>
	</div>
</div>
<?php } ?>
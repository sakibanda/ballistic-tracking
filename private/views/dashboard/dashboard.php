<ul class="stats not-on-phone">
	<li>
		<strong><?php echo number_format($top_stats['clicks']); ?></strong>
		<small>Clicks</small>
	</li>
	<li>
		<strong><?php echo number_format($top_stats['leads']); ?></strong>
		<small>Leads</small>
	</li>
	<li>
		<strong>$<?php echo number_format($top_stats['income'],2); ?></strong>
		<small>Income</small>
	</li>
	<li>
		<strong>$<?php echo number_format($top_stats['cost'],2); ?></strong>
		<small>Cost</small>
	</li>
	<li>
		<strong>$<?php echo number_format($top_stats['net'],2); ?></strong>
		<small>Net</small>
	</li>
	
	<li>
		<strong><?php echo number_format($top_stats['net'] / $top_stats['cost'] * 100,2); ?>%</strong>
		<small>ROI</small>
	</li>

</ul><!-- End of ul.stats -->
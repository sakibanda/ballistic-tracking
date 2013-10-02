<?php

$stats_total['clicks'] = 0; 
$stats_total['leads'] = 0; 
$stats_total['payout'] = 0; 
$stats_total['income'] = 0; 
$stats_total['cost'] = 0; 
$stats_total['net'] = 0;

?>

<div class="grid_12">
	<div class="box with-table">
		<div class="header">
			<h2>Breakdown</h2>
		</div>
		<div class="content">
			<div class="tabletools">
				<div class="right">
					<a href="#" onclick="exportCsv(); return false;">CSV</a>
				</div>
			</div>
			<table cellpadding="0" cellspacing="0" class="styled">
				<thead><tr>   
					<th>Time</th>
					<th>Clicks</th> 
					<th>Leads</th>
					<th>Avg Conv %</th>
					<th>Avg Payout</th>
					<th>Avg EPC</th> 
					<th>Avg CPC</th>
					<th>Income</th>
					<th>Cost</th>
					<th>Net</th>
					<th>ROI</th>
				</tr></thead>
				
				<?php
				
				//Buffer the body
				ob_start();
				
				?>
				<tbody>
	
				<?php foreach($breakdown_result as $breakdown_row) {
	
					//also harvest a total stats
					$stats_total['clicks'] = $stats_total['clicks'] + $breakdown_row['clicks']; 
					$stats_total['leads'] = $stats_total['leads'] + $breakdown_row['leads']; 
					$stats_total['payout'] = $stats_total['payout'] + $breakdown_row['payout']; 
					$stats_total['income'] = $stats_total['income'] + $breakdown_row['income']; 
					$stats_total['cost'] = $stats_total['cost'] + $breakdown_row['cost']; 
					$stats_total['net'] = $stats_total['net'] + $breakdown_row['net']; 
	
					$ex = explode('-',$breakdown_row['time_from']);
					if ($breakdown == 'day') { 
						$html['time'] = date('M d, Y', mktime(0,0,0,$ex[1],$ex[2],$ex[0]));      
					} elseif ($breakdown == 'month') { 
						$html['time'] = date('M Y', mktime(0,0,0,$ex[1],1,$ex[0]));      
					} elseif ($breakdown == 'year') { 
						$html['time'] = date('Y', mktime(0,0,0,1,1,$ex[0]));      
					}
	
					$html['clicks'] = BTHtml::encode($breakdown_row['clicks']);
					$html['leads'] = BTHtml::encode($breakdown_row['leads']);
					$html['conv'] = BTHtml::encode($breakdown_row['conv'].'%');
					$html['payout'] = BTHtml::encode(dollar_format($breakdown_row['payout']));
					$html['epc'] = BTHtml::encode(dollar_format($breakdown_row['epc']));
					$html['cpc'] = BTHtml::encode(dollar_format($breakdown_row['cpc']));
					$html['income'] = BTHtml::encode(dollar_format($breakdown_row['income']));
					$html['cost'] = BTHtml::encode(dollar_format($breakdown_row['cost']));
					$html['net'] = BTHtml::encode(dollar_format($breakdown_row['net'])); 
					$html['roi'] = BTHtml::encode($breakdown_row['roi'].'%'); ?>
	
					<tr>
						<td><?php echo $html['time']; ?></td>
						<td><?php echo $html['clicks']; ?></td>
						<td><?php echo $html['leads']; ?></td> 
						<td><?php echo $html['conv']; ?></td>
						<td><?php echo $html['payout']; ?></td> 
						<td><?php echo $html['epc']; ?></td>
						<td><?php echo $html['cpc']; ?></td>
						<td><?php echo $html['income']; ?></td>
						<td>(<?php echo $html['cost']; ?>)</td>
						<td class="<?php if ($breakdown_row['net'] >= 0) { echo 'report_pos'; } elseif ($breakdown_row['net'] < 0) { echo 'report_neg'; } else { echo 'report_zero'; } ?>"><?php echo $html['net'] ; ?></td>
						<td class="<?php if ($breakdown_row['net'] >= 0) { echo 'report_pos'; } elseif ($breakdown_row['net'] < 0) { echo 'report_neg'; } else { echo 'report_zero'; } ?>"><?php echo $html['roi'] ; ?></td>
					</tr>
				<?php } ?>
	
				<?php  $rows = count($breakdown_result);
					$html['clicks'] = BTHtml::encode($stats_total['clicks']);  
					$html['leads'] = BTHtml::encode($stats_total['leads']);  
					$html['conv'] = BTHtml::encode(calculate_conv($stats_total['clicks'], $stats_total['leads']) . '%');
					$html['payout'] =  BTHtml::encode(dollar_format(calculate_payout($stats_total['leads'],$stats_total['income'])));   
					$html['epc'] =  BTHtml::encode(dollar_format(calculate_epc($stats_total['clicks'],$stats_total['income'])));
					$html['cpc'] =  BTHtml::encode(dollar_format(calculate_cpc($stats_total['clicks'],$stats_total['cost'])));
					$html['income'] =  BTHtml::encode(dollar_format(($stats_total['income'])));
					$html['cost'] =  BTHtml::encode(dollar_format(($stats_total['cost']))); 
					$html['net'] = BTHtml::encode(dollar_format(calculate_net($stats_total['income'],$stats_total['cost'])));
					$html['roi'] = BTHtml::encode(calculate_roi($stats_total['income'],$stats_total['cost']) . '%');
				?> 
	
				</tbody>
	
				<?php
				$stat_html = ob_get_contents();
				ob_end_clean();
				?>
				
				<tfoot>
					<tr>
						<td><strong>Totals for report</strong></td>
						<td><strong><?php echo $html['clicks']; ?></strong></td>
						<td><strong><?php echo $html['leads']; ?></strong></td>
						<td><strong><?php echo $html['conv']; ?></strong></td>  
						<td><strong><?php echo $html['payout']; ?></strong></td>   
						<td><strong><?php echo $html['epc']; ?></strong></td>  
						<td><strong><?php echo $html['cpc']; ?></strong></td>  
						<td><strong><?php echo $html['income']; ?></strong></td>
						<td><strong>(<?php echo $html['cost']; ?>)</strong></td>
						<td class=" <?php if ($stats_total['net'] >= 0) { echo 'report_pos'; } elseif ($stats_total['net'] < 0) { echo 'report_neg'; } else { echo 'report_zero'; } ?>"><strong><?php echo $html['net']; ?></strong></td>
						<td class=" <?php if ($stats_total['net'] >= 0) { echo 'report_pos'; } elseif ($stats_total['net'] < 0) { echo 'report_neg'; } else { echo 'report_zero'; } ?>"><strong><?php echo $html['roi']; ?></strong></td>
					</tr>
	
					<tr>
						<th>Time</th>
						<th>Clicks</th> 
						<th>Leads</th>
						<th>Avg Conv %</th>
						<th>Avg Payout</th>
						<th>Avg EPC</th> 
						<th>Avg CPC</th>
						<th>Income</th>
						<th>Cost</th>
						<th>Net</th>
						<th>ROI</th>
					</tr>
				</tfoot>
				
				<?php echo $stat_html; ?>
			</table>
		</div>
	</div>
</div>

<script type="text/javascript">
    function exportCsv() {
        iframe = document.createElement('iframe');
        iframe.style.display = 'none';
        document.body.appendChild(iframe);
        iframe.src = '/reports/exportBreakdown?iSortCol_0=0&sSortDir_0=asc';
    }
</script>
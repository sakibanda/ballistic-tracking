<?php			
if (count ( $aff_networks ) < 1) {
	?>
	<tr>
		<td class="center">You have not added any networks.</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
	<?php
}
else	{
	foreach($aff_networks as $aff_network_row) {
		$html ['name'] = BTHtml::encode ( $aff_network_row->get('name'));
		$html ['aff_network_id'] = BTHtml::encode ( $aff_network_row->get('aff_network_id'));

		$no_of_accounts = count($aff_network_row->offers);
		?>
		<tr>
			<td><?php printf('%s',$html['name']); ?></td>
			<td class="center"><a href="/offers/?network=<?php echo $aff_network_row->aff_network_id; ?>"><?php echo $no_of_accounts; ?></a></td>
			<td class="center">
				<a href="/offers/affnetworks?aff_network_id=<?php echo $html['aff_network_id']; ?>" class="button small grey tooltip" alt="Edit" title="Edit"> <i class="icon-pencil"></i> Edit</a>
				
				<a href="#" class="button small grey tooltip" alt="Delete" title="Delete" onclick="confirmAffNetworkDelete(<?php echo $html['aff_network_id']; ?>)"><i class="icon-remove"></i> Delete</a>
			</td>
		</tr>
		<?php
	}
}
?>
<div style="border-bottom: 1px solid #b9b9b9;" class="offer_table">
	<input type="hidden" name="campaign_lpoffer_id[]" value="<?php echo $campoffer->id(); ?>" />
    <input type="hidden" name="lpoffer_id[]" value="<?php echo @$campoffer->offer->offer_id; ?>" />
	<table class="styled">
		<tbody>
			<tr class="no_row_borders">
				<td style="width: 90px;">Offer Name:</td>
				<td style="width: 550px;">
                    <?php if(@$campoffer->offer->offer_id) { ?>
                        <b><?php echo @$campoffer->offer->offer_id; ?> - <?php echo @$campoffer->offer->name; ?></b>
                        <input name="lpoffer_name[]" type="hidden" value="<?php echo @$campoffer->offer->name; ?>" />
                    <?php } else { ?>
                        <input name="lpoffer_name[]" type="text" value="<?php echo @$campoffer->offer->name; ?>" />
                    <?php } ?>
                </td>
				<td style="width: 90px;">Payout: $</td>
				<td> <input name="lpoffer_payout[]" type="text" value="<?php echo @$campoffer->offer->payout; ?>" /></td>
			</tr>
			<tr class="no_row_borders">
				<td style="width: 90px;">URL:</td>
				<td style="width: 550px;"> <textarea name="lpoffer_url[]"><?php echo @$campoffer->offer->url; ?></textarea></td>
				<td style="width: 90px;"></td>
				<td></td>
			</tr>
			
			<?php if(@$campoffer->offer->offer_id) { ?>
				<tr class="no_row_borders">
					<td style="width: 90px;">Aff Network:</td>
					<td style="width: 550px;"><?php echo @$campoffer->offer->network->name; ?><input type="hidden" name="lpoffer_aff_network_id[]" value="<?php echo @$campoffer->offer->aff_network_id; ?>" /></td>
					<td><img src="/theme/img/icons/16x16/delete.png" onclick="deleteOfferTable(this);" style="cursor: pointer; width: 16px; height: 16px;"></td><td></td>
				</tr>
			<?php } else { ?>
				<tr class="no_row_borders">
					<td style="width: 90px;">Aff Network:</td>
					<td style="width: 550px;">
						<?php
							BTForm::createSelect('lpoffer_aff_network_id[]',AffNetworkModel::model()->getRows(),@$campoffer->offer->aff_network_id,'','','name','aff_network_id');
						?>
					</td>
					<td><img src="/theme/img/icons/16x16/delete.png" onclick="deleteOfferTable(this);" style="cursor: pointer; width: 16px; height: 16px;"></td><td></td>
				</tr>
			<?php } ?>
		</tbody>
	</table>
</div>
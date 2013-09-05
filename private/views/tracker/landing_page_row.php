<tr>
	<td>
		<input type="hidden" name="campaign_lp_id[]" value="<?php echo $camplp->id(); ?>" />
		<input type="text" name="lp_name[]" value="<?php echo @$camplp->landing_page->name; ?>" />
	</td>
	<td><input type="text" name="lp_url[]" placeholder="http://" value="<?php echo @$camplp->landing_page->url; ?>" /></td>
	<td>
		<input type="text" name="lp_weight[]" value="<?php echo @$camplp->weight; ?>" style="width: 50px;" /> 
		<span>100%</span>
		<img src="/theme/img/icons/16x16/delete.png" onclick="deleteDataRow(this);" style="cursor: pointer; width: 16px; height: 16px;">
	</td>
</tr>
<tr class="no_row_borders">
	<td style="width: 150px;"><input type="text" class="exclude_from" name="exclude_ip_range_from[]" style="width: 100%;-moz-box-sizing: border-box;-webkit-box-sizing: border-box;box-sizing: border-box;" value="<?php echo getArrayVar($_GET,'ip_from'); ?>" /></td> 
	<td style="width: 7px; vertical-align: middle;">-</td>
	<td style="width: 150px;"><input type="text" class="exclude_to" name="exclude_ip_range_to[]" style="width: 100%;-moz-box-sizing: border-box;-webkit-box-sizing: border-box;box-sizing: border-box;" value="<?php echo getArrayVar($_GET,'ip_to'); ?>" /></td>
	
	<td style="width: 7px; vertical-align: middle;">
		<span class="icon icon-double-angle-right"></span>
	</td>
	
	<td>
		<input type="text" name="exclude_ip_range_url[]" style="width: 100%;-moz-box-sizing: border-box;-webkit-box-sizing: border-box;box-sizing: border-box;" value="<?php echo getArrayVar($_GET,'url'); ?>" />
	</td>
	
	<td>
		<input type="text" name="exclude_ip_range_memo[]" style="width: 100%;-moz-box-sizing: border-box;-webkit-box-sizing: border-box;box-sizing: border-box;" value="<?php echo getArrayVar($_GET,'memo'); ?>" />
	</td>
	
	<td style="vertical-align: middle; width: 25px;"><img src="/theme/img/icons/16x16/delete.png" class="delete_ip_range" onclick="deleteDataRow(this);" style="cursor: pointer; width: 16px; height: 16px;" /></td>
</tr>
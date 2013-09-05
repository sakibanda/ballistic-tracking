<tr class="no_row_borders">	
	<td style="vertical-align: middle; width: 20px;">
		<input type="hidden" name="user_agent_regex[]" value="<?php echo (getArrayVar($_GET,'regex')) ? '1' : '0'; ?>" />
		<input type="checkbox" name="regex_checkbox[]" class="regex_checkbox" value="1" <?php echo (getArrayVar($_GET,'regex')) ? 'checked="checked"' : ''; ?> />
	</td>
	
	<td>
		<input type="text" name="exclude_user_agent[]" style="width: 100%;-moz-box-sizing: border-box;-webkit-box-sizing: border-box;box-sizing: border-box;" value="<?php echo getArrayVar($_GET,'user_agent'); ?>" />
	</td>
	
	<td style="width: 7px; vertical-align: middle;">
		<span class="icon icon-double-angle-right"></span>
	</td>
	
	<td>
		<input type="text" class="exclude_url" name="exclude_user_agent_url[]" style="width: 100%;-moz-box-sizing: border-box;-webkit-box-sizing: border-box;box-sizing: border-box;" value="<?php echo getArrayVar($_GET,'url'); ?>" />
	</td>
	
	<td>
		<input type="text" name="exclude_user_agent_memo[]" style="width: 100%;-moz-box-sizing: border-box;-webkit-box-sizing: border-box;box-sizing: border-box;" value="<?php echo getArrayVar($_GET,'memo'); ?>" />
	</td>
	
	<td style="vertical-align: middle; width: 30px;">
		<img src="/theme/img/icons/16x16/delete.png" onclick="deleteDataRow(this);" style="cursor: pointer; width: 16px; height: 16px;" class="delete_user_agent" /> 
	</td>
</tr>
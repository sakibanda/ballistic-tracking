<tr class="no_row_borders">	
	<td>
		<select name="organizations[]">
			<option value="">None</option> 
			<?php

			foreach($cloaker_orgs as $org=>$val) {
				echo '<option value="' . $val['org_id'] . '">' . $org . '</option> ';
			}

			?>
		</select>
	</td>
	
	<td style="width: 7px; vertical-align: middle;">
		<span class="icon icon-double-angle-right"></span>
	</td>
	
	<td>
		<input type="text" value="" style="width: 100%;-moz-box-sizing: border-box;-webkit-box-sizing: border-box;box-sizing: border-box;" name="org_url[]">
	</td>
	
	<td style="vertical-align: middle; width: 30px;">
		<img src="/theme/img/icons/16x16/delete.png" class="delete_organization" style="cursor: pointer; width: 16px; height: 16px;" />
	</td>
</tr>
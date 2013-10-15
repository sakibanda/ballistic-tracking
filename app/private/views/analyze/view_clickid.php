<div class="box with-table">
	<div class="header">
		<h2>Details for <?php echo $clickid;?></h2>
	</div>

	<div class="content">
		<table class="dataTable vertical_columns" cellpadding="0" cellspacing="0">
			<?php
			foreach($clickid_data as $name=>$val) {
				printf("<tr><th>%s</th><td>%s</td></tr>\n",BTHtml::encode($name),BTHtml::encode($val));
			}
			?>
		</table>
	</div>
</div>
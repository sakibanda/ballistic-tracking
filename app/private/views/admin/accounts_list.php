<div class="box with-table">
	<div class="header">
		<h2>User Accounts</h2>
	</div>
	<div class="tabletools">
		<div class="left">
			<a class="open-manage-account-dialog" href="javascript:addNewUser();"><i class="icon-plus"></i>Add</a>
		</div>
		<div class="right"></div>
	</div>
	<div class="content">
		<table class="manageaccountlist dataTable with-prev-next" cellpadding="0" cellspacing="0">
			<thead>
				<tr>
					<th>User ID</th>
					<th>Username</th>
					<th>Email</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>
			<?php
			foreach($userlist as $row) {
				$html ['email'] = BTHtml::encode($row->email);
				$html ['user_name'] = BTHtml::encode($row->user_name);
				?>
				<tr>
					<td class="user_id" <?php if($row->privilege == 10) { echo 'style="background-color:lightgray;" title="admin" alt="admin" '; } ?>><?php echo $row->user_id; ?></td>
					<td><?php echo $html['user_name']; ?></td>
					<td><?php echo $html['email']; ?></td>
					<td class="center">
						<a onclick="viewAsUser('<?php echo $row->user_id; ?>')" class="button small grey tooltip editUser" title="View As User"><i class="icon-user"></i></a>
						<a onclick="editUser('<?php echo $row->user_id; ?>')" class="button small grey tooltip editUser" title="Edit"><i class="icon-pencil"></i></a>
						<a onclick="deleteUser('<?php echo $row->user_id; ?>','<?php echo $row->privilege; ?>')" class="button small grey tooltip deleteUser" title="Remove"><i class="icon-remove"></i></a>
					</td>
				</tr>
				<?php
			}
			?>
			</tbody>
		</table>
	</div><!-- End of .content -->
</div><!-- End of .box -->

<script type="text/javascript">
	$('table.manageaccountlist').table();
</script>
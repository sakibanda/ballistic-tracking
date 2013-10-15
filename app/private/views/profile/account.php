<?php $this->loadTemplate('message_boxes');  ?>

<div class="grid_6">
	<h1 class="grid_12">
		<span>Update Profile</span>
	</h1>
	
	<!-- The settings dialog -->
	<form action="/profile/" method="post" enctype="multipart/form-data" id="my_account_form" class="no-box">		
			
		<div class="grid_12">
			<div class="box">
				<div class="header">
					<h2>General</h2>
				</div>
				
				<div class="content">
					<div class="row">
						<label for="timezone" class="tooltip" title="Select your time zone. All reports will use this">Time zone</label>
						<div>
							<select class="search" name="timezone" id="timezone">
								<?php
								
								static $regions = array(
									'Africa' => DateTimeZone::AFRICA,
									'America' => DateTimeZone::AMERICA,
									'Antarctica' => DateTimeZone::ANTARCTICA,
									'Arctic' => DateTimeZone::ARCTIC,
									'Asia' => DateTimeZone::ASIA,
									'Atlantic' => DateTimeZone::ATLANTIC,
									'Australia' => DateTimeZone::AUSTRALIA,
									'Europe' => DateTimeZone::EUROPE,
									'Indian' => DateTimeZone::INDIAN,
									'Pacific' => DateTimeZone::PACIFIC,
									'UTC' => DateTimeZone::UTC,
								);
								
								$tzlist = array();
								foreach ($regions as $name => $mask) {
									$tzlist[] = DateTimeZone::listIdentifiers($mask);
								}	
								
								foreach($tzlist as $tzregion) {
									foreach($tzregion as $zone) {
										if($zone == BTAuth::user()->timezone) {		
											echo '<option selected="selected" value="' . $zone . '">' . $zone . '</option>' . PHP_EOL;
										}
										else {
											echo '<option value="' . $zone . '">' . $zone . '</option>' . PHP_EOL;
										}
									}
								}
								
								?>
							</select>
						</div>
					</div>
					
					<div class="row">
						<label for="email" class="tooltip" title="Your email address is used to retrieve your password.">Email</label>
						<div>
							<input type="text" id="email" name="email" size="40" value="<?php echo BTAuth::user()->get('email'); ?>" class="medium" />
						</div>
					</div>
					
					<div class="row">
						<label for="user_name" class="tooltip" title="The username you use to login">Username</label>
						<div>
							<div><?php echo BTAuth::user()->get('user_name'); ?></div>
						</div>
					</div>
					
					<div class="row">
						<label>Role</label>
						<div>
							<div>
								<?php
									if(BTAuth::user()->isAdmin()) {
										echo 'Administrator';
									}
									else {
										echo 'Affiliate';
									}
								?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
			
		<div class="grid_12">
			<div class="box">
				<div class="header"><h2>Change Password</h2></div>
				
				<div class="content">
					<div class="row">
							<label for="old_pass" class="tooltip" title="Enter your current password if you would like to change passwords">Current Password</label>
							<div>
								<input type="password" id="old_pass" name="old_pass" size="40" class="medium" />
							</div>
					</div>
					
					<div class="row">
							<label for="pass" class="tooltip" title="Enter a secure password">New Password</label>
							<div>
								<input type="password" id="pass" name="pass" size="40" class="medium" />
							</div>
					</div>
					
					<div class="row">
							<label for="pass_confirm" class="tooltip" title="Confirm your new password">Retype New Password</label>
							<div>
								<input type="password" id="pass_confirm" name="pass_confirm" size="40" class="medium" />
							</div>
					</div>
				</div>
			</div>
		</div>
		
		<div class="grid_12">
			<div class="box">
				<div class="actions">
					<div class="left"></div>
					<div class="right">
						<input type="submit" value="Save Changes" />
					</div>
				</div>
			</div>
		</div>
	</form>
</div>

<div class="grid_6">
	<h1 class="grid_12">
		<span>Login History</span>
	</h1>
	
	<div class="grid_12">
		<div class="box with-table">
			<div class="content">			
				<table class="dataTable" cellpadding="0" cellspacing="0" id="login_log_table">
					<thead>
						<tr>
							<th>Status</th>
							<th>Time</th>
							<th>IP</th>
						</tr>
					</thead>
					
					<tbody>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	
	<script type="text/javascript">
		$("#login_log_table").table({
			bServerSide: true,
			bSearchable: false,
			bFilter: false,
			iDisplayLength: 20,
			sAjaxSource: "/profile/dataGetLoginLogs",
			fnInitComplete: function() {
				$(".dataTables_length").hide();
				$(".dataTables_processing").hide();
				$(".dataTables_info").hide();
			},
			aoColumns: [
				{ "bSortable": false }, //status
				{ "bSortable": false }, //time
				{ "bSortable": false }, //ip
			]
		});
	</script>
</div>
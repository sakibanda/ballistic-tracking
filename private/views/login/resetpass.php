<div class="grid_6 center_col">
	<form method="post" action="/login/passReset?key=<?php echo $_GET['key']; ?>" class="box">
		<div class="header">
			<h2><span class="icon icon-lock"></span>Reset Your Password</h2>
		</div>
		
		<div class="content">
			<div class="login-messages">
				<div class="message welcome">Enter your new password below</div>
				
				<div class="message failure" style="display: <?php echo (!$error['pass']) ? 'none' : 'block'; ?>;"><?php echo $error['pass']; ?></div>
			</div>
			
			<div class="form-box">
				<div class="row">
					<label for="user_name" style="">
						Username
					</label>
					<div>
						<input tabindex="1" type="text" disabled="disabled" class="required noerror" name="user_name" id="user_name" value="<?php echo $html['user_name']; ?>" />
					</div>
				</div>
				
				<div class="row">
					<label for="pass" style="">
						New Pass
					</label>
					<div>
						<input tabindex="1" type="password" class="required noerror" name="pass" id="pass" value="" />
					</div>
				</div>
				
				<div class="row">
					<label for="verify_pass" style="">
						Confirm Pass
					</label>
					<div>
						<input tabindex="1" type="password" class="required noerror" name="verify_pass" id="verify_pass" value="" />
					</div>
				</div>
			</div>
		</div>
		
		<div class="actions">
			<div class="left">
				<a href="/login" class="button cancel">Cancel</a>
			</div>
			<div class="right">
				<input tabindex=3 type="submit" value="Save Password  &raquo;" name="save_pass" />
			</div>
		</div><!-- End of .actions -->
	</form>
</div>
<?php if ($success == true) { ?>

	<form method="post" action="/login" class="box">
	
		<div class="header">
			<h2><span class="icon icon-lock"></span>Reset Your Password</h2>
		</div>
		
		<div class="content">
			<p>A recovery link has been sent to your email account. It may take 1-5 minutes to receive it.</p>
		</div>
	</form>

<?php } else { ?>

<div class="grid_6 center_col">
	<form method="post" action="/login/lostPass" class="box">
		<div class="header">
			<h2><span class="icon icon-lock"></span>Reset Your Password</h2>
		</div>
		
		<div class="content">
			<div class="login-messages">
				<div class="message welcome">Please enter your username and e-mail address.</div>
				
				<div class="message failure" style="display: <?php echo (!$error['user']) ? 'none' : 'block'; ?>;"><?php echo $error['user']; ?></div>
			</div>
			
			<div class="form-box">
				<div class="row">
					<label for="user_name" style="">
						Username
					</label>
					<div>
						<input tabindex="1" type="text" class="required noerror" name="user_name" id="user_name" value="<?php echo $html['user_name']; ?>" />
					</div>
				</div>
			</div>
			
			<div class="form-box">
				<div class="row">
					<label for="email" style="">
						Email
					</label>
					<div>
						<input tabindex="1" type="text" class="required noerror" name="email" id="email" value="<?php echo $html['email']; ?>" />
					</div>
				</div>
			</div>
		</div>
		
		<div class="actions">
			<div class="left">
				<a href="/login" class="button cancel">Cancel</a>
			</div>
			<div class="right">
				<input tabindex=3 type="submit" value="Get New Password  &raquo;" name="get_pass" />
			</div>
		</div><!-- End of .actions -->
	</form>
</div>
	
<?php } ?>
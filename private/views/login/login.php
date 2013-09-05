<div class="grid_6 center_col">
	<form method="post" action="/login" class="box">
		<div class="header">
			<h2><span class="icon icon-lock"></span>Login</h2>
		</div>
		
		<div class="content">
			<!-- Login messages -->
			<div class="login-messages">
				<div class="message welcome">Welcome back!</div>
				<div class="message failure" style="display: <?php echo ($success) ? 'none' : 'block'; ?>;">Invalid credentials.</div>
			</div>
			
			<div class="form-box">
				<div class="row">
					<label for="user_name" style="">
						Username
					</label>
					<div style="">
						<input tabindex="1" type="text" class="required noerror" name="user_name" id="user_name" value="" />
					</div>
				</div>
	
				<div class="row">
					<label for="pass">
						Password
						<small><a href="/login/lostPass">Forgot Login</a></small>
					</label>
					<div>
						<input tabindex=2 type="password" class="required noerror" name="pass" id="pass" />
					</div>
				</div>
			</div><!-- End of .form-box -->		
		</div>
		
		<div class="actions">
			<!--<div class="left">
				<div class="rememberme">
					<input tabindex=4 type="checkbox" name="login_remember" id="login_remember" checked /><label for="login_remember">Remember me?</label>
				</div>
			</div>-->
			<div class="right">
				<input tabindex=3 type="submit" value="Sign In" name="login_btn" />
			</div>
		</div><!-- End of .actions -->
	</form>
</div>
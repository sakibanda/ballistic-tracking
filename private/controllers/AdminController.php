<?php

class AdminController extends BTAdminController {
	public function indexAction() {	
		$this->setVar('title','Admin Settings');
	
		$this->render('admin/index');
	}
	
	public function changelogAction() {
		$this->setVar("title","View Version Changes");
		
		$this->render('admin/changelog');
	}
	
	public function menu() {
		?>
		<div class="grid_12 admin_opts">
			<div class="buttons">
				<a href="/admin">
					<span class="icon icon-wrench"></span>
					Settings
				</a>
				
				<a href="/admin/accounts">
					<span class="icon icon-group"></span>
					Accounts
				</a>
				
				<a href="/admin/syslog">
					<span class="icon icon-file"></span>
					Syslog
				</a>
				
				<a href="/admin/database/stats">
					<span class="icon icon-hdd"></span>
					Database
				</a>
				
				<a href="/admin/updates">
					<span class="icon icon-download"></span>
					Updates
				</a>
				
				<a href="/admin/changelog">
					<span class="icon icon-tasks"></span>
					What's New?
				</a>
			</div>
		</div>
		<?php
	}
}

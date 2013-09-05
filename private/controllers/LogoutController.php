<?php

class LogoutController extends BTController {
	public function indexAction() {	
		if(isset($_COOKIE['user_inject'])) {
			setcookie("user_inject",'',time() - (60*60*24),"/",$_SERVER['HTTP_HOST']);
			
			BTAuth::require_user();
	
			if(BTAuth::authUser()->isAdmin()) {
				if(BTAuth::user()->id() != BTAuth::authUser()->id()) { //if in a "view as" session
					header('Location: /admin/accounts');
					BTApp::end();
				}
			}
		}
		
		$redir_url = '/';		
		BTAuth::set_auth_cookie('',time() - 3600);
				
		header('location: '.$redir_url);
	}
	
	public function ajaxKillSessionAction() {
		BTAuth::set_auth_cookie('',time() - 3600);
	}
}
<?php

loadController('AdminController');

class AdminUpdatesController extends AdminController {
	public function indexAction() {
		$this->setVar("title","Updates");
		
		$this->render("admin/updates/index");
	}
	
	public function updateRedirectAction() {
		require_once(BT_ROOT . '/private/crons/download_new_orgs.php');
	}
}
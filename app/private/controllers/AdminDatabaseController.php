<?php

loadController('AdminController');

class AdminDatabaseController extends AdminController {
	public function __construct() {
		$this->loadModel("DatabaseStatsModel");
	}

	public function indexAction() {
	}
	
	public function ajaxAction($command = '',$params = array()) {			
		switch($command) {
			case 'json_database_stats':		 
				$model = new DatabaseStatsModel();

				$row_cnt = $model->getDatabaseRowCount();
				$size = $model->getDatabaseSize();

				echo json_encode(array('cnt'=>$row_cnt,'size'=>$size));
				break;
		}
	}
	
	public function statsAction() {
		$model = new DatabaseStatsModel();
			
		$this->setVar("title","Database Stats");
		
		$this->render("admin/database/stats");
	}
	
	public function clearDataAction() {
		BTApp::importModel("ClickModel");
		BTApp::importModel("ClickSiteModel");
		BTApp::importModel("ClickAdvancedModel");
		BTApp::importModel("ClickPassthroughModel");
		
		ClickSiteModel::model()->deleteOldData();
		ClickAdvancedModel::model()->deleteOldData();
		ClickModel::model()->deleteOldData();
		ClickPassthroughModel::model()->deleteOldData();
	}
}

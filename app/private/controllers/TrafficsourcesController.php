<?php

class TrafficSourcesController extends BTUserController {
	public function __construct() {
		parent::__construct();
		
		$this->loadModel("TrafficSourceModel");
	}

	public function indexAction() {		
		if(isset($_POST['traffic_source_id']) && $_POST['traffic_source_id']) {
			$source = TrafficSourceModel::model()->getRowFromPk($_POST['traffic_source_id']);
		
			$source->name = $_POST['name'];
			$source->useRuleSet('edit');
			
			if(!$source->save()) {
				$this->setVar('error',$source->getErrors());
			}
			else {
				$this->setVar('success','Traffic source saved');
			}
		}
		else if(isset($_POST['name'])) {
			
			$source = TrafficSourceModel::model();
			$source->name = $_POST['name'];
			$source->useRuleSet('new');
			
			if(!$source->save()) {
				$this->setVar('error',$source->getErrors());
			}
			else {
				$this->setVar('success','Traffic source added');
			}
		}
		
		if(isset($_GET['id'])) {
			$source = TrafficSourceModel::model()->getRowFromPk($_GET['id']);
		}
		else {
			$source = TrafficSourceModel::model();			
		}
		
		$sources = TrafficSourceModel::model()->getRows();
		
		$this->setVar("title","Traffic Sources");
		
		$this->setVar('source',$source);
		$this->setVar('traffic_sources',$sources);
		$this->render("trafficsources/index");
	}
	
	public function deleteAction() {
		$id = $_GET['id'];
		
		$source = TrafficSourceModel::model()->getRowFromPk($id);
		
		if($source) {
			$source->delete();
		}
		
		header("Location: /trafficsources");
		BTApp::end();
	}
}
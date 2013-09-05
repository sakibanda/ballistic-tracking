<?php

class LandingpagesController extends BTUserController {
	public function __construct() {
		$this->loadModel("LandingPageModel");
	}

	public function indexAction() {		
		if(isset($_POST['name'])) {
			if(!($lp = LandingPageModel::model()->getRow(
					array(
						'conditions'=>array(
							'landing_page_id'=>$_POST['landing_page_id']
						)
					)
				))) {
				$lp = LandingPageModel::model();
				$lp->useRuleSet('new');
			}
			else {
				$lp->useRuleSet('edit');
			}

			$lp->name = $_POST['name'];
			$lp->url = $_POST['url'];
			
			if(!$lp->save()) {
				$this->setVar('error',$lp->getErrors());
			}
		}
		
		$this->setVar("title","Setup Landing Pages");
		$this->setVar("lp_data",LandingPageModel::model()->getRows());
		
		$this->render("landingpages/index");
	}
	
	public function deleteAction() {
		if(!isset($_GET['id'])) {
			$this->redirect();
		}
		
		$landing_page_id = $_GET['id'];
		
		$lp = LandingPageModel::model()->getRow(
			array(
				'conditions'=>array(
					'landing_page_id'=>$landing_page_id
				)
			)
		);
		
		if(!$lp) {
			$this->redirect();
		}
		
		$lp->delete();
		
		$this->redirect();
	}
}
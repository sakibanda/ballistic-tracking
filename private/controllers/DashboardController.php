<?php

class DashboardController extends BTUserController {
	public function indexAction() {			
		$this->loadModel("ClickModel");
		$model = new ClickModel();
				
		$top_stats = $model->dashboardTopStats(getUserId());
		
		$this->setVar("title","Dashboard");
		
		$this->setVar("top_stats",$top_stats);
		$this->render("dashboard/dashboard");
	}
}

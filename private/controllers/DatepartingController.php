<?php

class DatePartingController extends BTUserController {
	public function indexAction() {
		$this->useActionAsCurrentNav();
		
		$this->setVar("title", "Date Parting");
		$this->render("dateparting/date");
	}
	
	public function viewAction() {
		$this->loadView("dateparting/view");
	}
	
	public function dataDayAction() {
		dayparting_data();
	}
	
	public function dataWeekAction() {		
		weekparting_data();
	}
}
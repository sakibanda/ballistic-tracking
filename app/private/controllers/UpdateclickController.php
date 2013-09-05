<?php

class UpdateclickController extends BTUserController {
	public function __construct() {
		$this->loadModel("ClickModel");
	}
	
	public function indexAction() {
		$success = false;
	
		if(isset($_POST['clickids'])) {
			if($clickids = $_POST['clickids']) {
				$clickids = trim($clickids); 
				$clickids = explode("\n",$clickids);
				$clickids = str_replace("\r",'',$clickids);
				
				foreach($clickids as $sid) {
					$click = ClickModel::model()->getRow(
						array('conditions'=>
							array(
								'click_id'=>  base_convert($sid,36,10)
							)
						)
					);
									
					if($click) {
						if($_POST['update_type'] == 1) {
							$click->convert(1);
						}
						else {
							$click->clearConversion();
						}
					}
				}
				
				$_POST['clickids'] = '';
				
				$success = true;
			}
		}
		else {
			$_POST['clickids'] = '';
		}
		
		$this->setVar("clickids", $_POST['clickids']);
		$this->setVar("title","Update Clicks");
		$this->setVar("success",$success);
		
		$this->render("updateclick/index");
	}
}
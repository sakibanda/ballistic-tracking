<?php 

class SpendingController extends BtUserController {
	public function __construct() {
		$this->loadModel('TrafficSourceModel');
		$this->loadModel('SpendingModel');
	}
	
	public function indexAction() {
		$success = false;
		$error = array();
		
		if(isset($_POST['amount'])) {
			$spend = SpendingModel::model();
			$spend->date = date('Y-m-d',strtotime($_POST['date']));
			$spend->campaign_id = $_POST['campaign_id'];
			$spend->amount = $_POST['amount'];
						
			$spend->useRuleSet('new');
			if($spend->save()) {
				$success = "Spend saved";
			}
			else {
				$error = $spend->getErrors();
			}
		}
		
		$campaigns = CampaignModel::model()->getRows(
			array(
				'order'=>'`campaign_id` ASC'
			)
		);
	
		$this->setVar("campaigns",$campaigns);
		
		$this->setVar("title","Update Spending");
		$this->setVar("error",$error);
		$this->setVar("success",$success);
		
		$this->render("spending/spending");
	}
	
	public function ajaxAction($command = '',$params = array()) {	
		switch($command) {
			case 'data_spending':
				$start = getArrayVar($_GET,'iDisplayStart',0);
				$limit = getArrayVar($_GET,'iDisplayLength',10);
				
				$total_spends = SpendingModel::model()->count();
				$spends = SpendingModel::model()->getRows(array('order'=>' date desc, campaign_id asc '));
								
				$sEcho = $_GET['sEcho'];
				$data = array('sEcho'=>(int)$sEcho,
					'iTotalRecords'=>(int)$total_spends,
					'iTotalDisplayRecords'=>(int)$total_spends,
					'aaData'=>array());
					
				foreach($spends as $spend) {
					$arr = array();
												
					$arr[] = $spend->date;
					$arr[] = $spend->campaign_id . ' - ' . $spend->campaign->name;
					$arr[] = $spend->amount;
					$arr[] = '<input type="hidden" class="spending_id" value="' . $spend->spending_id . '" /> <a href="#" class="delete_spend button small grey tooltip"><i class="icon-remove"></i></a>';
				
					$data['aaData'][] = $arr;
				}
				
				echo json_encode($data);
				
				break;
		}
	}
	
	public function postDeleteAction() {
		$id = $_POST['spending_id'];
		
		$spend = SpendingModel::model()->getRowFromPk($id);
		
		if($spend) {
			if($spend->delete()) {
				echo 1;
			}
		}
		
		return 1;
	}
}
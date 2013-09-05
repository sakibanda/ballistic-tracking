<?php

class ProfileController extends BTUserController {
	public function indexAction() {
		$error = array();
		$success = array();
				
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {								
			DB::startTransaction();
			
			BTAuth::user()->email = $_POST['email'];
			BTAuth::user()->timezone = $_POST['timezone'];
			BTAuth::user()->useRuleSet('user_profile');
								
			if(!(BTAuth::user()->save())) {
				$error = BTAuth::user()->getErrors();
				DB::rollback();
			}
			else {						
				$success[] = "Profile updated!";
			}
		
			if($_POST['old_pass']) {
				if (!$error) {
					BTAuth::user()->plain_pass = $_POST['pass'];
					BTAuth::user()->old_pass = $_POST['old_pass'];
					BTAuth::user()->pass = $_POST['pass'];
					BTAuth::user()->pass_confirm = $_POST['pass_confirm'];
					BTAuth::user()->useRuleSet('user_profile_password');
					
					if(!BTAuth::user()->save()) {
						$error = BTAuth::user()->getErrors();
						DB::rollback();
						$success = array();
					}
					else {
						$success[] = "Password Saved";
					}
				}
			}
			
			DB::commit();
		}
		
		$this->setVar("title","Your Profile");
		$this->setVar("error",$error);
		$this->setVar("success",$success);
		
		$this->render("profile/account");
	}
	
	public function postPrefsAction() {
		$error = array();
		
		$mysql['campaign_type'] = @$_POST['campaign_type'];  
		$mysql['click_filter'] = @$_POST['click_filter'];
		
		$mysql['user_mobile_breakdown_1'] = @$_POST['user_mobile_breakdown_1'];
		$mysql['user_mobile_breakdown_2'] = @$_POST['user_mobile_breakdown_2'];
		$mysql['user_mobile_breakdown_3'] = @$_POST['user_mobile_breakdown_3'];
		$mysql['user_mobile_breakdown_4'] = @$_POST['user_mobile_breakdown_4'];
		
		
		$mysql['traffic_source_id'] = @$_POST['traffic_source_id'];
		$mysql['breakdown'] = @$_POST['breakdown'];
		$mysql['campaign_id'] = @$_POST['campaign_id'];
				
		//predefined timelimit set, set the options
		if (@$_POST['time_predefined'] != '') {
			switch(@$_POST['time_predefined']) {
				case 'today';
				case 'yesterday';
				case 'last7';
		        case 'last14';
				case 'last30';
				case 'thismonth';
				case 'lastmonth';
		        case 'thisyear';
				case 'lastyear';
				case 'alltime';
				$clean['time_predefined'] = @$_POST['time_predefined'];
				break;               
		    }
		    
			if (!isset($clean['time_predefined'])) { $error['time_predefined'] = '<div class="error">You choose an incorrect time user_preference</div>'; echo $error['time_predefined']; }
		    
		} else {
			$from = explode(' ', @$_POST['from']); 
			$from = explode('/', $from[0]); 
		     $from_month = trim($from[0]);
			$from_day = trim($from[1]);
			$from_year = trim($from[2]);
			
			$to = explode(' ', @$_POST['to']); 
		    $to = explode('/', $to[0]); 
		    $to_month = trim($to[0]);
		    $to_day = trim($to[1]);
		    $to_year = trim($to[2]);
		    
		    
		    //if from or to, validate, and if validated, set it accordingly
			if (($from != '') and ((checkdate($from_month, $from_day, $from_year) == false))) {
				$error['date'] = '<div class="error">Wrong date format, you must use the following time format:   <strong>mm/dd/yyyy</strong></div>';     
				echo $error['date'];
			} else {
				$clean['time_from'] = mktime(0,0,0,$from_month,$from_day,$from_year);
			}                                                                                                                    
				
			if (($to != '') and ((checkdate($to_month, $to_day, $to_year) == false))) {
				$error['date'] = '<div class="error">Wrong date format, you must use the following time format:   <strong>mm/dd/yyyy</strong></div>';   
				echo $error['date'];
			} else {
				$clean['time_to'] = mktime(23,59,59,$to_month,$to_day,$to_year);  
		    }
		  
		    if(!$error && ($clean['time_to'] < $clean['time_from'])) {
			    $error['date'] = '<div class="error">The end date must be <strong>after</strong> the start date.</div>';
				echo $error['date'];
		    }
		}
		
		if (!$error) {
		    
			$mysql['time_predefined'] = getArrayVar($clean,'time_predefined');
			$mysql['time_from'] = getArrayVar($clean,'time_from');
			$mysql['time_to'] = getArrayVar($clean,'time_to');
			
			$fields = getReportFieldsForPage($_POST['opt_setting']);
			
			DB::startTransaction();
			
			//Only add fields that should be on this page, so we don't overwrite other options
			foreach($fields as $field=>$val) {
				BTAuth::user()->setPref($field,$mysql[$field]);
			}
			
			DB::commit();
		}
	}
	
	public function dataGetLoginLogsAction() {
		$sEcho = $_GET['sEcho'];

		$start = (int)$_GET['iDisplayStart'];
		$limit = (int)$_GET['iDisplayLength'];
		
		$total = BTAuth::user()->countLoginLogs();
		$logs = BTAuth::user()->getLoginLogs($limit,$start);
		
		$data = array('sEcho'=>(int)$sEcho,
			'iTotalRecords'=>$total,
			'iTotalDisplayRecords'=>$total,
			'aaData'=>array());
		
		foreach($logs as $log) {
			$row = array();
			
			if($log['success']) {
				$row[] = "Success";
			}
			else {
				$row[] = "<strong style='color: #ff0000;'>Failure</strong>";
			}
			
			$row[] = $log['time'];
			$row[] = $log['ip_address'];
			
			$data['aaData'][] = $row;
		}
	
		echo json_encode($data);
	}
}
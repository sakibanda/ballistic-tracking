<?php

class ApiController extends BTController {
	public function __construct() {
		parent::__construct();
		
		if(!bt_cloaker_enabled()) {
			echo 0;
			BTApp::end();
		}
		
		$this->loadModel('CloakerModel');
		$this->loadModel('CloakerOptionModel');
		$this->loadModel('CampaignModel');
		$this->loadModel("ClickAdvancedModel");
		
		require_once(BT_ROOT . '/private/includes/cloaker.php');
		
		loadController("TrackerController");
	}
	
	public function indexAction() {
		BTApp::end();
	}
	
	public function cloakerAction() {				
		if(!($ip = $_POST['ip'])) {
			echo '0';
			exit;
		}
						
		if(!($slug = $_POST['slug'])) {
			echo '0';
			exit;
		}

        $row = DB::getRow("select * from bt_u_campaigns camp left join bt_u_cloakers cloak on cloak.cloaker_id=camp.cloaker_id
where ((cloak.slug > '' and concat('/',cloak.slug,'/',camp.slug)='" . DB::quote($slug) . "')
or (cloak.slug = '' and concat('/',camp.slug)='" . DB::quote($slug) . "'))");
				
		$campaign = CampaignModel::model()->getRowFromPk($row['campaign_id']);
		$cloaker = $campaign->cloaker;
		
		if(!$campaign) {
			echo '0';
			exit;
		}
				
		$ip_id = INDEXES::get_ip_id($ip);
	
		$referer = (isset($_POST['referer'])) ? $_POST['referer'] : '';
		$user_agent = (isset($_POST['user_agent'])) ? $_POST['user_agent'] : '';
		$hostname = gethostbyaddr($ip);
		
		$_SERVER['REMOTE_ADDR'] = $ip;
		$_SERVER['HTTP_REFERER'] = $referer;
		$_SERVER['HTTP_USER_AGENT'] = $user_agent;
		
		$_GET = array();
		parse_str($_POST['query'],$_GET);
		$paused_redir = false;
				
		if($campaign->option('advanced_redirect_status')->value) {
			$num_prev_visits = ClickAdvancedModel::getNumPreviousClicks($campaign->id(),$ip_id);
			
			$options = $cloaker->options;
			
			foreach($options as $opt) {
				$opts[$opt->name] = $opt->value;
			}
			
			$opts = array_merge(CloakerOptionModel::defaultOptions(),$opts);

			$url = '';
			
			//Check 1: blank referer?
			/*if(!trim($referer)) {
				$url = $opts['exclude_url'];
			}*/
			
			$ip = DB::quote(ip2long($ip));
			$referer = DB::quote($referer);
			$user_agent = DB::quote($user_agent);
			$hostname = DB::quote($hostname);
			$cloaker_id = DB::quote($cloaker->cloaker_id);
			
			if(!$url) {
				if(!($row = DB::getRow("select url from bt_u_cloaker_ips where cloaker_id='$cloaker_id' and ip_from <= '$ip' and ip_to >= '$ip'",null))) {
					if(!($row = DB::getRow("select url from bt_u_cloaker_referers where cloaker_id='$cloaker_id' and '$referer' REGEXP referer",null))) {
						if(!($row = DB::getRow("select url from bt_u_cloaker_hostnames where cloaker_id='$cloaker_id' and '$hostname' REGEXP hostname",null))) {
							$row = DB::getRow("select url from bt_u_cloaker_user_agents where cloaker_id='$cloaker_id' and '$user_agent' REGEXP user_agent",null);
						}
					}
				}
			}
			
			/******** ORG CHECK ***********/
			// We always check the server too, in case the visitor is a known super-bad guy who should be
			// avoided, at all costs. 
			if(!isset($opts['organizations'])) {
				$opts['organizations'] = '[]';
			}
	
			$orgs_data = json_decode($opts['organizations']);
			$orgs = array();
				
			    foreach($orgs_data as $data) {
				    if(isset($data[0])){
                        $orgs[] = $data[0];
                    }
			}
	
			//CHECK BALLISTIC API SERVER
			$payload = array(
				'ip' => getArrayVar($_POST,'ip'),
				'user_agent' => getArrayVar($_POST,'user_agent'),
				'orgs' => implode(',',$orgs),
				'has_referer' => ($_SERVER['HTTP_REFERER']) ? '1' : '0',
				'version' => '1.0'
			);
	
			$ch = curl_init(API_SERVER . '/check.php?' . http_build_query($payload));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$ret = curl_exec($ch);
			
			$org_url = '';
	
			if($ret != 0) {
				foreach($orgs_data as $data) {
					if($data[0] == $ret) {
						$org_url = $data[1];
					}
				}
	
				if(!$org_url) {
					$org_url = $opts['exclude_url'];
				}
			}
			/****** END ORG CHECK *********/
				
			if(!$url) {
				if(!$row) {
					//only use org url if it did not match any filtets locally
					$url = $org_url;
				}
				else { // found local match
					$url = ($row['url']) ? $row['url'] : $opts['exclude_url'];
				}
			}
					
			//handle expiration first
			if((!$url) && ($campaign->option('adv_redir_clicks')->value < $opts['expiration'])) {
				$is_cloaked = 1;
				$url = $opts['exclude_url'];
			}
			elseif((!$url) && ($opts['clickfrequency'] && ($num_prev_visits >= $opts['clickfrequency']))) {
				$is_cloaked = 1;
				$url = $opts['exclude_url'];
			}
			elseif(!isset($url) || !$url) { //redirect to offer, no cloak
				$is_cloaked = 0;
				$url = '';
			}
			else {
				$is_cloaked = 1;
			}
			
			//Uptick click count, regardless of cloak status
			$clicks = $campaign->option('adv_redir_clicks');
			$clicks->value = $clicks->value + 1;
			$clicks->save();
		}
		else {		
			$options = $cloaker->options;
			
			foreach($options as $opt) {
				$opts[$opt->name] = $opt->value;
			}
			
			$opts = array_merge(CloakerOptionModel::defaultOptions(),$opts);
			
			//if the adv. redirect is paused
			$is_cloaked = 0;
			$url = $opts['exclude_url'];
			$paused_redir = true;
		}
		
		//save click data, this will also set the clickid cookie :)
		$tracker_controller = new TrackerController();
		$url = $tracker_controller->saveData($campaign,$is_cloaked,$url);
		
		if(!$is_cloaked && !$paused_redir) {
			//record uncloaked (normal) visitors. First we redirect through the tracking system
			$click_id = $_COOKIE['btclickid'];
			$type = $campaign->option('redirect_method')->value;
			
			echo getBTUrl() . "/tracker/advRedirect/?click_id=$click_id&t=$type";
		}
		else if(!$is_cloaked && $paused_redir) {
			echo $url;
		}
		else {
			echo $url;
		}
				
		exit;
	}
}
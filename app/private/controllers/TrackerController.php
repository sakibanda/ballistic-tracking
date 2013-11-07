<?php

class TrackerController extends BTController {
	public function __construct() {
		parent::__construct();
		
		$this->loadModel('ClickModel');
		$this->loadModel('ClickAdvancedModel');
		$this->loadModel('ClickSiteModel');
		$this->loadModel('CloakerModel');
		$this->loadModel('CampaignModel');
		$this->loadModel('CampaignOptionModel');
		$this->loadModel('LandingPageModel');
		$this->loadModel('OfferModel');
		$this->loadModel('CampaignOfferModel');
		$this->loadModel('ClickPassthroughModel');
		$this->loadModel('TrafficSourceModel');
		$this->loadModel('AffNetworkModel');
		$this->loadModel('CampaignLPModel');
		
		require_once(BT_ROOT . '/private/includes/traffic/link.php');
		require_once(BT_ROOT . '/private/includes/traffic/variables.php');
		
		$this->loadModel('OfferModel');
		
		//$_SERVER['REMOTE_ADDR'] = long2ip(mt_rand(10000000,40000000));
	}
	
	public function redirectAction() {
		$url = getArrayVar($_GET,'url'); //final destination url
		$type = getArrayVar($_GET, 't'); //redirect type
		$count = getArrayVar($_GET, 'c'); //number of redrects so far
		
		switch($type) {
			case REDIRECT_TYPE_301:
				$this->redirect301($url);
				break;
			case REDIRECT_TYPE_302:
				$this->redirect302($url);
				break;
			case REDIRECT_TYPE_307:
				$this->redirect307($url);
				break;
			case REDIRECT_TYPE_DOUBLE_META:
				if(!$count) {
					$tmp = array(
						'url'=>$url,
						't'=>REDIRECT_TYPE_DOUBLE_META,
						'c'=>1
					);
					$real = "/tracker/redirect/?" . http_build_query($tmp);
					$this->redirectMeta($real);
				}
				elseif($count == 1) {
					$this->redirectMeta($url);
				}
				break;
			case REDIRECT_TYPE_JS:
				$this->redirectJs($url);
				break;
			case REDIRECT_TYPE_JSMETA:
				if(!$count) {
					$tmp = array(
						'url'=>$url,
						't'=>REDIRECT_TYPE_JSMETA,
						'c'=>1
					);
					$real = "/tracker/redirect/?" . http_build_query($tmp);
					$this->redirectJs($real);
				}
				elseif ($count == 1) {
					$this->redirectMeta($url);
				}
				break;
		}
		
		exit;
	}
	
	public function directAction($param = array()) {
		if(!$param) {
			BTApp::log("Direct Link: Invalid Tracker ID ",'direct',BT_SYSLOG_CRITICAL);
		}
		
		$btid = $param[0];
		
		$campaign = CampaignModel::model()->getRowFromPk($btid);
		
		if (!$campaign) { BTApp::log("Direct Link: Invalid Tracker: " . $btid,'direct',BT_SYSLOG_CRITICAL); } 
		
		$url = $this->saveData($campaign);
				
		$_GET = array();
		$_GET['url'] = $url;
		$_GET['c'] = 0;
		$_GET['t'] = $campaign->option('redirect_method')->value;
		
		//var_dump($_GET);
		//exit;
		
            $this->redirectAction();
		
		BTApp::end();
	}
	
	public function lpAction($param = array()) {
		if(!$param) {
			BTApp::log("LP Link: Invalid Tracker ID ",'direct',BT_SYSLOG_CRITICAL);
		}
		
		$btid = $param[0];
		
		$campaign = CampaignModel::model()->getRowFromPk($btid);
				
		if (!$campaign) { BTApp::log("LP Link: Invalid Tracker: " . $btid,'direct',BT_SYSLOG_CRITICAL); } 
				
		$url = $this->saveData($campaign);
		
		$_GET = array();
		$_GET['url'] = $url;
		$_GET['c'] = 0;
		$_GET['t'] = $campaign->option('redirect_method')->value;
		
		var_dump($_GET);
		//exit; Solved a problem with redirection-- Ticket146
		
		$this->redirectAction();
		
		BTApp::end();
	}
	
	public function advRedirectAction() {
		$click_id_public = $_GET['click_id'];
		$type = $_GET['t'];
		
		$click = ClickModel::model()->getRow(array('conditions'=>array('click_id'=>base_convert($click_id_public,36,10))));
		
		if(!$click) {
			echo 'Invalid data';
			BTApp::end();
		}
		
		$campaign = $click->campaign;
				
		setClickIdCookie(base_convert($click->click_id,10,36));
		
		if($click->landing_page_id) {
			$url = $click->site->landing_url;
			$pass_type = 'lp';
		}
		else {
			$url = $click->site->offer_url;
			$pass_type = 'offer';
		}
		
		$append = array();
		foreach($click->passthroughs as $pass) {			
			$opt = json_decode($campaign->options['pass_' . $pass->name]->value);
			
			if($opt->$pass_type) {
				$append[$pass->name] = $pass->value;
			}
		}
			
		$_GET = array();
		$_GET['url'] = appendQueryString($url,http_build_query($append));
		$_GET['c'] = 0;
		$_GET['t'] = $type;
				
		$this->redirectAction();
	}
	
	public function offerAction($params = array()) {
		$campaign_id = getArrayVar($params,0,0);
		$offer_id = getArrayVar($params,1,0);
		
		//just one param
		if(!$offer_id) {
			if(!($campoffer = CampaignOfferModel::model()->getRowFromPk($campaign_id))) {
				echo "Error 0: Invalid offer ID";
				exit;
			}
			
			$campaign_id = $campoffer->campaign_id;
			$offer_id = $campoffer->offer_id;
		}
		
		//two params
		if(!($campaign = CampaignModel::model()->getRowFromPk($campaign_id))) {
			echo "Error 1: Invalid offer ID";
			exit;
		}

		if(!($offer = OfferModel::model()->getRowFromPk($offer_id))) {
			echo "Error 3: Invalid offer ID";
			exit;
		}
							
		//no LP id set, direct link?
		if($campaign->type != 1) {
			echo "Error 2: Invalid LP ID";
			exit;
		}
						
		if($_COOKIE['btclickid']) {
			$click = ClickModel::model()->getRow(array('conditions'=>array('click_id'=>base_convert($_COOKIE['btclickid'],36,10))));
			
			if(!$click) {
				echo "Error 4: Invalid click";
				exit;
			}
			else if(!$click->landing_page_id) {
				echo "Error 8: Invalid click";
				exit;
			}
			else if($click->offer_id) {
				//new click (offer already set). Use old data
				
				$new_click = clone $click;
				$new_click->offer_id = 0;
				$new_click->filtered = 2; //always filter as repeat visitor
				$new_click->lead = 0;
				$new_click->useRuleSet('track');
				$new_click->save(false,true);
				$id = $new_click->id();
				
				$new_adv = clone $click->advanced;
				$new_adv->click_id = $id;
				$new_adv->save(false,true);
				
				$new_site = clone $click->site;
				$new_site->click_id = $id;
				$new_site->save(false,true);
				
				$passes = array();
				foreach($click->passthroughs as $pass) {
					$new_pass = clone $pass;
					$new_pass->click_id = $id;
					$new_pass->name = $pass->name;
					$new_pass->save(false,true);
					$passes[$new_pass->name] = $new_pass;
				}
				
				$click = $new_click;
				$click->addJoinedModel('advanced',$new_adv);
				$click->addJoinedModel('site',$new_site);
				$click->addJoinedModel('passthroughs',$passes);
				
				setClickIdCookie(base_convert($click->click_id,10,36));
			}
		}
		else { //if no cookie, generate new click
			$this->saveData($campaign,0,'');
			
			if(!$_COOKIE['btclickid']) {
				echo "Error 2: Database Error";
				exit;
			}
			
			$click = ClickModel::model()->getRow(array('conditions'=>array('click_id'=>base_convert($_COOKIE['btclickid'],36,10))));
		}
		
		if(!$click) {
			echo "Error 6: Invalid click";
			exit;
		}
		
		$data = $click->advanced->getAdvPlaceholderData();
		$data['clickid'] = base_convert($click->click_id,10,36);
		
		//set campaign id
		$click->offer_id = $offer->id();
		$click->payout = $offer->payout;
		$click->useRuleSet('lpoffer');
		$click->save();
		
		//set offer urls
		$redirect_url = $offer->url;
		$redirect_url = replaceTrackerPlaceholders($redirect_url,$data);
		
		$click->site->offer_url = $redirect_url;
		$click->site->useRuleSet('lpoffer');
		$click->site->save();
		
		$append = array();
		foreach($click->passthroughs as $pass) {			
			$opt = json_decode($campaign->options['pass_' . $pass->name]->value);
			
			if($opt->offer) {
				$append[$pass->name] = $pass->value;
			}
		}
				
		$_GET = array();
		$_GET['url'] = appendQueryString($redirect_url,http_build_query($append));
		$_GET['c'] = 0;
		$_GET['t'] = $campaign->options['redirect_method']->value;

		$this->redirectAction();
	}
	
	public function saveData($campaign,$cloaked = 0,$outbound_url = '') {
		$offer_id = 0;
		$landing_page_id = 0;
		
		if($campaign->type == 2) {
			$campoffer = rotateDirectCampaign($campaign);
								
			if(!$campoffer) { 
				BTApp::log("Direct Link: Invalid Offers For Tracker: " . $campaign->id(),'direct',BT_SYSLOG_CRITICAL); 
			}

			$payout = 0.00; //$campoffer->offer->payout;
			$offer_id = $campoffer->offer->id();
		}
		else if($campaign->type == 1) {
			$camplp = rotateLPCampaign($campaign);
			
			$payout = 0.00;
			
			$landing_page = $camplp->landing_page;
			$landing_page_id = $landing_page->id();
			
			if(!$landing_page) {
				BTApp::log("Landing Page: Invalid Landing Page ID: " . $landing_page_id . " For Tracker: " . $campaign->id(),'direct',BT_SYSLOG_CRITICAL);
			}
		}
		
		$ip_id = INDEXES::get_ip_id($_SERVER['REMOTE_ADDR']);
								
		$click = new ClickModel();
		$click->offer_id = $offer_id;
		$click->landing_page_id = $landing_page_id;
		$click->traffic_source_id = $campaign->traffic_source_id;
		$click->payout = $payout;
		$click->filtered = FILTER::startFilter($ip_id);
		$click->user_id = $campaign->user_id;
		$click->cloaked = $cloaked;
		$click->campaign_id = $campaign->id();
		$click->useRuleSet("track");
		$click->save();
						
		$vars = saveTrackingVariables($campaign);
				
		//if behind cloaker scripts, we use $_POST. Otherwise (normally) use HTTP_REFERER
		$referer = (isset($_POST['referer'])) ? $_POST['referer'] : getArrayVar($_SERVER,'HTTP_REFERER');
				
		$keyword = getArrayVar($_GET,$campaign->option('var_kw')->value);
		
		if(!$keyword) {
			$keyword = getArrayVar($_GET,'kw');
			
			if(!$keyword) {
				$keyword = getArrayVar($_GET,'keyword');
			}
		}
		
		$keyword_id = INDEXES::get_keyword_id($keyword); 
				
		$platform = INDEXES::get_platform_and_browser_id();
		
		$organization_id = 0;
		$geo_block_id = 0;
		$device_id = 0;
		
		require(BT_ROOT .'/private/includes/traffic/devices_detect_inc.php');
		
		$adv = new ClickAdvancedModel();
		$adv->click_id = $click->id();
		$adv->keyword_id = $keyword_id;
		$adv->ip_id = $ip_id;
		$adv->platform_id = $platform['platform'];
		$adv->browser_id = $platform['browser'];
		$adv->org_id = $organization_id;
		$adv->device_id = $device_id;
		$adv->v1_id = $vars['v1_id'];
		$adv->v2_id = $vars['v2_id'];
		$adv->v3_id = $vars['v3_id'];
		$adv->v4_id = $vars['v4_id'];
		$adv->location_id = $geo_block_id;
		$adv->campaign_id = $campaign->id();		
		$adv->useRuleSet('track');
		$adv->save();
						
		$data = $vars;
		$data['keyword'] = $keyword;
		$data['clickid'] = base_convert($click->click_id,10,36);
		
		if($offer_id) {
			if($outbound_url) {
				$redirect_url = $outbound_url;
			}
			else {
				$redirect_url = $campoffer->offer->url;
				$redirect_url = replaceTrackerPlaceholders($redirect_url,$data);
			}
			
			$landing_url = '';
		}
		else {
			$redirect_url = '';
			
			if($outbound_url) {
				$landing_url = $outbound_url;
			}
			else {
				$landing_url = $landing_page->url;
			}
		}
		
		//set the cookie
		setClickIdCookie(base_convert($click->click_id,10,36));
		
		$site = new ClickSiteModel();
		$site->click_id = $click->id();
		$site->referer_url = $referer;
		$site->referer_domain = getUrlDomain($referer);
		$site->offer_url = $redirect_url;
		$site->landing_url = $landing_url;
		$site->useRuleSet('track');
		$site->save();
		
		$pass_vars = array();
		$to_append = '';
		
		if($landing_page_id) {
			$type = 'lp';
		}
		else {
			$type='offer';
		}
		
		foreach($campaign->options as $option) {
			if(strpos($option->name,'pass_') === 0) {
				$var_name = substr($option->name,5);
				
				$val = getArrayVar($_GET,$var_name,'');
				
				$pass = new ClickPassthroughModel();
				$pass->click_id = $click->click_id;
				$pass->name = $var_name;
				$pass->value = $val;
				$pass->useRuleSet('track');
				$pass->save();
				
				$pass_vars[$var_name] = $pass;
			}
		}
		
		$to_append = http_build_query($this->getPassthroughsToAppend($campaign,$pass_vars,$type));
		
		if($offer_id) { //direct
			return appendQueryString($redirect_url,$to_append);
		}
		else { //lp			
			return appendQueryString($landing_url,$to_append);
		}
	}
	
	public function getPassthroughsToAppend($campaign,$pass_vars,$type) {
		$append = array();
		
		foreach($campaign->options as $option) {
			if(strpos($option->name,'pass_') === 0) {
				$opts = json_decode($option->value);
				$var_name = substr($option->name,5);
				
				if((int)$opts->$type) {
					$append[$var_name] = $pass_vars[$var_name]->value;
				}
			}
		}
		
		return $append;
	}
	
	public function redirectMeta($url) {
		//disallow browser caching
		header("Expires: Thu, 19 Nov 1981 08:52:00 GMT"); //Date in the past
		header("Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0"); //HTTP/1.1
		header("Pragma: no-cache");
		?><html>
		<head>
			<meta http-equiv="refresh" content="0;url=<?php echo $url; ?>">
		</head>
		<body>
		</body>
		</html><?php
	}

	public function redirectJs($url) {
		//disallow browser caching
		header("Expires: Thu, 19 Nov 1981 08:52:00 GMT"); //Date in the past
		header("Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0"); //HTTP/1.1
		header("Pragma: no-cache");
		?><html>
		<head>
			<title>Redirecting...</title>
			<script type='text/javascript'>
				document.location.href = "<?php echo $url; ?>";
			</script>
		</head>

		<body onLoad='document.location.href="<?php echo $url; ?>"; '>

		</body>
		</html><?php
	}

	public function redirect301($url) {
		header("HTTP/1.1 301 Moved Permanently");
		header("Location: $url");
		
		//disallow browser caching
		header("Expires: Thu, 19 Nov 1981 08:52:00 GMT"); //Date in the past
		header("Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0"); //HTTP/1.1
		header("Pragma: no-cache");
	}

	public function redirect302($url) {
		header("HTTP/1.1 302 Found");
		header("Location: $url");
		
		//disallow browser caching
		header("Expires: Thu, 19 Nov 1981 08:52:00 GMT"); //Date in the past
		header("Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0"); //HTTP/1.1
		header("Pragma: no-cache");
	}

	public function redirect307($url) {
		header("HTTP/1.1 307 Temporary Redirect");
		header("Location: $url");
		
		//disallow browser caching
		header("Expires: Thu, 19 Nov 1981 08:52:00 GMT"); //Date in the past
		header("Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0"); //HTTP/1.1
		header("Pragma: no-cache");
	}
}
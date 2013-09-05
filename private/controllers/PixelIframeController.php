<?php

class PixelIframeController extends BTController {
	public function __construct() {
		$this->loadModel("ClickModel");
		$this->loadModel("TrafficSourceModel");
	}
	
	public function indexAction() {
		//get the aff_camapaign_id
		$mysql['use_pixel_payout'] = 0;
		
		//see if it has the cookie in the campaign id, then the general match, then do whatever we can to grab SOMETHING to tie this lead to
		if ($_COOKIE['btclickid']) {
			$click_pid = $_COOKIE['btclickid'];
		} else  {
			//ok grab the last click from this ip_id
			$mysql['ip_address'] = DB::quote($_SERVER['REMOTE_ADDR']);
			$daysago = time() - 2592000; // 30 days ago
			$click_sql1 = "	SELECT 	bt_s_clicks.click_id
							FROM 		bt_s_clicks
							LEFT JOIN	bt_s_clicks_advanced USING (click_id)
							LEFT JOIN 	bt_s_ips USING (ip_id)
							WHERE 	bt_s_ips.ip_address='".$mysql['ip_address']."'
							AND		bt_s_clicks.time >= '".$daysago."'
							ORDER BY 	bt_s_clicks.click_id DESC
							LIMIT 		1";
		
			$click_row1 = DB::getRow($click_sql1);
		
			$click_pid = base_convert($click_row1['click_id'],10,36);
			$mysql['ad_account_id'] = DB::quote($click_row1['ad_account_id']);
		}
		
		$click = ClickModel::model()->getRow(array(
			'conditions'=>array(
				'click_id'=>  base_convert($click_pid, 36, 10)
			)
		));
		
		if(!$click) {
			BTApp::end();
		}
						
		if($click->get('ad_account_id')){			
			if (getArrayVar($_GET,'amount') && is_numeric($_GET['amount'])) {
				$mysql['use_pixel_payout'] = 1;
			}
			
			if($mysql['use_pixel_payout']==1) {
				$click->convert(0,$_GET['amount']);
			}
			else {
				$click->convert();
			}
			
			if($click->campaign->option('pixel_type')->value) {				
				$sql = "select v1.var_value as v1, v2.var_value as v2, v3.var_value as v3, v4.var_value as v4 from bt_s_clicks_advanced adv
					left join bt_s_variables v1 on (v1.var_id=adv.v1_id)
					left join bt_s_variables v2 on (v2.var_id=adv.v2_id)
					left join bt_s_variables v3 on (v3.var_id=adv.v3_id)
					left join bt_s_variables v4 on (v4.var_id=adv.v4_id)
					where adv.click_id=?";
				
				$st = DB::prepare($sql);
				$st->execute(array($click->id()));
				$row = $st->fetch();
									
				$data['v1'] = $row['v1'];
				$data['v2'] = $row['v2'];
				$data['v3'] = $row['v3'];
				$data['v4'] = $row['v4'];
				$data['clickid'] = $click->id();
				$data['keyword'] = '';
				$data['amount'] = $click->payout;
				
				$code = replaceTrackerPlaceholders($click->campaign->option('pixel_code')->value,$data);
				$code = str_replace('[[amount]]',$data['amount'],$code);
			
				switch ($click->campaign->option('pixel_type')->value) {
					case 1:
					case 2:
					case 3:
						echo $code;
						break;
					case 4:
						$ch = curl_init($code);
						curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 Postback-Bot v1.0');
						curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
						curl_exec($ch);
						break;
				}
			}
		}	
	}
}
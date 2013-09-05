<?php

class PixelPostbackController extends BTController {
	public function __construct() {
		$this->loadModel("ClickModel");
	}
	
	public function IndexAction() {
		if (!getArrayVar($_GET,'clickid')) BTApp::log("No SubID",'postback_pixel',BT_SYSLOG_CRITICAL);

		$click_pid = getArrayVar($_GET,'clickid');
	
		$mysql['click_id'] = DB::quote(base_convert($click_pid,36,10));
		$mysql['pixel_id'] = 0;
		$mysql['use_pixel_payout'] = 0;
		
		if ($click_pid) {
			if ($_GET['amount'] && is_numeric($_GET['amount'])) {
				$mysql['use_pixel_payout'] = 1;
				$mysql['payout'] = DB::quote($_GET['amount']);
			}
			
			$click = ClickModel::model()->getRow(array(
				'conditions'=>array(
					'click_id'=>$mysql['click_id']
				)
			));

			if(!$click) {
				BTApp::end();
			}
			
			if($mysql['use_pixel_payout']==1) {
				$click->convert(0,$mysql['payout']);
			}
			else {
				$click->convert();
			}
						
			if($click->campaign->option('pixel_type')->value == 4) {
				$data = array();
				
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
				
				$pb_url = replaceTrackerPlaceholders($click->campaign->option('pixel_code')->value,$data);
				$pb_url = str_replace('[[amount]]',$data['amount'],$pb_url);
				
				$ch = curl_init($pb_url);
				curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 Postback-Bot v1.0');
				curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
				curl_exec($ch);
			}
		}
	}
}
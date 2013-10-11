<?php

class ClickModel extends BTModel {

    public function tableName() {
		return 'bt_s_clicks';
	}
	
	public function pk() {
		return 'click_id';
	}
	
	public function rules() {
		return array(
			array('payout','optional',array('for'=>array('pixel','track','lpoffer'))),
			array('lead','required',array('for'=>array('pixel','clear'))),
			array('lead_manual','required',array('for'=>array('pixel','clear'))),
			array('lead_time','required',array('for'=>array('pixel','clear'))),
			array('lifetime','optional',array('for'=>array('pixel'))),
			
			array('offer_id','required',array('for'=>array('track','lpoffer'))),
			array('landing_page_id','required',array('for'=>array('track'))),
			array('traffic_source_id','required',array('for'=>array('track'))),
			array('filtered','required',array('for'=>array('track'))),
			array('user_id','required',array('for'=>array('track'))),
			array('cloaked','required',array('for'=>array('track'))),
			array('campaign_id','required',array('for'=>array('track')))
		);
	}
	
	public function relations() {
		return array(
			'advanced'=>array('ClickAdvancedModel','click_id',self::REL_ONE_ONE),
			'site'=>array('ClickSiteModel','click_id',self::REL_ONE_ONE),
			'passthroughs'=>array('ClickPassthroughModel','click_id',self::REL_ONE_MANY),
			'traffic_source'=>array('TrafficSourceModel','traffic_source_id',self::REL_ONE_ONE),
			'campaign'=>array('CampaignModel','campaign_id',self::REL_ONE_ONE)
		);
	}

	//returns an array with the count, and the click rows. 
	public function clickSpy($user_id,$start,$length) {
		$cnt = DB::getVar("select count(1) from bt_s_clicks click
			LEFT JOIN bt_u_campaigns camp ON (click.campaign_id = camp.campaign_id)
			LEFT JOIN bt_u_traffic_sources ts ON (ts.traffic_source_id = click.traffic_source_id)
			where ts.deleted=0
			and camp.deleted=0
		");
		
		$data = array("count"=>$cnt);
		
		$click_sql = "SELECT  click.click_id,
								click.time,
								ts.name as `ts.name`,
								ip_address,
								keyword,
								lead,
								filtered,
								landing_url as landing,
								click.cloaked,
								adv.browser_id,
								adv.platform_id,
								referer_url as referer_url_address,
								referer_domain as referer_domain_host,
								offer_url as redirect_url_address,
								camp.name
								
					  FROM      bt_s_clicks  AS click  
					  					LEFT JOIN bt_s_clicks_advanced adv USING (click_id)
										LEFT JOIN bt_s_clicks_site USING (click_id)
										LEFT JOIN bt_u_traffic_sources ts ON (ts.traffic_source_id = click.traffic_source_id)
										LEFT JOIN bt_s_ips ON (bt_s_ips.ip_id = adv.ip_id)
										LEFT JOIN bt_s_keywords ON (bt_s_keywords.keyword_id = adv.keyword_id)
										LEFT JOIN bt_u_campaigns camp ON (click.campaign_id = camp.campaign_id)
										
					  WHERE ts.deleted=0 and camp.deleted=0 order by click.click_id desc limit " . DB::quote($start) . ',' . DB::quote($length);		
				
		$click_rows = DB::getRows($click_sql);
				
		$data['click_rows'] = $click_rows;
		
		return $data;
	}
	
	public function dashboardTopStats($user_id) {	
		$top_stats = DB::getRow("select count(1) as clicks, sum(lead) as leads, sum(lead * payout) as income, coalesce(spend.cost,0) as cost, (sum(lead * payout) - coalesce(spend.cost,0)) as net from bt_s_clicks 
left join (select sum(amount) as cost from bt_u_spending where and deleted=0) spend on spend.cost > 0 ");
		
		return $top_stats;
	}
	
	public function clearConversion() {
		$this->lead_manual = 1;
		$this->lead = 0;
		$this->lead_time = time();
		$this->useRuleSet("pixel");
		
		return $this->save();
	}
	
	public function convert($manual = 0,$amount = 0) {
		require_once(BT_ROOT . '/private/includes/traffic/lifetime.php');
        $sql = 'SELECT c.allow_duplicate_conversion FROM  bt_u_campaigns as c WHERE c.campaign_id = '.$this->campaign_id;
        $result = DB::getRows($sql);
        $row = $result[0];

		//check amount first, so we dont overwrite the amount already in there.
		if($amount) {
            //$this->payout = $amount;
            if($row['allow_duplicate_conversion'] == 1){
                $this->payout += $amount;
            }else{
                $this->payout = $amount;
            }
		}
		$this->lead_manual = $manual;
		//$this->lead = 1;
        if($row['allow_duplicate_conversion'] == 1){
            $this->lead += 1;
        }else{
            $this->lead = 1;
        }
        $this->lead_time = time();
		$this->lifetime = getClickLifetimeInterval(time() - $this->time); //save lifetime interval
		$this->useRuleSet("pixel");

		return $this->save();
	}
	
	public function beforeSave() {
		parent::beforeSave();
		
		if($this->isNew()) {
			$this->time = time();
			
			BTApp::importModel("ClickCounterModel");
			$cnt = ClickCounterModel::model()->getRow();
			$cnt->inc();
			$this->click_id = $cnt->click_count;
		}
	}
	
	public function deleteOldData() {
		DB::query("truncate table bt_s_clicks");
	}
}
<?php

class ClickAdvancedModel extends BTModel {
	public function tableName() {
		return 'bt_s_clicks_advanced';
	}
	
	public function pk() {
		return 'click_id';
	}
	
	public function relations() {
		return array();
	}
	
	public function rules() {
		return array(
			array('click_id','required',array('for'=>array('track'))),
			array('keyword_id','optional',array('for'=>array('track'))),
			array('ip_id','optional',array('for'=>array('track'))),
			array('platform_id','optional',array('for'=>array('track'))),
			array('browser_id','optional',array('for'=>array('track'))),
			array('org_id','optional',array('for'=>array('track'))),
			array('device_id','optional',array('for'=>array('track'))),
			array('v1_id','optional',array('for'=>array('track'))),
			array('v2_id','optional',array('for'=>array('track'))),
			array('v3_id','optional',array('for'=>array('track'))),
			array('v4_id','optional',array('for'=>array('track'))),
			array('location_id','optional',array('for'=>array('track'))),
			array('campaign_id','optional',array('for'=>array('track')))
		);
	}
	
	public function getAdvPlaceholderData() {
		$st = DB::prepare("select var_id,var_value from bt_s_variables where var_id IN (?,?,?,?)");
	
		$st->execute(array(
			$this->v1_id,
			$this->v2_id,
			$this->v3_id,
			$this->v4_id
		));
		
		$rows = array();
		while($row = $st->fetch(PDO::FETCH_ASSOC)) {
			$rows[$row['var_id']] = $row['var_value'];
		}
		
		$data = array(
			'v1'=>$rows[$this->v1_id],
			'v2'=>$rows[$this->v2_id],
			'v3'=>$rows[$this->v3_id],
			'v4'=>$rows[$this->v4_id]
		);
		
		$st = DB::prepare("select keyword from bt_s_keywords where keyword_id=?");
		$st->execute(array($this->keyword_id));
		
		if($row = $st->fetch(PDO::FETCH_ASSOC)) {
			$data['keyword'] = $row['keyword'];
		}
		
		return $data;
	}
	
	public static function getNumPreviousClicks($campaign_id,$ip_id) {
		if(!is_numeric($campaign_id) || !(is_numeric($ip_id))) {
			return false;
		}
		
		return DB::getVar("select count(1) from bt_s_clicks_advanced left join bt_s_clicks using (click_id) where campaign_id='" . DB::quote($campaign_id) . "' and ip_id='" . DB::quote($ip_id) . "'");
	}
	
	public function deleteOldData() {
		DB::query("truncate table bt_s_clicks_advanced");
	}
}
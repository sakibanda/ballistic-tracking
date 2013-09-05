<?php

class CampaignLPModel extends BTModel {
	public function tableName() {
		return 'bt_u_campaign_lps';
	}
	
	public function pk() {
		return 'campaign_lp_id';
	}
	
	public function delete($bit = 0) {
		$this->_delete();
	}
	
	public function defaultValues() {
		return array(
			'weight'=>1	
		);
	}
	
	public function relations() {
		return array(
			'campaign'=>array('CampaignModel','campaign_id',self::REL_ONE_ONE),
			'landing_page'=>array('LandingPageModel','landing_page_id',self::REL_ONE_ONE)
		);
	}
	
	public function rules() {
		return array(
			array('campaign_id','required',array('message'=>'LP must have a campaign', 'for'=>array('new'))),
			array('landing_page_id','required',array('message'=>'LP must have an LP', 'for'=>array('new'))),
			array('weight','number',array('message'=>'Offer must have a weight', 'for'=>array('edit','new'))),
		);
	}

    public function duplicate($old_id,$campaign_id) {
        $campaign_id = DB::quote($campaign_id);
        $old_id = DB::quote($old_id);

        $query = "insert into " . $this->tableName() . " (campaign_id, landing_page_id, weight)";
        $query .= "select '$campaign_id', landing_page_id, weight from " . $this->tableName() . " where campaign_id='$old_id'";
        if(!DB::query($query)) {
            return false;
        }

        return true;
    }
}

?>
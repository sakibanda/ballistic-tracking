<?php

class TrafficSourceModel extends BTModel {		
	public function tableName() {
		return 'bt_u_traffic_sources';
	}
	
	public function pk() {
		return 'traffic_source_id';
	}
	
	public function deletedColumn() {
		return 'deleted';
	}
	
	public function relations() {
		return array(
			'user'=>array('UserModel','user_id',self::REL_ONE_ONE),
			'clicks'=>array('ClickModel','traffic_source_id',self::REL_ONE_MANY),
			'trackers'=>array('CampaignModel','traffic_source_id',self::REL_ONE_MANY),
			'spends'=>array('SpendingModel','traffic_source_id',self::REL_ONE_MANY)
		);
	}
	
	public function filters() {
		return array('deleted'=>0);
	}
	 
	public function delete($bit = 0) {
		if(!$bit || ($bit == DELETE_BIT_SELF)) {
			$this->deleted = 1;
			$this->useRuleSet('delete');
			$this->save();
			
			BTApp::importModel('CampaignModel');
			
			CampaignModel::model()->deleteAll(array('traffic_source_id'=>$this->id()),DELETE_BIT_TRAFFIC_SOURCE);
		}
				
		return true;
	}
	
	public function rules() {
		return array(
			array('name','required',array('message'=>'Please enter a traffic source name', 'for'=>array('new','edit'))),
			array('name','length',array('min'=>1,'max'=>50,'message'=>'Invalid traffic source name', 'for'=>array('new','edit'))),
			
			array('deleted','required',array('for'=>array('delete')))
		);
	}
	
	public function beforeSave() {
		parent::beforeSave();
		
		if($this->isNew()) {
			$this->user_id = getUserID();
		}
	}
}

?>
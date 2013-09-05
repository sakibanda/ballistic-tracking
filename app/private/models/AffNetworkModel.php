<?php

class AffNetworkModel extends BTModel {	
	public function tableName() {
		return 'bt_u_aff_networks';
	}
	
	public function pk() {
		return 'aff_network_id';
	}
	
	public function filters() {
		return array('deleted'=>0);
	}
	
	public function deletedColumn() {
		return "deleted";
	}
	
	public function delete($flag = 0) {
		
		if(!$flag || $flag == DELETE_BIT_SELF) {
			return parent::delete(1);
		}
		
		return false;
	}
	
	public function relations() {
		return array(
			'offers'=>array('OfferModel','aff_network_id',self::REL_ONE_MANY)
		);
	}
	
	public function rules() {
		return array(
			array('name','required',array('message'=>'Please enter a network name', 'for'=>array('edit','new'))),
			array('name','length',array('min'=>1,'max'=>50,'message'=>'Invalid network name', 'for'=>array('edit','new')))
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
<?php

class CloakerIpModel extends BTModel {	
	public function tableName() {
		return 'bt_u_cloaker_ips';
	}
	
	public function pk() {
		return array('cloaker_id'); //yes, it is actually a composite PK. But we will ONLY be searching by cloaker ID. 
	}
	
	public function rules() {
		return array(
			array('ip_from','required',array('message'=>'Invalid IP','for'=>array('new'))),
			array('ip_to','optional',array('for'=>array('new'))),
			array('url','optional',array('for'=>array('new'))),
			array('memo','optional',array('for'=>array('new'))),
			array('cloaker_id','required',array('for'=>array('new')))
		);
	}
	
	public function delete($flag = 0) {
		$this->_delete(); //this will delete ALL entries for the cloaker ID in one go. Check the above PK function. 
	}
	
	public function beforeSave() {
		parent::beforeSave();
		
		$this->ip_from = ip2long($this->ip_from);
		$this->ip_to = ip2long($this->ip_to);
	}
	
	public function afterSave() {
		parent::afterSave();
		
		$this->ip_from = long2ip($this->ip_from);
		$this->ip_to = long2ip($this->ip_to);
	}
	
	public function afterDataSet() {
		parent::afterDataSet();
		
		$this->ip_from = long2ip($this->ip_from);
		$this->ip_to = long2ip($this->ip_to);
	}
	
	public function duplicate($old_id,$cloaker_id) {	
		$cloaker_id = DB::quote($cloaker_id);
		$old_id = DB::quote($old_id);
		
		if(!DB::query("insert into " . $this->tableName() . " (cloaker_id, ip_from, ip_to, url, memo) select '$cloaker_id', ip_from, ip_to, url, memo from " . $this->tableName() . " where cloaker_id='$old_id'")) {
			return false;
		}
		
		return true;
	}
}
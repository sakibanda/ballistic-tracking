<?php

class CloakerUaModel extends BTModel {	
	public function tableName() {
		return 'bt_u_cloaker_user_agents';
	}
	
	public function pk() {
		return array('cloaker_id'); //yes, it is actually a composite PK. But we will ONLY be searching by cloaker ID. 
	}
	
	public function rules() {
		return array(
			array('user_agent','required',array('message'=>'Invalid User Agent','for'=>array('new'))),
			array('url','optional',array('for'=>array('new'))),
			array('memo','optional',array('for'=>array('new'))),
			array('cloaker_id','required',array('for'=>array('new'))),
			array('regex','optional',array('for'=>array('new')))
		);
	}
	
	public function delete($flag = 0) {
		$this->_delete(); //this will delete ALL entries for the cloaker ID in one go. Check the above PK function. 
	}
	
	public function beforeSave() {
		parent::beforeSave();
		
		if(!$this->regex) {
			$this->user_agent = '^' . str_replace("\\*", ".*", preg_quote($this->user_agent)) . '$';
		}
	}
	
	public function afterSave() {
		parent::afterSave();
		
		if(!$this->regex) {
			$this->user_agent = substr(str_replace(".*", "*", stripslashes($this->user_agent)),1,-1);
		}
	}
	
	public function afterDataSet() {
		parent::afterDataSet();
		
		if(!$this->regex) {
			$this->user_agent = substr(str_replace(".*", "*", stripslashes($this->user_agent)),1,-1);
		}
	}
	
	public function duplicate($old_id,$cloaker_id) {	
		$cloaker_id = DB::quote($cloaker_id);
		$old_id = DB::quote($old_id);
		
		if(!DB::query("insert into " . $this->tableName() . " (cloaker_id, user_agent, url, memo, regex) select '$cloaker_id', user_agent, url, memo, regex from " . $this->tableName() . " where cloaker_id='$old_id'")) {
			return false;
		}
		
		return true;
	}
}
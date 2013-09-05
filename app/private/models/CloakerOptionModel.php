<?php

class CloakerOptionModel extends BTModel {	
	protected static $_defaultOpts = array(
		'clickfrequency' => 1,
		'exclude_url' => '',
		'expiration' => 0,
		'organizations' => '[""]',
		'redirect' => 31
	);
	
	public function tableName() {
		return 'bt_u_cloaker_options';
	}
	
	public function pk() {
		return array('cloaker_id','name');
	}
	
	public function rules() {
		return array(
			array('name','required',array('message'=>'Please enter a name','for'=>array('new'))),
			array('name','length',array('min'=>1,'max'=>50,'message'=>'Invalid name','for'=>array('new'))),
			array('value','length',array('min'=>0,'max'=>16000,'message'=>'Invalid value','for'=>array('new','edit'))),
			array('cloaker_id','required',array('for'=>array('new')))
		);
	}
	
	public static function defaultOptions() {
		return self::$_defaultOpts;
	}
	
	public function delete($flag = 0) {
		$this->_delete();
	}
	
	//Set default value before save
	public function beforeSave() {
		parent::beforeSave();
		
		$default = getArrayVar(self::$_defaultOpts, $this->name);
		
		if($this->value == null) {
			$this->value = $default;
		}
	}
	
	//set default value before datra load
	public function afterDataSet() {
		parent::afterDataSet();
		
		$default = getArrayVar(self::$_defaultOpts, $this->name);
		
		if($this->value == null) {
			$this->value = $default;
		}
	}
	
	public function duplicate($old_id,$cloaker_id) {	
		$cloaker_id = DB::quote($cloaker_id);
		$old_id = DB::quote($old_id);
		
		if(!DB::query("insert into " . $this->tableName() . " (cloaker_id, name, value) select '$cloaker_id', name, value from " . $this->tableName() . " where cloaker_id='$old_id'")) {
			return false;
		}
		
		return true;
	}
}
<?php

class LandingPageModel extends BTModel {	
	public function tableName() {
		return 'bt_u_landing_pages';
	}
	
	public function pk() {
		return 'landing_page_id';
	}
	
	public function filters() {
		return array('deleted'=>0);
	}
	
	public function deletedColumn() {
		return 'deleted';
	}
	
	public function rules() {
		return array(
			array('name','required',array('message'=>'Please enter a LP name', 'for'=>array('new','edit'))),
			array('name','length',array('min'=>1,'max'=>50,'message'=>'Invalid LP name', 'for'=>array('new','edit'))),
			array('url','required',array('message'=>'Enter a url','for'=>array('new','edit'))),
			array('url','url',array('message'=>"Invalid url",'for'=>array('new','edit')))
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
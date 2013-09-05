<?php

class ClickPassthroughModel extends BTModel {
	public function tableName() {
		return 'bt_s_clicks_passthrough';
	}
	
	public function pk() {
		return array('click_id','name');
	}
	
	public function rules() {
		return array(
			array('click_id','required',array('for'=>array('track'))),
			array('name','optional',array('for'=>array('track'))),
			array('value','optional',array('for'=>array('track')))
		);
	}
	
	public function deleteOldData() {
		DB::query("truncate table bt_s_clicks_passthrough");
	}
}
<?php

class ClickSiteModel extends BTModel {
	public function tableName() {
		return 'bt_s_clicks_site';
	}
	
	public function pk() {
		return 'click_id';
	}
	
	public function rules() {
		return array(
			array('click_id','required',array('for'=>array('track'))),
			array('referer_url','optional',array('for'=>array('track'))),
			array('referer_domain','optional',array('for'=>array('track'))),
			array('offer_url','optional',array('for'=>array('track','lpoffer'))),
			array('landing_url','optional',array('for'=>array('track')))
		);
	}
	
	public function deleteOldData() {
		DB::query("truncate table bt_s_clicks_site");
	}
}
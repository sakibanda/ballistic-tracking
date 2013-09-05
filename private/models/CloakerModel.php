<?php

class CloakerModel extends BTModel {	
	public function tableName() {
		return 'bt_u_cloakers';
	}
	
	public function pk() {
		return 'cloaker_id';
	}
	
	public function rules() {
		return array(
			array('name','required',array('message'=>'Please enter a name', 'for'=>array('new','edit'))),
			array('name','length',array('min'=>1,'max'=>50,'message'=>'Invalid name', 'for'=>array('new','edit'))),
			array('url','required',array('message'=>'URL is required','for'=>array('new','edit'))),
			array('url','url', array('message'=>'Invalid URL','for'=>array('new','edit')))
		);
	}	
	
	public function relations() {
		return array(
			'options'=>array('CloakerOptionModel','cloaker_id',self::REL_ONE_MANY),
			'hostnames'=>array('CloakerHostnameModel','cloaker_id',self::REL_ONE_MANY),
			'ips'=>array('CloakerIpModel','cloaker_id',self::REL_ONE_MANY),
			'referers'=>array('CloakerRefererModel','cloaker_id',self::REL_ONE_MANY),
			'user_agents'=>array('CloakerUaModel','cloaker_id',self::REL_ONE_MANY)
		);
	}
	
	public function delete($flag = 0) {
		if($this->options) {
			foreach($this->options as $opt) {
				$opt->delete();
			}
		}
		
		if($this->hostnames) {
			foreach($this->hostnames as $opt) {
				$opt->delete();
			}
		}
		
		if($this->ips) {
			foreach($this->ips as $opt) {
				$opt->delete();
			}
		}
		
		if($this->referers) {
			foreach($this->referers as $opt) {
				$opt->delete();
			}
		}
		
		if($this->user_agents) {
			foreach($this->user_agents as $opt) {
				$opt->delete();
			}
		}
		
		$this->_delete();
	}
	
	public function beforeSave() {
		parent::beforeSave();
		
		if($this->isNew()) {
			$this->user_id = getUserID();
		}
		
		$this->slug = trim($this->cloakerAPIPath(),'/');
	}
	
	public function duplicate($id) {
		DB::startTransaction();
				
		if(!DB::query("insert into " . $this->tableName() . " (name, user_id, url) select concat(name,' copy') as name, user_id, url from " . $this->tableName() . " where cloaker_id='" . DB::quote($id) . "'")) {
			DB::rollback();
			return false;
		}
		
		$new_id = DB::insertId();
		
		if(!$new_id) {
			DB::rollback();
			return false;
		}
		
		if(!CloakerOptionModel::model()->duplicate($id,$new_id)) {
			DB::rollback();
			return false;
		}
				
		if(!CloakerHostnameModel::model()->duplicate($id,$new_id)) {
			DB::rollback();
			return false;
		}
				
		if(!CloakerIpModel::model()->duplicate($id,$new_id)) {
			DB::rollback();
			return false;
		}
				
		if(!CloakerRefererModel::model()->duplicate($id,$new_id)) {
			DB::rollback();
			return false;
		}
				
		if(!CloakerUaModel::model()->duplicate($id,$new_id)) {
			DB::rollback();
			return false;
		}
		
		DB::commit();
		return $new_id;
	}
	
	public function cloakerAPIPath() {
		$path = parse_url($this->url,PHP_URL_PATH);
		
		return dirname($path);
	}
	
	public function getUrl() {
		$url = parse_url($this->url);
		
		return $url['scheme'] . '://' . $url['host'] . (($this->slug) ? '/' . $this->slug : '');
	}
}
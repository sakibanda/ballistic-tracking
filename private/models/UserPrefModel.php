<?php

class UserPrefModel extends BTModel {
	protected $_defaultPreferences = array(
			'click_filter'=>'all',
			'time_from'=>0,
			'time_to'=>0,
			'time_predefined'=>'today',
			'traffic_source_id'=>0,
			'campaign_type'=>'all',
			'user_mobile_breakdown_1'=>'',
			'user_mobile_breakdown_2'=>'',
			'user_mobile_breakdown_3'=>'',
			'user_mobile_breakdown_4'=>'',
			'breakdown'=>'day',
			'campaign_id'=>0
	);
	
	public function rules() {
		return array(
			array('user_id','required',array('message'=>"User ID required",'for'=>array('save'))),
			array('name','required',array('message'=>"Name required",'for'=>array('save'))),
			array('value','optional',array('for'=>array('save'))),
		);
	}
	
	public function tableName() {
		return 'bt_u_users_pref';
	}
	
	public function pk() {
		return array('user_id','name');
	}
	
	public function relations() {
		return array(
			'user'=>array('UserModel','user_id',self::REL_ONE_ONE)
		);
	}
		
	public function setDefaultPreferences($conditions,$prefs) {		
		foreach($this->_defaultPreferences as $name=>$value) {
			$found = false;
			foreach($prefs as $row) {
				if($row->name == $name) {
					$found = true;
					break;
				}
			}
			
			if(!$found) {
				$tmp = self::model();
				$tmp->user_id = $conditions['user_id'];
				$tmp->name = $name;
				$tmp->value = $value;

				$prefs[] = $tmp;
			}
		}
		
		return $prefs;
	}
}

?>
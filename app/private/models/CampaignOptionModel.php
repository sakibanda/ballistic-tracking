<?php

class CampaignOptionModel extends BTModel {	
	protected $_defaultPreferences = array(
		'redirect_method' => 31,
		'var_kw' => 'kw',
		'var_v1' => 'v1',
		'var_v2' => 'v2',
		'var_v3' => 'v3',
		'var_v4' => 'v4',
		'default_var_kw'=>'',
		'default_var_v1'=>'',
		'default_var_v2'=>'',
		'default_var_v3'=>'',
		'default_var_v4'=>'',
		'adv_redir_clicks'=>0,
		'advanced_redirect_status'=>1,
		'pixel_type'=>0,
		'pixel_code'=>''
	);
	
	public function delete($bit = 0) {
		$this->_delete();
	}
	
	public function tableName() {
		return 'bt_u_campaign_options';
	}
	
	public function pk() {
		return array('campaign_id','name');
	}
	
	public function rules() {
		return array(
			array('name','required',array('message'=>'Please enter a name','for'=>array('new'))),
			array('name','length',array('min'=>1,'max'=>45,'message'=>'Invalid name','for'=>array('new'))),
			array('value','length',array('min'=>0,'max'=>16000,'message'=>'Invalid value','for'=>array('new','edit'))),
			array('note','length',array('min'=>0,'max'=>200,'message'=>'Invalid note','for'=>array('new','edit'))),
			array('campaign_id','required',array('for'=>array('new'),'message'=>'Invalid tracker ID'))
		);
	}
	
	public static function getUniqueVariableName($min,$max) {
		$failsafe = 5;
		$cur = '';
		$chosen = '';
				
		while($failsafe) {
			$cur = generateRandomString($min,$max);
			
			if(!self::checkUniqueVariableName($cur) . "'") {
				$chosen = $cur;
				break; //yay, we got one
			}
			
			$failsafe--;
		}
		
		return $chosen;
	}
	
	public static function checkUniqueVariableName($name) {
		if($name == 'click_id' || $name == 'referer') {
			return true;
		}
		
		return DB::getVar("select 1 from bt_u_campaign_options where name like 'var_%' and value = '" . DB::quote($name) . "'");
	}
	
	public function __toString() {
		return $this->value;
	}
	
	public function setDefaultPreferences($conditions,$models) {
		foreach($this->_defaultPreferences as $name=>$value) {
			$found = false;
			foreach($models as $row) {
				if($row->name == $name) {
					$found = true;
					break;
				}
			}
			
			if(!$found) {
				$tmp = self::model();
				$tmp->campaign_id = $conditions['campaign_id'];
				$tmp->name = $name;
				$tmp->value = $value;

				$models[] = $tmp;
			}
		}
		
		return $models;
	}

    public function duplicate($old_id,$campaign_id) {
        $campaign_id = DB::quote($campaign_id);
        $old_id = DB::quote($old_id);

        $query = "insert into " . $this->tableName() . " (campaign_id, name, value)";//  The note field that is trying to insert is not in the table
        $query .= "select '$campaign_id', name, value from " . $this->tableName() . " where campaign_id='$old_id'";
        if(!DB::query($query)) {
            return false;
        }

        for ($i = 1; $i < 5; $i++) {
            $updateVar = "UPDATE bt_u_campaign_options as co SET co.value= '".generateRandomString(3,7)."'";
            $updateVar .= " WHERE co.campaign_id = '$campaign_id' AND co.name = 'var_v".$i."'";
            if(!DB::query($updateVar)) {
                return false;
            }
        }

        return true;
    }

}
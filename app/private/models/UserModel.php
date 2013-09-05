<?php

BTApp::importModel('UserPrefModel');

class UserModel extends BTModel {
	public function tableName() {
		return 'bt_u_users';
	}
	
	public function pk() {
		return 'user_id';
	}
	
	public function relations() {
		return array(
			'prefs'=>array('UserPrefModel','user_id',self::REL_ONE_MANY,'name',array(UserPrefModel::model(),'setDefaultPreferences'))
		);
	}
	
	public function rules() {
		return array(
			array('user_name','required',array('message'=>'Please enter an account name','for'=>array('admin_edit','admin_new'))),
			array('user_name','length',array('min'=>1,'max'=>50,'message'=>'Invalid account name','for'=>array('admin_edit','admin_new'))),
			array('user_name','callback',array('func'=>array('UserModel','usernameFree'),'message'=>'That username is taken')),
			
			array('email','required',array('message'=>'Please enter your email','for'=>array('user_profile','admin_edit','admin_new'))),
			array('email','email',array('message'=>'Invalid email','for'=>array('user_profile','admin_edit','admin_new'))),
			array('email','callback',array('func'=>array('UserModel','emailFree'),'message'=>'That email is taken')),
				
			array('timezone','required',array('for'=>'user_profile')),
			
			array('old_pass','callback',array('for'=>array('user_profile_password'),'func'=>array($this,'checkUserPass'),'message'=>'Please enter your current password')),
			array('pass','required',array('for'=>array('user_profile_password','admin_new'), 'message'=>"Please enter a password")),
			array('pass_confirm','required',array('for'=>array('user_profile_password','admin_new'), 'message'=>"Please confirm your password")),
			array('pass','optional',array('for'=>array('admin_edit'))),
			array('pass_confirm','compare',array('to'=>'pass', 'for'=>array('user_profile_password','admin_edit','admin_new'), 'message'=>"Please confirm your password")),
			
			array('privilege','required',array('for'=>array('admin_edit','admin_new')))
		);
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
	
	public function setData($data = array()) {	
		parent::setData($data);
												
		return $this;
	}
	
	public function name() {
		return $this->get('user_name');
	}
	
	public function getPref($name) {
		if(!isset($this->prefs[$name])) {
			return null;
		}
		
		return $this->prefs[$name]->value;
	}
	
	public function setPref($name,$value) {
		if(!isset($this->prefs[$name])) {
			$this->prefs[$name] = UserPrefModel::model();
		}
		
		$pref = $this->prefs[$name];
		
		$pref->value = $value;
		$pref->user_id = $this->id();
		$pref->name = $name;
		$pref->useRuleSet('save');
				
		return $pref->save();		
	}
	
	public function checkUserPass($to_check) {
		$to_check = self::saltPassword($to_check,$this->get('pass_salt'));
		
		return ($to_check == $this->_orig['pass']);
	}
	
	public function privilegeLevel() {
		return $this->get('privilege');
	}
	
	public function isAdmin() {
		return ($this->privilegeLevel() == USER_PRIV_ADMIN) ? true : false;
	}
	
	public static function userWithName($user_name) {
		return UserModel::model()->getRow(array(
			'conditions'=>array(
				'user_name'=>$user_name
			)
		));
	}

	public static function usernameFree($username,$model) {
		$username = DB::quote($username);
		$id = DB::quote($model->id());
			
		return (DB::getRow("select user_id from bt_u_users where user_name='" . $username . "' and user_id <> '" . $id . "'") == null);
	}
	
	public static function emailFree($email,$model) {
		$email = DB::quote($email);
		$id = DB::quote($model->id());
	
		return (DB::getRow("select user_id from bt_u_users where email='" . $email . "' and user_id <> '" . $id . "'") == null);
	}
	
	public function getLoginLogs($limit = 100, $offset = 0) {
		if(!is_numeric($limit) || !is_numeric($offset)) {
			return array();
		}
		
		return DB::getRows("select * from bt_s_authsessions left join bt_s_ips using (ip_id) where user_id='" . DB::quote($this->id()) . "' order by session_id desc limit $offset,$limit");
	}
	
	public function countLoginLogs() {
		return DB::getVar("select count(1) from bt_s_authsessions where user_id='" . DB::quote($this->id()) . "'");
	}
	
	public static function saltPassword($password,$salt) {
		$iteration = 50;
		$algo = 'sha512';
		$hash = hash($algo,$salt . $password . GLOBAL_HASH_SALT);
		
		//to slow em down... a little bit anyway. A GPU could still crack this sucker in about 10 seconds. 
		for($i = 1;$i < $iteration;$i++) {
			$hash = hash($algo,$hash);
		}
		
		return $hash;
	}
	
	public static function generateSalt() {
		$min_len = 30;
		$max_len = 30;
		$len = mt_rand($min_len,$max_len);
		$char_list = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890!@#$%^&*()";
		$our_salt = '';
		
		for($i = 0;$i < $len;$i++) {
			$our_salt .= $char_list{mt_rand(0,strlen($char_list) - 1)};
		}		
		
		return $our_salt;
	}
	
	public function beforeSave() {
		parent::beforeSave();
		
		//handle password salting
		if($this->plain_pass) {
			$this->pass_salt = self::generateSalt();
			
			$this->pass = self::saltPassword($this->plain_pass, $this->pass_salt);
		}
	}
	
	public function afterSave() {
		parent::afterSave();
		
		if(!$this->prefs) {
			$mod = new UserPrefModel();
			$mod->user_id = $this->id();
			
			$mod->save(false,true);
		}
	}
}
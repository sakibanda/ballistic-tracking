<?php 

class BTAuth {
	public static $user = array();
	public static $expire = '';
	
	protected static $_authUserId = 0;
	protected static $_authUser = array();

	/**
	 * Logs user in and sets user auth cookie. Adds session to database
	 * 
	 * @param string $name username
	 * @param string $plain_pass Plain text password
	 * @return int
	 */
	public static function log_in($name,$plain_pass) {		
		$success = 0;
				
		$user = UserModel::userWithName($name);
				
		$message = '';
				
		if(!$user) {
			$message = "Invalid username";
		}
		
		if(!$message) {
			if($user->get('pass_salt')) { //using new style
				$pass = UserModel::saltPassword($plain_pass,$user->get('pass_salt'));
			}
			else { //old style
				$pass = BTAuth::salt_pass($plain_pass);
			}
			
			if($pass == $user->get('pass')) {
				$success = 1;
			}
			else {
				$message = 'Incorrect password';
			}
		}
		
		if($success) {
			if(!$user->get('pass_salt')) { //still using old hashing, time to upgrade
				$user->pass = $plain_pass;
				$user->save();
			}
		
			$key = sha1(sha1(rand(0,100000)) . sha1($user->get('user_id')));
			$fingerprint = sha1($_SERVER['HTTP_USER_AGENT'] . $_SERVER['REMOTE_ADDR'] . $key);
			
			$expire = time() + (AUTH_SESSION_LENGTH*60);
			$cookie = array($key,$user->get('user_id'));
			$cookie = join('|',$cookie);
			
			$time_format = DB::quote(date('Y-m-d H:i:s',time()));
			$expire_format = DB::quote(date('Y-m-d H:i:s',$expire));
			$active_format = $time_format;
			$user_id = DB::quote($user->get('user_id'));
			$key = DB::quote($key);
			$fingerprint = DB::quote($fingerprint);
			$ip_id = DB::quote(INDEXES::get_ip_id($_SERVER['REMOTE_ADDR']));
			$meta = DB::quote(json_encode(array('user_agent'=>$_SERVER['HTTP_USER_AGENT'],'user_name'=>$name,'message'=>'')));	
		}
		else if($user) {
			$time_format = DB::quote(date('Y-m-d H:i:s',time()));
			$expire_format = DB::quote(date('Y-m-d H:i:s',time()));
			$active_format = $time_format;
			$user_id = $user->id();
			$key = '';
			$fingerprint = '';
			$ip_id = DB::quote(INDEXES::get_ip_id($_SERVER['REMOTE_ADDR']));
			$meta = DB::quote(json_encode(array('user_agent'=>$_SERVER['HTTP_USER_AGENT'],'user_name'=>$name,'message'=>$message)));
		}
		else {
			$time_format = DB::quote(date('Y-m-d H:i:s',time()));
			$expire_format = DB::quote(date('Y-m-d H:i:s',time()));
			$active_format = $time_format;
			$user_id = 0;
			$key = '';
			$fingerprint = '';
			$ip_id = DB::quote(INDEXES::get_ip_id($_SERVER['REMOTE_ADDR']));
			$meta = DB::quote(json_encode(array('user_agent'=>$_SERVER['HTTP_USER_AGENT'],'user_name'=>$name,'message'=>$message)));
		}
		
		DB::query("insert into bt_s_authsessions set `time`='$time_format', `expire`='$expire_format', `user_id`='$user_id', `key`='$key', `fingerprint`='$fingerprint',
				ip_id='$ip_id', `success`='$success', `meta`='$meta'");
		
		if($success) {
			self::set_auth_cookie($cookie,$expire);
			self::$_authUserId = $user->get('user_id');
			self::$expire = $expire_format;
		}
		
		return $success;
	}
	
	public static function user() {
		return self::$user;
	}
	
	public static function authUser() {
		return self::$_authUser;
	}
	
	public static function salt_pass($pass) { 
		$salt = '6d886q0EU55233p';
		$pass = md5($salt . md5($pass . $salt));
		return $pass;
	}
	
	public static function generate_random_salt() {
		$min_len = 15;
		$max_len = 20;
		$len = mt_rand($min_len,$max_len);
		$char_list = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890!@#$%^&*()";
		$our_salt = '';
		
		for($i = 0;$i < $len;$i++) {
			$our_salt .= $char_list{mt_rand(0,strlen($char_list) - 1)};
		}		
		
		return $our_salt;
	}
	
	public static function logged_in() {
		$cookie = getArrayVar($_COOKIE,'bt_auth');
		
		if(!$cookie) {
			return false;
		}
		
		$split = explode('|',$cookie);
		if(!is_array($split) || (count($split) < 2)) {
			self::set_auth_cookie('',time() - 3600);
			return false;
		}
		
		$key = DB::quote($split[0]);
		$user_id = DB::quote($split[1]);
		$cur_time = DB::quote(date('Y-m-d H:i:s',time()));
        //$session = DB::getRow("select session_id,expire, fingerprint from bt_s_authsessions where `success`='1' and `user_id`='$user_id' and `key`='$key' and `expire` > '$cur_time' order by session_id desc");
        $session = DB::getRow("select session_id,expire, fingerprint from bt_s_authsessions where `success`='1' and `user_id`='$user_id' and `key`='$key' order by session_id desc");
		
		if(!$session) {
			self::set_auth_cookie('',time() - 3600);
			return false;
		}
		
		$fingerprint = sha1($_SERVER['HTTP_USER_AGENT'] . $_SERVER['REMOTE_ADDR'] . $key);
		
		if($session['fingerprint'] != $fingerprint) {
			self::set_auth_cookie('',time() - 3600);
			return false;
		}
		
		self::$expire = $session['expire'];
		self::$_authUserId = $split[1];
		
		//extend cookie length if non-ajax request
		if(!IS_AJAX) {
			$expire = time() + (AUTH_SESSION_LENGTH*60);
			$expire_format = DB::quote(date('Y-m-d H:i:s',$expire));
			
			self::set_auth_cookie($_COOKIE['bt_auth'],$expire);
			DB::query("update bt_s_authsessions set expire='" . $expire_format . "' where session_id='" . DB::quote($session['session_id']) . "'");
		}

        self::validatePlan($user_id);
		
		return true;
	}
	
	public static function set_auth_cookie($cookie,$time) {
		setcookie('bt_auth',$cookie,$time,'/','.' . $_SERVER['HTTP_HOST']);
		$_COOKIE['bt_auth'] = $cookie; //to avoid php weirdness with cookie not getting set after setcookie for this page load
	}

	public static function require_user() {	
		if (BTAuth::logged_in() == false) {
			if(IS_AJAX) {
				//is datatables request
				if(isset($_GET['sEcho'])) {
					$sEcho = $_GET['sEcho'];
					$cols = $_GET['iColumns'];

					$data = array('sEcho'=>(int)$sEcho,
					'iTotalRecords'=>1,
					'iTotalDisplayRecords'=>1,
					'aaData'=>array());
					
					$arr = array('Your session has timed out. Please log back in.');
					for($i = 1;$i < $cols;$i++) {  //ensures we return correct # of cols. No super important since datatables is forgiving in this respect.
						$arr[] = '';
					}
					
					$data['aaData'][] = $arr;
					
					echo json_encode($data);
					BTApp::end();
				}
				else {
					echo "Your session has timed out. Please log back in.";
					BTApp::end();
				}
			
				return false;
			}
			else {
				header("Location: /logout");
				BTApp::end();
			}
		}
		
		if(!self::$user) {
			$user = UserModel::model()->getRowFromPk(self::$_authUserId,true);
			
			if(!$user) {
				header("Location: /");
				BTApp::end(); //what else are we gonna do? Call the ghostbusters?
			}
			
			//this is always the authed user
			self::$_authUser = $user;
			
			if($user->isAdmin()) {
				if(isset($_COOKIE['user_inject'])) {
					$id = $_COOKIE['user_inject'];
					
					$tmpuser = UserModel::model()->getRowFromPk($id,true);
					
					if($user->isAdmin()) { //always allow admin
						self::$user = $tmpuser;
					}
				}
			}
			
			if(!self::$user) {
				//this is the auth user or a subuser (if authed user is admin)
				self::$user = $user;
			}
		}
		
		date_default_timezone_set(self::$user->get('timezone'));
				
		return true;
	}	
	
	public static function require_admin() {
		self::require_user();
		
		if(!self::user()->isAdmin()) {
			header("Location: /overview/");
		}
	}

    public static function  validatePlan($user_id){

        $url = 'http://localhost/license_check.php?license=abc1231';
        $handle = curl_init($url);
        curl_setopt($handle,  CURLOPT_RETURNTRANSFER, TRUE);

        /* Get the HTML or whatever is linked in $url. */
        $response = curl_exec($handle);
        $test_mode_mail = $response === 'true'? true: false;
        if(!$test_mode_mail){
            header("Location: /planValidation");
        }
        //echo $response;

        /* Check for 404 (file not found). */
        $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);

        //echo $httpCode;

        curl_close($handle);
       /*
       if(isset($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST'] != ""){

           $sql_plan = "SELECT s.settings_id ,s.pass_key,s.api_key, s.domain,s.buy_date, s.recurrence FROM bt_u_settings s WHERE s.type = 'Ballistic' AND s.user_id =".$user_id;
           $result = DB::getRows($sql_plan);
           if($result){
               $flag = 0;
               foreach($result as $row){
                   if(in_array($_SERVER['HTTP_HOST'], $row)){
                       $date = mktime(0, 0, 0, date("m")-$row['recurrence']  , date("d"), date("Y"));
                       $buydate = date('Y-m-d',strtotime($row['buy_date']));
                       if($buydate>=date("Y-m-d",$date)){
                            if(($row['api_key'] == $row['pass_key']) && ($row['domain'] == $_SERVER['HTTP_HOST'])){
                                $flag = 1;
                            }
                       }
                   }
               }
                if($flag!=1){
                    header("Location: /planValidation");
                }

           }
       }
       */
    }
}

<?php

class LoginController extends BTController {

	public function indexAction() {
		if(BTAuth::logged_in()) {
			header('location: /overview');
			BTApp::end();
		}
		
		$success = true;
		
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$mysql['user_name'] = $_POST['user_name'];
				
			$success = BTAuth::log_in($_POST['user_name'],$_POST['pass']);
			
			if($success) {

                //Validate if the plan is Active
                $this->validateSubscriptions();

				header('location: /overview');
				BTApp::end();
			}
		}
		
		$this->setVar("title","Login");
		$this->loadTemplate("public_header");
		
		$this->setVar("success",$success);

        $file = BT_ROOT . '/bt-config/bt-config.php';
        if(file_exists($file)){
            $this->loadView("login/login");
        }else{
            header("Location: /config");
            BTApp::end();
        }
		
		$this->loadTemplate("public_footer");
	}
	
	public function ajaxLoginAction() {
		$success = BTAuth::log_in($_POST['user_name'],$_POST['pass']);
			
		if($success) {
			echo 1;
		}
		else {
			echo 0;
		}
	}
	
	public function lostPassAction() {
		if(BTAuth::logged_in()) {
			header('location: /overview');
			BTApp::end();
		}
		
		if(isset($_POST['cancel']) && $_POST['cancel']) {
			header("Location: /login");
			BTApp::end();
		}
		
		if ($_SERVER['REQUEST_METHOD'] == 'POST') { 
			$mysql['user_name'] = $_POST['user_name'];
			$mysql['email'] = $_POST['email'];
			
			$user_row = UserModel::model()->getRow(array(
				'conditions'=>array(
					'email'=>$_POST['email']
				)
			));
									
			if($user_row && ($user_row->get('user_name') != $_POST['user_name'])) {
				$user_row = null;
			}
									
			if (!$user_row) { $error['user'] = '<div class="error"> Invalid username /email combination.</div>'; }
			
			//i there isn't any error, give this user, a new password, and email it to them!
			if (!$error) {
				
				$mysql['user_id'] = $user_row->id();
				
				//generate random key
				$pass_key = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
				$pass_key = substr(str_shuffle($pass_key), 0, 40) . time();
				$mysql['pass_key'] = $pass_key;
		
				//set the user pass time
				$mysql['pass_time'] = time(); 
					
				//insert this verification key into the database, and the timestamp of inserting it
				
				$user_row->pass_key = $mysql['pass_key'];
				$user_row->pass_time = $mysql['pass_time'];
				$user_row->save();			
				
				//now email the user the script to reset their email
				$to = $_POST['email'];
				$subject = "Ballistic Tracking Password Reset"; 
				
				$message = "
		<p>Someone has asked to reset the password for the following username.</p>
				
		<p>Username: ".$_POST['user_name']."</p>
		
		<p>To reset your password visit the following address, otherwise just ignore this email and nothing will happen.</p>
		
		<p><a href=\"" . getBTUrl() ."/login/passReset?key=$pass_key\">" . getBTUrl() ."/login/passReset?key=$pass_key</a></p>";
				
				$from = "ballistictracking@".$_SERVER['SERVER_NAME'];
				
				$header = "From: Ballistic Tracking<" . $from . "> \r\n";
			    	$header .= "Reply-To: ".$from." \r\n";
			    	$header .=  "To: " . $to . " \r\n";
			    	$header .= "Content-Type: text/html; charset=\"iso-8859-1\" \r\n";
			    	$header .= "Content-Transfer-Encoding: 8bit \r\n";
			    	$header .= "MIME-Version: 1.0 \r\n";
						
				mail($to,$subject,$message,$header);
				
				$success = true;
			}
				
			$html['user_name'] = BTHtml::encode($_POST['user_name']);
			$html['email'] = BTHtml::encode($_POST['email']);
			
		}
		
		$this->setVar("title","Reset Your Password");
		$this->loadTemplate("public_header");
		
		$this->setVar("success",$success);
		$this->setVar("html",$html);
		$this->setVar("error",$error);
		$this->loadView("login/lostpass");
		
		$this->loadTemplate("public_footer");
	}
	
	public function passResetAction() {	
		if(!isset($_GET['key'])) {
			_die("Bad key");
		}
	
		//take password retireveal and see if it is legitimate
		$user_row = UserModel::model()->getRow(array(
			'conditions'=>array(
				'pass_key'=>$_GET['key']
			)
		));
				
		if (!$user_row) { 
			$error['pass_key'] = '<div class="error">No key was found like that</div>'; 
		}
		
		if (!$error) {
			//how many days ago was this code activated, this code will only work if the activation reset code is at least current within the last 3 days
			$date_today = time(); 
			$days = (($date_today-$user_row->get('pass_time'))/86400);  
			
			if ($days > 3) { $error['pass_key'] .= '<div class="error">Sorry, this key has expired</div>'; }		
		}
		
		
		//if the key is legit, make sure their new posted password is legit     
		if (!$error and ($_SERVER['REQUEST_METHOD'] == "POST")) {	
			if ($_POST['pass']=='') { $error['pass'] = '<div class="error">You must type in your desired password</div>'; }
			
			if ($_POST['pass']=='') { $error['pass'] .= '<div class="error">You must type verify your password</div>'; }
			
			if ((strlen($_POST['pass']) < 6) OR (strlen($_POST['pass']) > 15)) { $error['pass'] .= '<div class="error">Passwords must be 6 to 15 characters long</div>';}
			
			if ($_POST['pass'] != $_POST['verify_pass']) { $error['pass'] .= '<div class="error">Your passwords did not match, please try again</div>'; }
			
			if (!$error) {		
				$user_row->pass = $_POST['pass'];
				$user_row->pass_time = 0;
				$user_row->pass_key = '';
				
				$user_row->save();
				
				$success = true;
			}
		}   
		
		$html['user_name'] = BTHtml::encode($user_row->get('user_name')); 
		
		
		
		//if password was changed succesfully
		if ($success == true) { 
			_die("<div style='text-align: center'><br/>Congratulations, your password has been reset.<br/>You can now <a href=\"/login\">login</a> with your new password</div>");
		} 
		
		if ($error['pass_key']) {
			_die("<div style='text-align: center'><br/>".$error['pass_key'] ."<p>Please use the <a href=\"/lost-pass\">password retrieval tool</a> to get a new password reset key.</p></div>");
		}
		
		$this->setVar("title","Reset Your Password");
		$this->loadTemplate("public_header");
		
		$this->setVar("success",$success);
		$this->setVar("html",$html);
		$this->setVar("error",$error);
		$this->loadView("login/resetpass");
		
		$this->loadTemplate("public_footer");
	}
	
	public function ViewAsAction() {
		BTAuth::require_user();
	
		if(!BTAuth::authUser()->isAdmin()) { //normal user
			error404();
		}
	
		$id = $_GET['id'];
		
		$user = UserModel::model()->getRowFromPk($id);
		
		$inject = false;
		if($user) {
			if(BTAuth::authUser()->isAdmin()) { //allow super admin to view anyone
				$inject = true;
			}
		}
		
		if($inject) {
			setcookie("user_inject",$id,time() + (60*60*24),"/",$_SERVER['HTTP_HOST']);
			header("Location: /overview");
		}
		else {
			setcookie("user_inject",'',time() - (60*60*24),"/",$_SERVER['HTTP_HOST']);		
			header("Location: /overview");
		}
	}

    public function validateSubscriptions(){

        $user_id = 1;
        $settings = SettingsModel::model()->getRow(array(
            'conditions'=>array(
                'user_id'=>$user_id,
                'type' => 'Ballistic Tracker'
            )
        ));

        if($settings){
            $key = $settings->api_key;
            $url = 'http://ballistic.puresrc.com/license_check/?license=order_'.$key;
            $handle = curl_init($url);
            curl_setopt($handle,  CURLOPT_RETURNTRANSFER, TRUE);
            $response = curl_exec($handle);
            $test_mode_mail = $response === 'true'? true: false;
            if(!$test_mode_mail){

                //disable
                $settings->active=1;
                $settings->save();

                BTAuth::set_auth_cookie('',time() - 3600);
                header("Location: /plan?error=1");
                BTApp::end();
            }
            //$httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
            //echo $httpCode;
            curl_close($handle);

            //enable
            $settings->active=0;
            $settings->save();

            $ss = SettingsModel::model()->getRow(array(
                'conditions'=>array(
                    'user_id'=>$user_id,
                    'type' => 'Advanced Redirects'
                )
            ));

            //If Advanced Redirects is active
            if($ss){
                $key = $ss->api_key;
                $url = 'http://ballistic.puresrc.com/advanced_redirects/?license=order_'.$key;
                $handle = curl_init($url);
                curl_setopt($handle,  CURLOPT_RETURNTRANSFER, TRUE);
                $response = curl_exec($handle);
                curl_close($handle);
                $test_mode_mail = $response === 'true'? true: false;
                if(!$test_mode_mail){
                    //disable
                    $ss->active=1;
                }else{
                    //enable
                    $ss->active=0;
                }
                $ss->save();
            }

        }else{
            BTAuth::set_auth_cookie('',time() - 3600);
            header("Location: /plan?error=2");
            BTApp::end();
        }
    }
}
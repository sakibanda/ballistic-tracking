<?php

loadController('AdminController');

class AdminAccountsController extends AdminController {
	public function indexAction() {
		$this->setVar("title","Manage Accounts");
		$this->render("admin/accounts");
	}
	
	public function ajaxAction($command = '',$params = array()) {			
		switch($command) {
			case 'view_accountlist':
				$userlist=  UserModel::model()->getRows();
				
				$this->setVar("userlist",$userlist);
				$this->loadView("admin/accounts_list");
				break;
			case 'json_user':
				$user = UserModel::model()->getRowFromPk($_GET['user_id']);

				echo $user->toJSON();
				break;
			case 'post_delete':
				$user_id = $_POST['user_id'];

				$user = UserModel::model()->getRowFromPk($user_id);
				$user->delete();
				break;
			case 'post_add':
				$user = UserModel::model();
				
				$user->user_name = $_POST['user_name'];
				$user->email = $_POST['email'];
				
				$user->plain_pass = $_POST['pass'];
				$user->pass = $_POST['pass'];
				$user->pass_confirm = $_POST['pass_confirm'];
				
				$user->privilege = $_POST['privilege'];
				
				$user->useRuleSet("admin_new");
				
				if($user->save()) {
					echo '0';
				}
				else {
					echo join('<br>',$user->getErrors());
				}
				break;
			case 'post_edit':
				$user = UserModel::model()->getRowFromPk($_POST['user_id']);
				
				if(!$user) {
					echo "Bad ID";
					BTApp::end();
				}
				
				$user->user_name = $_POST['user_name'];
				$user->email = $_POST['email'];
				
				if($_POST['pass']) {
					$user->plain_pass = $_POST['pass'];
					$user->pass = $_POST['pass'];
					$user->pass_confirm = $_POST['pass_confirm'];
				}
				else { //to satisfy the validation
					$user->pass = $user->pass;
					$user->pass_confirm = $user->pass;
				}
				
				$user->privilege = $_POST['privilege'];
				
				$user->useRuleSet("admin_edit");
				
				if($user->save()) {
					echo '0';
				}
				else {
					echo join('<br>',$user->getErrors());
				}
				
				break;
		}
	}
}
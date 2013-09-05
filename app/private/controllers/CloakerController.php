<?php

class CloakerController extends BTUserController {
	public function __construct() {
		parent::__construct();
		
		if(!bt_cloaker_enabled()) {
			header("Location: /");
			BTApp::end();
		}
		
		$this->loadModel('CloakerModel');
		$this->loadModel('CloakerOptionModel');
		$this->loadModel('CloakerIpModel');
		$this->loadModel('CloakerRefererModel');
		$this->loadModel('CloakerHostnameModel');
		$this->loadModel('CloakerUaModel');
		
		require_once(BT_ROOT . '/private/includes/cloaker.php');
		
		initCloaker();
	}
	
	public function indexAction() {		
		$this->setVar("title","Manage Cloakers");
		
		$this->render("cloaker/index");
	}
	
	public function editAction($param = array()) {
		if($param) {
			switch($param[0]) {
				case 'orgs':
					$this->editOrgsAction();
					break;
				case 'advanced':
					$this->editAdvancedAction();
					break;
				case 'variables':
					$this->editVariablesAction();
					break;
				case 'limits':
					$this->editLimitsAction();
					break;
			}
			
			BTApp::end();
		}
		
		if(!($cloaker = CloakerModel::model()->getRowFromPk($_GET['id']))) {
			echo 'Invalid redirect ID';
			BTApp::end();
		}
		else {
			$cloaker->useRuleSet('edit');
		}
				
		$opts = $cloaker->options;
		
		if(isset($_POST['name'])) {						
			$cloaker->name = $_POST['name'];
			$cloaker->url = $_POST['url'];
			
			if(!($cloaker->save())) {
				$this->setVar("error","Could not save settings");
			}
			else {
				setCloakerOption($cloaker,'exclude_url',$_POST['exclude_url']);
				
				$this->setVar("success","Settings saved");
			}
		}
		
		$opts = array(
			'exclude_url'=>''
		);
		
		$cloaker->clearJoin(); //clear the join
		$options = $cloaker->options;
		
		foreach($options as $opt) {
			$opts[$opt->name] = $opt->value;
		}
		
		global $cloaker_orgs;
		$this->setVar("cloaker_orgs",$cloaker_orgs);
		$this->setVar("options",$opts);
		$this->setVar("cloaker",$cloaker);
		$this->setVar("title","Edit Cloaker");
		
		$this->render("cloaker/edit");
	}
	
	public function editOrgsAction() {
		if(!($cloaker = CloakerModel::model()->getRowFromPk($_GET['id']))) {
			echo 'Invalid redirect ID';
			BTApp::end();
		}
		else {
			$cloaker->useRuleSet('edit');
		}
		
		if(isset($_POST['edit_org'])) {			
			$orgs = array();
			for($i = 0;$i < count($_POST['organizations']);$i++) {
				$orgs[] = array($_POST['organizations'][$i],$_POST['org_url'][$i]);
			}
			
			setCloakerOption($cloaker,'organizations',json_encode($orgs));
			
			$this->setVar("success","Settings saved");
		}
		
		$opts = array(
			'organizations'=>'[]',
		);
		
		$options = $cloaker->options;
		
		foreach($options as $opt) {
			$opts[$opt->name] = $opt->value;
		}
		
		$opts['organizations'] = json_decode($opts['organizations'],true);
		
		global $cloaker_orgs;
		$this->setVar("cloaker_orgs",$cloaker_orgs);
		$this->setVar("options",$opts);
		$this->setVar("cloaker",$cloaker);
		$this->setVar("title","Edit Redirect Organizations");
		
		$this->render("cloaker/edit_orgs");
	}
	
	public function editAdvancedAction() {
		if(!($cloaker = CloakerModel::model()->getRowFromPk($_GET['id']))) {
			echo 'Invalid redirect ID';
			BTApp::end();
		}
		else {
			$cloaker->useRuleSet('edit');
		}
		
		/*
		 * When saved, we simply delete all existing entries for the respective models & readd them. Easier than
		 * tracking entry IDs and such.
		 */ 
		if(isset($_POST['save'])) {	
			if($cloaker->ips) {
				$cloaker->ips[0]->delete();
			}
			
			if($cloaker->hostnames) {
				$cloaker->hostnames[0]->delete();
			}
			
			if($cloaker->referers) {
				$cloaker->referers[0]->delete();
			}
			
			if($cloaker->user_agents) {
				$cloaker->user_agents[0]->delete();
			}
			
			for($i = 0;$i < count($_POST['exclude_hostname']); $i++) {
				$obj = new CloakerHostnameModel();
				$obj->hostname = $_POST['exclude_hostname'][$i];
				$obj->cloaker_id = $cloaker->id();
				$obj->url = $_POST['exclude_hostname_url'][$i];
				$obj->memo = $_POST['exclude_hostname_memo'][$i];
				$obj->regex = $_POST['hostname_regex'][$i];
				$obj->useRuleSet('new');
				$obj->save();
			}
			
			for($i = 0;$i < count($_POST['exclude_user_agent']); $i++) {
				$obj = new CloakerUaModel();
				$obj->user_agent = $_POST['exclude_user_agent'][$i];
				$obj->cloaker_id = $cloaker->id();
				$obj->url = $_POST['exclude_user_agent_url'][$i];
				$obj->memo = $_POST['exclude_user_agent_memo'][$i];
				$obj->regex = $_POST['user_agent_regex'][$i];				
				$obj->useRuleSet('new');
				$obj->save();
			}
						
			for($i = 0;$i < count($_POST['exclude_ip_range_from']); $i++) {
				$obj = new CloakerIpModel();
				$obj->ip_from = $_POST['exclude_ip_range_from'][$i];
				$obj->ip_to = $_POST['exclude_ip_range_to'][$i];
				$obj->url = $_POST['exclude_ip_range_url'][$i];
				$obj->memo = $_POST['exclude_ip_range_memo'][$i];
				$obj->cloaker_id = $cloaker->id();
				$obj->useRuleSet('new');
				$obj->save();
			}
			
			for($i = 0;$i < count($_POST['exclude_referer']); $i++) {
				$obj = new CloakerRefererModel();
				$obj->referer = $_POST['exclude_referer'][$i];
				$obj->cloaker_id = $cloaker->id();
				$obj->url = $_POST['exclude_referer_url'][$i];
				$obj->memo = $_POST['exclude_referer_memo'][$i];
				$obj->regex = $_POST['referer_regex'][$i];
				$obj->useRuleSet('new');
				$obj->save();
			}
			
			$this->setVar("success","Settings saved");
		}
		
		$cloaker->clearJoin(); //clear the join
		
		global $cloaker_orgs;
		$this->setVar("cloaker_orgs",$cloaker_orgs);
		$this->setVar("cloaker",$cloaker);
		$this->setVar("title","Edit Advanced Options");
		
		$this->render("cloaker/edit_advanced");
	}
	
	public function editVariablesAction() {
		if(!($cloaker = CloakerModel::model()->getRowFromPk($_GET['id']))) {
			echo 'Invalid redirect ID';
			BTApp::end();
		}
		else {
			$cloaker->useRuleSet('edit');
		}
		
		if(isset($_POST['var_trackerid'])) {
			setCloakerOption($cloaker,'var_trackerid',$_POST['var_trackerid']);
			setCloakerOption($cloaker,'var_v1',$_POST['var_v1']);
			setCloakerOption($cloaker,'var_v2',$_POST['var_v2']);
			setCloakerOption($cloaker,'var_v3',$_POST['var_v3']);
			setCloakerOption($cloaker,'var_v4',$_POST['var_v4']);
			setCloakerOption($cloaker,'var_kw',$_POST['var_kw']);
			
			$this->setVar("success","Settings saved");
		}
		
		$cloaker->clearJoin(); //clear the join
		$options = $cloaker->options;
		
		$opts = array(
			'var_trackerid'=>'btid',
			'var_v1'=>'v1',
			'var_v2'=>'v2',
			'var_v3'=>'v3',
			'var_v4'=>'v4',
			'var_kw'=>'kw'
			
		);
		
		foreach($options as $opt) {
			$opts[$opt->name] = $opt->value;
		}
		
		global $cloaker_orgs;
		$this->setVar("cloaker_orgs",$cloaker_orgs);
		$this->setVar("options",$opts);
		$this->setVar("cloaker",$cloaker);
		$this->setVar("title","Edit Variables");
		
		$this->render("cloaker/edit_variables");
	}

	public function editLimitsAction() {
		if(!($cloaker = CloakerModel::model()->getRowFromPk($_GET['id']))) {
			echo 'Invalid redirect ID';
			BTApp::end();
		}
		else {
			$cloaker->useRuleSet('edit');
		}
		
		if(isset($_POST['clickfrequency'])) {
			setCloakerOption($cloaker,'clickfrequency',$_POST['clickfrequency']);
			setCloakerOption($cloaker,'expiration',$_POST['expiration']);
			
			$this->setVar("success","Settings saved");
		}
		
		$cloaker->clearJoin(); //clear the join
		$options = $cloaker->options;
		
		$opts = array(
			'clickfrequency'=>'0',
			'expiration'=>'10'
		);
		
		foreach($options as $opt) {
			$opts[$opt->name] = $opt->value;
		}
		
		global $cloaker_orgs;
		$this->setVar("cloaker_orgs",$cloaker_orgs);
		$this->setVar("options",$opts);
		$this->setVar("cloaker",$cloaker);
		$this->setVar("title","Edit Limits");
		
		$this->render("cloaker/edit_limits");
	}
	
	public function ajaxAction($command = '',$params = array()) {
		switch($command) {
			case 'view_cloaker_list':
				$cloakers = CloakerModel::model()->getRows();
				
				$this->setVar("cloakers",$cloakers);
				$this->loadView("cloaker/view_cloaker_list");
				break;
				
			case 'post_cloaker_add':
				$cloaker = CloakerModel::model();
				$cloaker->useRuleSet('new');
				
				$cloaker->url = $_POST['url'];
				$cloaker->name = $_POST['name'];
				
				if($cloaker->save()) {
					echo 1;
				}
				else {
					echo 0;
				}
				
				break;
				
			case 'post_cloaker_delete':
				$cloaker = CloakerModel::model()->getRow(array(
					'conditions'=>array(
						'cloaker_id'=>$_POST['id']
					)
				));
				
				if($cloaker) {
					$cloaker->delete();
                    $this->setVar("success","Redirect Deleted");
				}
				
				break;
				
			case 'misc_download':
				$file = BT_ROOT . '/private/downloads/index.php';
				
				header('Content-Description: File Transfer');
				header('Content-Type: application/octet-stream');
				header('Content-Disposition: attachment; filename='.basename($file));
				header('Content-Transfer-Encoding: binary');
				header('Expires: 0');
				header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
				header('Pragma: public');

				$content = file_get_contents($file);
				
				$content = str_replace("{BT_URL}", getBTUrl(),$content);
				
				echo $content;
				
				BTApp::end();
				
				break;
			case 'post_cloaker_duplicate':				
				if($_POST['id']) {
					CloakerModel::model()->duplicate($_POST['id']);
				}
				
				break;
		}
	}
	
	public function viewCloakerOrgsAction() {	
		global $cloaker_orgs;
		$this->setVar("cloaker_orgs",$cloaker_orgs);
		
		$this->loadView("cloaker/view_cloakerorgs");
	}
	
	public function viewIprangeAction() {	
		$this->loadView("cloaker/view_iprange");
	}
	
	public function viewUserAgentAction() {	
		$this->loadView("cloaker/view_useragent");
	}
	
	public function viewHostnameAction() {	
		$this->loadView("cloaker/view_hostname");
	}
	
	public function viewRefererAction() {	
		$this->loadView("cloaker/view_referer");
	}
	
	public function menu() {
		?>
		<div class="grid_12 admin_opts">
			<div class="buttons">
				<a href="/cloaker/edit?id=<?php echo $_GET['id']; ?>">
					<span class="icon icon-edit"></span>
					General
				</a>
				
				<a href="/cloaker/edit/orgs?id=<?php echo $_GET['id']; ?>">
					<span class="icon icon-group"></span>
					Organizations
				</a>
				
				<a href="/cloaker/edit/limits?id=<?php echo $_GET['id']; ?>">
					<span class="icon icon-time"></span>
					Limits
				</a>
				
				<a href="/cloaker/edit/advanced?id=<?php echo $_GET['id']; ?>">
					<span class="icon icon-wrench"></span>
					Advanced
				</a>
			</div>
		</div>
		<?php
	}
}
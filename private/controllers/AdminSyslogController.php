<?php

loadController('AdminController');

class AdminSyslogController extends AdminController {
	public function __construct() {
		$this->loadModel('SyslogModel');
	}
	
	public function indexAction() {	
		$model = new SyslogModel();
		
		$this->setVar("syslog_types",$model->getRows(array('group'=>'type','order'=>'type asc')));
	
		$this->setVar("title","Syslog");
		
		$this->render("admin/syslog/syslog");
	}
	
	public function ajaxAction($command = '',$params = array()) {	
		switch($command) {
			case 'data_get_syslog':
				$type = $_GET['filtertype'];
				$level = $_GET['filterlevel'];
				
				$conds = array();
				
				if($type != 'all') {
					$conds['type'] = $type;
				}
				
				if($level != 'all') {
					$conds['level'] = $level;
				}
								
				$cnt = SyslogModel::model()->count(array(
					'conditions'=>$conds
				));
								
				$rows = SyslogModel::model()->getRows(array(
					'conditions'=>$conds,
					'offset'=>$_GET['iDisplayStart'],
					'limit'=>$_GET['iDisplayLength'],
					'order'=>'date desc'
				));
																
				$data = array('sEcho'=>(int)$_GET['sEcho'],
					'iTotalRecords'=>$cnt,
					'iTotalDisplayRecords'=>$cnt,
					'aaData'=>array());
				
				foreach($rows as $row) {
					$entry = array();
					
					switch($row->level) {
						case 1:
							$row->level = 'Message';
							break;
						case 2:
							$row->level = 'Warning';
							break;
						case 3:
							$row->level = 'Error';
							break;
						case 4:
							$row->level = '<strong>Critical</strong>';
							break;
					}
						
					$entry[] = $row->date;
					$entry[] = $row->type;
					$entry[] = $row->level;
					$entry[] = $row->message;
					
					$data['aaData'][] = $entry;
				}
				
				echo json_encode($data);
				break;
		}
	}
}

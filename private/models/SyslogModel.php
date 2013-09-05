<?php

class SyslogModel extends BTModel {	
	public function tableName() {
		return 'bt_g_syslog';
	}
	
	public function pk() {
		return 'log_id';
	}
	
	public function delete($flag = 0) {
		
	}
}
<?php

class ClickCounterModel extends BTModel {
	public function tableName() {
		return 'bt_s_counter';
	}
	
	public function pk() {
		return 'click_count';
	}
	
	public function inc() {
		DB::query("update bt_s_counter set click_count=click_count+1");
		$this->click_count++;
	}
}
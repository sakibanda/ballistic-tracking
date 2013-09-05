<?php
global $cloaker_orgs;
$cloaker_orgs = array();

function initCloaker() {
	global $cloaker_orgs;
	
	$cloaker_orgs = DB::getRows("SELECT * FROM `bt_g_cloaker_orgs`",'org_name');
}

function setCloakerOption($cloaker,$name,$value) {
	$opt = CloakerOptionModel::model()->getRowFromPk(array('cloaker_id'=>$cloaker->id(),'name'=>$name));

	if(!$opt) {
		$opt = CloakerOptionModel::model();
		$opt->cloaker_id = $cloaker->id();
		$opt->name = $name;
		$opt->value = $value;
		$opt->useRuleSet('new');
	}
	else {
		$opt->useRuleSet('edit');
		$opt->value = $value;
	}


	$opt->save();
}

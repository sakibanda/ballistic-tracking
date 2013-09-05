<?php

function saveTrackingVariables($campaign) {
	$mysql = array();
	
	$v1 = DB::quote(getArrayVar($_GET,$campaign->option('var_v1')->value));
	$v2 = DB::quote(getArrayVar($_GET,$campaign->option('var_v2')->value));
	$v3 = DB::quote(getArrayVar($_GET,$campaign->option('var_v3')->value));
	$v4 = DB::quote(getArrayVar($_GET,$campaign->option('var_v4')->value));
	
	if(!$v1) {
		$v1 = DB::quote(getArrayVar($_GET,'subid1'));
	}
	
	if(!$v2) {
		$v2 = DB::quote(getArrayVar($_GET,'subid2'));
	}
	
	if(!$v3) {
		$v3 = DB::quote(getArrayVar($_GET,'subid3'));
	}
	
	if(!$v4) {
		$v4 = DB::quote(getArrayVar($_GET,'subid4'));
	}
	
	$v1 = strtolower($v1);
	$v2 = strtolower($v2);
	$v3 = strtolower($v3);
	$v4 = strtolower($v4);

	$row = DB::getRows("select var_id,LOWER(var_value) as var_value from bt_s_variables where var_value IN ('$v1','$v2','$v3','$v4')",'var_value');
	
	if(!isset($row[$v1])) {
		DB::query("insert into bt_s_variables set var_value='$v1'");
		$row[$v1] = array('var_id'=>DB::insertId(),'var_value'=>$v1);
	}
	
	if(!isset($row[$v2])) {
		DB::query("insert into bt_s_variables set var_value='$v2'");
		$row[$v2] = array('var_id'=>DB::insertId(),'var_value'=>$v2);
	}
	
	if(!isset($row[$v3])) {
		DB::query("insert into bt_s_variables set var_value='$v3'");
		$row[$v3] = array('var_id'=>DB::insertId(),'var_value'=>$v3);
	}
	
	if(!isset($row[$v4])) {
		DB::query("insert into bt_s_variables set var_value='$v4'");
		$row[$v4] = array('var_id'=>DB::insertId(),'var_value'=>$v4);
	}
	
	$mysql['v1'] = $row[$v1]['var_value'];
	$mysql['v1_id'] = $row[$v1]['var_id'];
	
	$mysql['v2'] = $row[$v2]['var_value'];
	$mysql['v2_id'] = $row[$v2]['var_id'];
	
	$mysql['v3'] = $row[$v3]['var_value'];
	$mysql['v3_id'] = $row[$v3]['var_id'];
	
	$mysql['v4'] = $row[$v4]['var_value'];
	$mysql['v4_id'] = $row[$v4]['var_id'];
	
	return $mysql;
}
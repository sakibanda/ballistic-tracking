<?php

function runOrganizationLookup() {		
	$orgip = Net_GeoIP::getInstance(BT_ROOT . "/bt-config/GeoIPOrg.dat");
	
	$org = '';
	try {
		$org = $orgip->lookupOrg($_SERVER['REMOTE_ADDR']);
	} catch (Exception $e) {
		return 0;
	}
	
	if(!$org) {
		return 0;
	}
	
	$org = DB::quote($org);
	
	$sql = "select org_id from bt_g_organizations where name='$org'";
	
	$id = DB::getVar($sql);
	
	if(!$id) {
		$sql = "insert into bt_g_organizations values ('','$org')";
		
		DB::query($sql);
		
		$id = DB::insertId();
	}
	
	return $id;
}
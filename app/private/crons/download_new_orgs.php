<?php

require_once(__DIR__ . '/../includes/connect.php');

$ch = curl_init(API_SERVER . '/download_orgs.php');
curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
$ret = curl_exec($ch);

//could not download
if(!$ret) {
	echo "Could not download\n";
	exit;
}

//invalid data
if(!($orgs = json_decode($ret))) {
	echo "Invalid data returned\n";
	exit;
}

$getSt = DB::prepare("select 1 from bt_g_cloaker_orgs where org_id=?");
$inSt = DB::prepare("insert into bt_g_cloaker_orgs set org_id=?, org_name=?");

foreach($orgs as $org) {
	$getSt->execute(array($org->org_id));
	
	if(!$getSt->fetchAll()) {
		$inSt->execute(array($org->org_id,$org->name));
	}
}

echo 'Done';
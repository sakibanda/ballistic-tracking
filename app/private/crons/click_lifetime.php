<?php

require_once(__DIR__ . '/../includes/connect.php');

require_once(BT_ROOT . '/private/includes/traffic/lifetime.php');

$sql = "select click_id,(lead_time - time) as lifetime from bt_s_clicks where (lead=1) and (lead_time > time) and (lead_time > 0) and (lifetime = 0) limit %s, %s";

$cnt = DB::getVar("select count(*) from bt_s_clicks where (lead=1) and (lead_time > time) and (lead_time > 0) and (lifetime = 0)");

$cursor = 0;
$page = 200;

while($rows = DB::getRows(sprintf($sql,$cursor,$page))) {
	foreach($rows as $row) {
		$time = $row['lifetime'];
		$id = $row['click_id'];
		
		DB::query("update bt_s_clicks set lifetime='" . DB::quote(getClickLifetimeInterval($time)) . "' where click_id='" . $id . "'");
	}

	$cursor += $page;
}

printf("%d Links processed\n",$cnt);
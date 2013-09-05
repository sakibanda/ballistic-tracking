<?php 
$organization_id = 0;
$geo_block_id = 0;

$ip = DB::quote(ip2long($_SERVER['REMOTE_ADDR']));

/***BEGIN GEO TRACKING****/
if(bt_geo_enabled()) {
	require(BT_ROOT . '/private/includes/traffic/geolookup.php');
	
	$geo_block_id = runGeoLookup();
}
/***END GEO TRACKING****/

/***BEGIN MOBILE TRACKING****/
if(bt_mobile_enabled()) {
	require(BT_ROOT . '/private/includes/traffic/organization.php');
	
	$organization_id = runOrganizationLookup();

	//Track Devices:
	require_once(BT_ROOT . '/private/libs/wurfl/TeraWurfl.php');

	$wurflObj = new TeraWurfl();

	$wurflObj->getDeviceCapabilitiesFromAgent();

	//dont run for desktop OSes. 
	$is_wireless = $wurflObj->getDeviceCapability('is_wireless_device');
	$is_tablet = $wurflObj->getDeviceCapability('is_tablet');
	$is_phone = $wurflObj->getDeviceCapability('can_assign_phone_number');

	$is_desktop = true;
	if($is_wireless == 'true') {
		$is_desktop = false;
	}
	else if($is_tablet == 'true') {
		$is_desktop = false;
	}
	else if($is_phone == 'true') {
		$is_desktop = false;
	}

	//dont run if its a desktop
	if($is_desktop) {
		$device_id = 0;
		return;
	}

	require_once(BT_ROOT . '/private/models/DeviceDataModel.php');

	$device_id = DeviceDataModel::getDeviceId($wurflObj);
}
/*****END MOBILE TRACKING****/
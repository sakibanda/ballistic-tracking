<?php
require_once(__DIR__ . "/timezone.php");
require_once(__DIR__ . "/geoipregionvars.php");
require_once(BT_ROOT . "/private/libs/geo/GeoIP.php");

function runGeoLookup() {
	global $GEOIP_REGION_NAME;
	
	$geoip = Net_GeoIP::getInstance(BT_ROOT . "/bt-config/GeoLiteCity.dat");
	
	$location = null;
	try {
		$location = $geoip->lookupLocation($_SERVER['REMOTE_ADDR']);
	} catch (Exception $e) {
		//ignore it, seriously dude, friggin ignore it. 
	}
			
	if($location) {
		$country = DB::quote($location->countryCode);
		$country_full = DB::quote($location->countryName);
		$state = DB::quote($location->region);
		$state_full = @DB::quote($GEOIP_REGION_NAME[strtoupper($location->countryCode)][strtoupper($location->region)]);
		$city = DB::quote($location->city);
		$timezone = @DB::quote(get_time_zone($location->countryCode,$location->region));
		$postalcode = DB::quote($location->postalCode);
	}
	else {
		return 0;
	}
	
	$sql = "select location_id from bt_g_geo_locations where country='$country' and state='$state' and city='$city'";
	
	$id = DB::getVar($sql);
	
	if(!$id) {
		$sql = "insert into bt_g_geo_locations values ('','$country','$country_full','$state','$state_full','$city','$timezone','$postalcode')";
		
		DB::query($sql);
		
		$id = DB::insertId();
	}
	
	return $id;
}
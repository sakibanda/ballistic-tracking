<?php

function clickLifetimeIntervals() {
	$intervals = array(
		180 => '0-3 Minutes',
		300 => '3-5 Minutes',
		900 => '5-15 Minutes',
		1800 => '15-30 Minutes',
		2700 => '30-45 Minutes',
		3600 => '45 Minutes - 1 Hour',
		7200 => '1-2 Hours',
		10800 => '2-3 Hours',
		14400 => '3-4 Hours',
		18000 => '4-5 Hours',
		21600 => '5-6 Hours',
		25200 => '6-7 Hours',
		28800 => '7-8 Hours',
		32400 => '8-9 Hours',
		36000 => '9-10 Hours',
		39600 => '10-11 Hours',
		43200 => '11-12 Hours',
		46800 => '12-13 Hours',
		50400 => '13-14 Hours',
		54000 => '14-15 Hours',
		57600 => '15-16 Hours',
		61200 => '16-17 Hours',
		64800 => '17-18 Hours',
		68400 => '18-19 Hours',
		72000 => '19-20 Hours',
		75600 => '20-21 Hours',
		79200 => '21-22 Hours',
		82800 => '22-23 Hours',
		86400 => '23-24 Hours',
		172800 => '1-2 Days',
		259200 => '2-3 Days',
		345600 => '3-4 Days',
		432000 => '4-5 Days',
		518400 => '5-6 Days',
		604800 => '6-7 Days',
		1209600 => '1-2 Weeks',
		1814400 => '2-3 Weeks',
		2419200 => '3-4 Weeks'
	);
	
	return $intervals;
}

function getClickLifetimeInterval($time) {
	$intervals = clickLifetimeIntervals();
	
	$failsafe = 2419200; //4 weeks
	
	$chosen = 0;
	
	foreach($intervals as $interval=>$label) {
		//printf("Comparing %d with %d\n",$time,$interval);
	
		if($time > $interval) {
			continue;
		}
		else {
			$chosen = $interval;
			break;
		}
	}
	
	if(!$chosen) {
		$chosen = $failsafe;
	}
	
	return $chosen;
}
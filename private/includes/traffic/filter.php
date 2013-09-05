<?php


class FILTER {
	
	public static function startFilter($ip_id) {
		 
		//we only do the other checks, if the first ones have failed.
		//we will return the variable filter, if the $filter returns TRUE, when the click is inserted and recorded we will insert the new click already inserted,
		//what was lagign this query is before it would insert a click, then scan it and then update the click, the updating later on was lagging, now we will just insert and it will not stop the clicks from being redirected becuase of a slow update.
			
		//check the user
		if (!FILTER::checkUserIP($ip_id)) {
			if(FILTER::checkLastIps($ip_id)) {
				return 2;
			}
		}
		else {
			return 1;
		}
		
		return 0;
	}
	
	//Easy. Check the cookie and recent user IP. Done. 
	public static function checkUserIP($ip_id) {		
		if(isset($_COOKIE['bt_auth']) && $_COOKIE['bt_auth']) {
			return true;
		}   
		
		return false;
	}
	
	public static function checkLastIps($ip_id) {
		$ip_id = DB::quote($ip_id);
	
		if(DB::getVar("SELECT 1 FROM bt_s_clicks_advanced left join bt_s_clicks using (click_id) where ip_id='$ip_id' and time > (UNIX_TIMESTAMP() - (60 * 60 * 24)) limit 1")) {
			return true;
		}
	
		return false;
	}
}
<?php 
					

function getTrackingDomain() {
	return $_SERVER['HTTP_HOST'];
}




class INDEXES {
	
		
	//this returns the ip_id, when a ip_address is given
	public static function get_ip_id($ip_address) {
	
		$mysql['ip_address'] = DB::quote($ip_address);
		
		$ip_sql = "SELECT ip_id FROM bt_s_ips WHERE ip_address='".$mysql['ip_address']."'";
		$ip_row = DB::getRow($ip_sql);
		if ($ip_row) {
			//if this ip already exists, return the ip_id for it.
			$ip_id = $ip_row['ip_id'];
			
			return $ip_id;    
		} else {
			$ip_sql = "INSERT INTO bt_s_ips SET ip_address='".$mysql['ip_address']."'";
			$ip_result = DB::query($ip_sql) ; //($ip_sql);
			$ip_id = DB::insertId();
			
			return $ip_id;    
		}
	}     
	
	
	//this returns the keyword_id
	public static function get_keyword_id($keyword) {
		
		//only grab the first 255 charactesr of keyword
		$keyword = substr($keyword, 0, 255);
		$mysql['keyword'] = DB::quote($keyword);
		
		$keyword_sql = "SELECT keyword_id FROM bt_s_keywords WHERE keyword='".$mysql['keyword']."'";
		$keyword_row = DB::getRow($keyword_sql);
		if ($keyword_row) {
			//if this already exists, return the id for it
			$keyword_id = $keyword_row['keyword_id'];
			return $keyword_id;    
		} else {
			//else if this ip doesn't exist, insert the row and grab the id for it
			$keyword_sql = "INSERT INTO bt_s_keywords SET keyword='".$mysql['keyword']."'";
			$keyword_result = DB::query($keyword_sql) ; //($keyword_sql);
			$keyword_id = DB::insertId();
			return $keyword_id;    
		}
	}
	
	public static function get_platform_and_browser_id() {
		$br = new Browser;
		$id['platform'] = $br->Platform;
		$id['browser'] = $br->Browser; 
		return $id;      
	}
}

function getUrlDomain($url) {
	if(!$url) {
		return '';
	}

	$parsed_url = @parse_url($url);
	$domain_host = getArrayVar($parsed_url,'host');
	$domain_host = str_replace('www.','',$domain_host);
	return $domain_host;
}


function replaceTrackerPlaceholders($url,$data) {

	$url = str_replace('[[subid1]]', $data['v1'], $url);
	$url = str_replace('[[subid2]]', $data['v2'], $url);
	$url = str_replace('[[subid3]]', $data['v3'], $url);
	$url = str_replace('[[subid4]]', $data['v4'], $url);
	
	$url = str_replace('[[v1]]', $data['v1'], $url);
	$url = str_replace('[[v2]]', $data['v2'], $url);
	$url = str_replace('[[v3]]', $data['v3'], $url);
	$url = str_replace('[[v4]]', $data['v4'], $url);
	
	$url = str_replace('[[c1]]', $data['v1'], $url);
	$url = str_replace('[[c2]]', $data['v2'], $url);
	$url = str_replace('[[c3]]', $data['v3'], $url);
	$url = str_replace('[[c4]]', $data['v4'], $url);
	
	$url = str_replace('[[kw]]', $data['keyword'], $url);
	$url = str_replace('[[keyword]]', $data['keyword'], $url);
	
	$url = str_replace('[[clickid]]', $data['clickid'], $url);
	$url = str_replace('[[subid]]', $data['clickid'], $url);
	
	return $url;
}

function setClickIdCookie($subid) {
	//set the cookie for the PIXEL to fire, expire in 30 days
	$expire = time() + 2592000;
	setcookie('btclickid',$subid,$expire,'/', $_SERVER['SERVER_NAME']);
	$_COOKIE['btclickid'] = $subid;	
}

?>
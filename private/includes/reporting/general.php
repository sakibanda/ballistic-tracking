<?php

function dollar_format($amount) {
	$decimals = 2;
	
	if ($amount >= 0) {
		$new_amount = "\$".sprintf("%.".$decimals."f",$amount);
	} else { 
		$new_amount = "\$".sprintf("%.".$decimals."f",substr($amount,1,strlen($amount)));
		$new_amount = '('.$new_amount.')';    
	}
	
	return $new_amount;
}

function grab_timeframe() {
	if ((BTAuth::user()->getPref('time_predefined') == 'today') or (BTAuth::user()->getPref('time_from') != '')) { 
		$time['from'] = mktime(0,0,0,date('m',time()),date('d',time()),date('Y',time()));
		$time['to'] = mktime(23,59,59,date('m',time()),date('d',time()),date('Y',time()));    
	}
	
	if(BTAuth::user()->getPref('time_predefined') == 'yesterday') { 
		$time['from'] = mktime(0,0,0,date('m',time()-86400),date('d',time()-86400),date('Y',time()-86400));
		$time['to'] = mktime(23,59,59,date('m',time()-86400),date('d',time()-86400),date('Y',time()-86400));    
	}
	
	if(BTAuth::user()->getPref('time_predefined') == 'last7') { 
		$time['from'] = mktime(0,0,0,date('m',time()-86400*7),date('d',time()-86400*7),date('Y',time()-86400*7));
		$time['to'] = mktime(23,59,59,date('m',time()),date('d',time()),date('Y',time()));    
	}
	
	if(BTAuth::user()->getPref('time_predefined') == 'last14') { 
		$time['from'] = mktime(0,0,0,date('m',time()-86400*14),date('d',time()-86400*14),date('Y',time()-86400*14));
		$time['to'] = mktime(23,59,59,date('m',time()),date('d',time()),date('Y',time()));    
	}
	
	if(BTAuth::user()->getPref('time_predefined') == 'last30') { 
		$time['from'] = mktime(0,0,0,date('m',time()-86400*30),date('d',time()-86400*30),date('Y',time()-86400*30));
		$time['to'] = mktime(23,59,59,date('m',time()),date('d',time()),date('Y',time()));    
	}
	
	if(BTAuth::user()->getPref('time_predefined') == 'thismonth') { 
		$time['from'] = mktime(0,0,0,date('m',time()),1,date('Y',time()));
		$time['to'] = mktime(23,59,59,date('m',time()),date('d',time()),date('Y',time()));  
	}
	
	if(BTAuth::user()->getPref('time_predefined') == 'lastmonth') { 
		$time['from'] = mktime(0,0,0,date('m',time()-2629743),1,date('Y',time()-2629743));
		$time['to'] = mktime(23,59,59,date('m',time()-2629743),getLastDayOfMonth(date('m',time()-2629743), date('Y',time()-2629743)),date('Y',time()-2629743));
	}
	
	if(BTAuth::user()->getPref('time_predefined') == '') { 
		$time['from'] = BTAuth::user()->getPref('time_from');
		$time['to'] = BTAuth::user()->getPref('time_to');
	}
	

   $time['time_predefined'] = BTAuth::user()->getPref('time_predefined');
   return $time;   
}

/**
 * Gets the last day of the provided month.
 * @param int $month The month to get the last day of
 * @param int $year The year
 * @return int
 */
function getLastDayOfMonth($month, $year){
	return date("d", mktime(0, 0, 0, $month + 1, 0, $year));
}

function calculate_conv($clicks,$leads) {
	if($clicks) {
		$conv = ($leads / $clicks) * 100;
	}
	else {
		$conv = 0;
	}
	
	return number_format($conv,2,'.','');
}

function calculate_epc($clicks,$income) {
	if($clicks) {
		$epc = $income / $clicks;
	}
	else {
		$epc = 0;
	}
	
	return number_format($epc,2,'.','');
}

function calculate_cpc($clicks,$cost) {
	if($clicks) {
		$cpc = $cost / $clicks;
	}
	else {
		$cpc = 0;
	}
	
	return number_format($cpc,2,'.','');
}

function calculate_net($income,$cost) {
	$net = $income - $cost;
	
	return number_format($net,2,'.','');
}

function calculate_roi($income,$cost) {
	$net = calculate_net($income,$cost);
	
	if($cost > 0) {
		$roi  = ($net / $cost) * 100;
	}
	else {
		$roi = 0;
	}
	
	return number_format($roi,2,'.','');
}

function calculate_payout($leads,$income) {
	if(!$leads) {
		$payout = 0;
	}
	else {
		$payout = $income / $leads;
	}
	
	return number_format($payout,2,'.','');
}

function getFilteredCondition() {
	$filtered = '';
	
	if (BTAuth::user()->getPref('click_filter') == 'all') { $filtered = ''; }
	if (BTAuth::user()->getPref('click_filter') == 'real') { $filtered = " filtered='0' "; }
	if (BTAuth::user()->getPref('click_filter') == 'filtered') { $filtered = " filtered>'0' "; }
	if (BTAuth::user()->getPref('click_filter') == 'leads') { $filtered = " lead='1' "; }
	
	return $filtered;
}

function getReportLimits() {
	$start = (int)$_GET['iDisplayStart'];
	$limit = (int)$_GET['iDisplayLength'];
	
	if(!is_int($start)) {
		$start = 0;
	}
	
	if(!is_int($limit)) {
		$limit = 50;
	}
	
	//$offset = round($start / $limit);
	
	return sprintf(" limit %d, %d ",$start,$limit);
}

/**
 *This should technically fill in almost everything in the "where" conditions
 **/
function getReportFilters($report_page,$extra_join = '') {	
	$time = grab_timeframe();
	
	$start = DB::quote($time['from']);
	$end = DB::quote($time['to']);
	
	$option_fields = getReportOptionsForPage($report_page);
		
	$sql = '
		bt_s_clicks click
		LEFT JOIN bt_u_offers AS offer ON (click.offer_id = offer.offer_id)
		LEFT JOIN bt_u_aff_networks AS net ON (net.aff_network_id = offer.aff_network_id)
		LEFT JOIN bt_u_traffic_sources AS ts ON (ts.traffic_source_id = click.traffic_source_id)
		LEFT JOIN bt_u_campaigns as camp ON (camp.campaign_id=click.campaign_id)
		' . $extra_join . ' where ';
	
	$conds = array();
	
	$conds[] = " ts.deleted='0' ";
	$conds[] = " camp.deleted='0' ";
	$conds[] = " (net.deleted='0' || net.deleted is null ) ";
	$conds[] = " (offer.deleted='0' || offer.deleted is null ) ";
	
	if(@$option_fields['show_type']) {
		if(BTAuth::user()->getPref('campaign_type') == 'lp') {
			$conds[] =sprintf(" camp.type=1 ");
		}
		else if(BTAuth::user()->getPref('campaign_type') == 'direct') {
			$conds[] =sprintf(" camp.type=2 ");
		}
	}
	
	if(@$option_fields['show_traffic_source']) {
		if(BTAuth::user()->getPref('traffic_source_id')) {
			$conds[] =sprintf(" ts.traffic_source_id='%s' ",DB::quote(BTAuth::user()->getPref('traffic_source_id')));
		}
	}
	
	if(@$option_fields['show_campaign']) {
		if(BTAuth::user()->getPref('campaign_id')) {
			$conds[] =sprintf(" camp.campaign_id=%s ",DB::quote(BTAuth::user()->getPref('campaign_id')));
		}
	}
	
	$filt = getFilteredCondition();
	if($filt) {
		$conds[] = $filt;
	}
		
	$conds[] = sprintf(' ((click.time >= %s) and (click.time <= %s)) ',$start,$end);
	
	$sql .= join(' and ',$conds);
	
	return $sql;
}

function getSpendingReportFilters($spend_table,$option_fields) {
	$sql = '';
			
	$sql .= ' and deleted=0 ';
	
	return $sql;
}

function getReportOptionsForPage($page) {
	if(!($node = simplexml_load_file(BT_ROOT . '/private/config/report_options.xml'))) {
		echo 'Invalid report configuration';
		exit;
	}
	
	$opt_path = explode('/',$page);
	
	if(!$opt_path) {
		exit;
	}
	
	foreach($opt_path as $branch) {	
		if($node->$branch->count()) {
			$node = $node->$branch;
		}
		else {
			echo 'Bad report configuration';
			exit;
		}
	}
	
	$opt_arr = array();

	foreach($node->children() as $child) {
		if((string)$child === 'true') {
			$opt_arr[$child->getName()] = true;
		}
		else {
			$opt_arr[$child->getName()] = false;
		}
	}
	
	return $opt_arr;
}

function getReportFieldsForPage($page) {
	$opt_arr = getReportOptionsForPage($page);
							
	if(!($option_fields = simplexml_load_file(BT_ROOT . '/private/config/option_fields.xml'))) {
		echo 'Invalid report configuration';
		exit;
	}
				
	$fields = array();
				
	foreach($opt_arr as $opt=>$enabled) {
		if($option_fields->$opt) {								
			foreach($option_fields->$opt->children() as $field) {
				if($enabled) {
					$fields[(string)$field] = true;
				}
				else {
					unset($fields[(string)$field]);
				}
			}
		}
	}
	
	return $fields;
}

function formatColumnValue($col,$value) {	
	switch($col) {
		case 'click_through_rates':
		case 'conv':
		case 'roi':
			$value = number_format($value,2,'.','') . '%';
			break;
		case 'payout':
		case 'income':
		case 'net':
		case 'epc':
		case 'cpc':
		case 'avg_cpc':
		case 'cost':
			$value = dollar_format($value);
	}
	
	return $value;
}

function getDatatablesReportJson($report_data,$total_entries,$cols) {
	$sEcho = $_GET['sEcho'];

	$data = array('sEcho'=>(int)$sEcho,
		'iTotalRecords'=>$total_entries,
		'iTotalDisplayRecords'=>$total_entries,
		'aaData'=>array());
	
	foreach($report_data as $row) {
		$html = array();
		
		foreach($cols as $col) {
			$value = getArrayVar($row,$col,null);
			
			if($value === null) {
				$html[] = '';
			}
			else {
				switch($col) {
					case 'label':
					case 'actions':
						$html[] = formatColumnValue($col,$value);
						break;
					default:
						$html[] = BTHtml::encode(formatColumnValue($col,$value));
						break;
				}
			}
		}
		
		$data['aaData'][] = $html;
	} 

	return json_encode($data);
}

function getReportGeneralSelects() {
	return '
		count(click.click_id) as clicks,
		sum(click.offer_id>0) as click_throughs, 
		coalesce((sum(click.offer_id>0) / count(click.click_id))*100,0) as click_through_rates,
		sum(click.lead) as leads,
		coalesce((sum(click.lead) /count(click.click_id)*100),0) as conv,
		coalesce((sum(click.payout * click.lead) / sum(click.lead)),0) as payout,
		coalesce((sum(click.lead * click.payout) / count(click.click_id)),0) as epc,
		sum(click.lead * click.payout) as income
	';
}

function getReportOrder($cols,$extra_orders = '') {
	$sort_col = $_GET['iSortCol_0'];
	$sort_dir = $_GET['sSortDir_0'];
	
	if(($sort_dir != 'asc') && ($sort_dir != 'desc')) {
		$sort_dir = 'asc';
	}
	
	if($extra_orders) {
		return ' order by ' . $extra_orders . ', `' . $cols[$sort_col] . '` ' . $sort_dir . ' ';
	}
	
	return ' order by `' . $cols[$sort_col] . '` ' . $sort_dir . ' ';
}
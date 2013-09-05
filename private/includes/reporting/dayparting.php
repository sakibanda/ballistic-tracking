<?php

function dayparting_time($hour) {
	if($hour == '24') {
		$hour = 00;
	}
	
	if($hour == '00') {
		return 'midnight';
	}
	
	if($hour < 12) {
		return str_pad($hour,2,'0',STR_PAD_LEFT) . 'am';
	}
	
	if(!($hour = $hour - 12)) {
		$hour = '12';
	}	
	
	return str_pad($hour,2,'0',STR_PAD_LEFT) . 'pm';
}

function dayparting_data($order = '',$use_cache = false) {
	$offset = date('Z');
				
	DB::query("delete from bt_c_statcache where user_id='" . DB::quote(getUserId()) . "'  and type='daypart'");
	
	/****GET SPENDING****/
	$time = grab_timeframe();
	
	$spend_from = date("Y-m-d",$time['from']);
	$spend_to = date("Y-m-d",$time['to']);
	
	$sql = "select sum(amount) as cost from bt_u_spending where date >= '$spend_from' and date <= '$spend_to' ";
			
	$sql .= getSpendingReportFilters('bt_u_spending',getReportOptionsForPage('dateparting/date'));
		
	$spending_amt = DB::getVar($sql);
	/****END SPENDING****/
	
	$bulk = new DB_Bulk_Insert('bt_c_statcache',array('user_id','time_from','time_to','type'));
	for($i = 0;$i < 24;$i++) {
		
		$bulk->insert(array(getUserId(),$i,"0","'daypart'"));
	}
	$bulk->execute();
	
	$cols = array('time_from_int','clicks','leads','conv','payout','epc','income','cost','net','roi');
			
	$sql = "select FROM_UNIXTIME(time + $offset,'%H') as time_from, ";
	
	$sql .= getReportGeneralSelects() . ' from ';
	
	$sql .= getReportFilters('dateparting/date','');
	
	$sql .= ' group by time_from ';
					
	$result = DB::getRows($sql);
	
	$total_clicks = 0;
	
	foreach($result as $row) {
		$total_clicks += $row['clicks'];
	}
	
	$st = DB::prepare("update bt_c_statcache set clicks=?, leads=?, conv=?, payout=?, epc=?, income=?, cost=?, net=?, roi=? where user_id=? and type='daypart' and time_from=?");
	
	foreach($result as $row) {
		//simple ratio.
		$cost = ($row['clicks'] * $spending_amt) / $total_clicks;
		
		$row['cost'] = round($cost,2);
		$row['net'] = calculate_net($row['income'],$row['cost']);
		$row['roi'] = calculate_roi($row['income'],$row['cost']);
		
		$arr = array($row['clicks'], $row['leads'], $row['conv'], $row['payout'], $row['epc'], $row['income'], $row['cost'], $row['net'], $row['roi'], getUserID(), (int)$row['time_from']);
		
		$st->execute($arr);
	}
	
	$sql = "select *,convert(`time_from`,UNSIGNED INTEGER) as time_from_int,? as total_spend from bt_c_statcache where user_id=? and type='daypart' ";
	$sql .= getReportOrder($cols);
	
	$st = DB::prepare($sql);
	$st->execute(array($spending_amt,getUserID()));
	$rows = $st->fetchAll(PDO::FETCH_ASSOC);
	
	//show breakdown
	$total_clicks = 0;
	$total_leads = 0;
	$total_income = 0;
	$total_cost = 0;
	$cnt = 0;

	foreach($rows as &$row) {
		$row['time_from_int'] = dayparting_time($row['time_from_int']) . ' - ' . dayparting_time($row['time_from_int']+1);
		
		$total_clicks += $row['clicks'];
		$total_leads += $row['leads'];
		$total_income += $row['income'];
		$total_cost = $row['total_spend'];
	}
	
	$data_array = array();
	$data_array['time_from_int'] = 'Totals for report';
	$data_array['clicks'] = $total_clicks;
	$data_array['leads'] = $total_leads;
	$data_array['conv'] = calculate_conv($total_clicks,$total_leads);
	$data_array['payout'] = calculate_payout($total_leads,$total_income);
	$data_array['epc'] = calculate_epc($total_clicks,$total_income);
	$data_array['income'] = $total_income;
	$data_array['cost'] = $total_cost;
	$data_array['net'] = calculate_net($total_income,$total_cost);
	$data_array['roi'] = calculate_roi($total_income,$total_cost);
	$rows[] = $data_array;
	
	echo getDatatablesReportJson($rows,count($rows),$cols);
}
<?php

function weekparting_time($day) {
	$days = array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');
	
	return $days[$day];
}

function weekparting_data() {
	$offset = date('Z');
				
	DB::query("delete from bt_c_statcache where user_id='" . DB::quote(getUserId()) . "'  and type='weekpart'");
	
	/****GET SPENDING****/
	$time = grab_timeframe();
	
	$spend_from = date("Y-m-d",$time['from']);
	$spend_to = date("Y-m-d",$time['to']);
	
	$sql = "select sum(amount) as cost, FROM_UNIXTIME(UNIX_TIMESTAMP(date),'%w') as dayweek from bt_u_spending where date >= '$spend_from' and date <= '$spend_to'";
			
	$sql .= getSpendingReportFilters('bt_u_spending',getReportOptionsForPage('dateparting/date'));
		
	$sql .= ' group by dayweek';
		
	$spending_amts = DB::getRows($sql,'dayweek');
	/****END SPENDING****/
	
	$bulk = new DB_Bulk_Insert('bt_c_statcache',array('user_id','time_from','time_to','type'));
	for($i = 0;$i <7;$i++) {
		$bulk->insert(array(getUserId(),$i,"0","'weekpart'"));
	}
	$bulk->execute();
	
	$cols = array('time_from_int','clicks','leads','conv','payout','epc','cpc','income','cost','net','roi');
			
	$sql = "select FROM_UNIXTIME(time + $offset,'%w') as time_from, ";
	
	$sql .= getReportGeneralSelects() . ' from ';
	
	$sql .= getReportFilters('dateparting/date','');
	
	$sql .= ' group by time_from ';
					
	$result = DB::getRows($sql);
	
	$total_clicks = 0;
	
	foreach($result as $row) {
		$total_clicks += $row['clicks'];
	}
	
	$st = DB::prepare("update bt_c_statcache set clicks=?, leads=?, conv=?, payout=?, epc=?, income=?, cost=?, net=?, roi=? where user_id=? and type='weekpart' and time_from=?");
	
	$spending_amt = 0;
	
	foreach($result as $row) {
		//simple ratio.
		if(isset($spending_amts[$row['time_from']])) {
			$row['cost'] = $spending_amts[$row['time_from']]['cost'];
			$row['net'] = calculate_net($row['income'],$row['cost']);
			$row['roi'] = calculate_roi($row['income'],$row['cost']);
			
			$spending_amt += $row['cost'];			
		}
		else {
			$row['cost'] = 0;
			$row['net'] = 0;
			$row['roi'] = 0;
		}
		
		$arr = array($row['clicks'], $row['leads'], $row['conv'], $row['payout'], $row['epc'], $row['income'], $row['cost'], $row['net'], $row['roi'], getUserID(), (int)$row['time_from']);
			
		$st->execute($arr);
	}
	
	$sql = "select *,convert(`time_from`,UNSIGNED INTEGER) as time_from_int,? as total_spend from bt_c_statcache where user_id=? and type='weekpart' ";
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
		$row['time_from_int'] = weekparting_time($row['time_from_int']);
		
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
	$data_array['cpc'] = calculate_cpc($total_clicks,$total_cost);
	$data_array['income'] = $total_income;
	$data_array['cost'] = $total_cost;
	$data_array['net'] = calculate_net($total_income,$total_cost);
	$data_array['roi'] = calculate_roi($total_income,$total_cost);
	$rows[] = $data_array;
	
	echo getDatatablesReportJson($rows,count($rows),$cols);
}
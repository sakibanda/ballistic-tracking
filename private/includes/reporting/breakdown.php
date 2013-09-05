<?php

function date_chart($breakdown, $date) {
	$ex = explode('-',$date);

	if ($breakdown == 'day') { 
		$date = date('M jS', mktime(0,0,0,$ex[1],$ex[2],$ex[0]));      
	} elseif ($breakdown == 'month') { 
		$date = date('M Y', mktime(0,0,0,$ex[1],1,$ex[0]));        
	} elseif ($breakdown == 'year') { 
		$date = date('Y', mktime(0,0,0,1,1,$ex[0]));      
	}
	return $date;
}

function runBreakdown($user_pref) {
	//grab time
		$time = grab_timeframe(); 

	 //get breakdown pref
		$mysql['user_id'] = DB::quote(getUserID());   
				
		$filtered = getFilteredCondition();

		
	//breakdown should be hour, day, month, or year.
		$breakdown = BTAuth::user()->getPref('breakdown');
				
	//first delete old report
		$breakdown_sql = "
			DELETE
			FROM bt_c_statcache
			WHERE user_id='".$mysql['user_id']."' and type='breakdown'
		";
		DB::query($breakdown_sql) ; //($breakdown_sql);
	
	//find where to start from.		
		$datetype = '';
		
		//breakdown format
		if ($breakdown == 'day') {
			$datetype = '%Y-%m-%d';
		}
		else if ($breakdown == 'month') {
			$datetype = '%Y-%m';
		}
		else if ($breakdown == 'year') {
			$datetype = '%Y';
		}
		//BTApp::end();
		
		$start = $time['from'];
		$end = $time['to'];
		
		$spend_from = date("Y-m-d",$start);
		$spend_to = date("Y-m-d",$end);
		
		/****GET SPENDING****/
		$sql = "select sum(amount) as cost, FROM_UNIXTIME(UNIX_TIMESTAMP(date),'$datetype') as date from bt_u_spending 
				where date >= '$spend_from' and date <= '$spend_to' ";
				
		$sql .= getSpendingReportFilters('bt_u_spending',getReportOptionsForPage('overview/breakdown'));
				
		$sql .= "group by date";
		
		$spending_data = DB::getRows($sql,'date');
		/****END SPENDING****/
				
		$offset = date('Z');
		
		$bulk = new DB_Bulk_Insert('bt_c_statcache',array('user_id','time_from','time_to','type'));
		$x=0; 
		while ($end > $start) {
			if ($breakdown == 'day') { 
				$yr = date('Y',$start);
				$mo = date('m',$start);
				$dy = date('d',$start);
				
				$from = mktime(0,0,0,$mo,$dy,$yr);  
				$to = mktime(23,59,59,$mo,$dy,$yr);
				
				$start = $to + 1;
			} elseif ($breakdown == 'month') {
				$yr = date('Y',$start);
				$mo = date('m',$start);
				
				$from = mktime(0,0,0,$mo,1,$yr);  
				$to = mktime(23,59,59,$mo+1,0,$yr);
				
				$start = $to + 1;
			} elseif ($breakdown == 'year') { 
				$yr = date('Y',$start);
				
				$from = mktime(0,0,0,1,1,$yr);  
				$to = mktime(23,59,59,1,0,$yr+1);
				
				$start = $to + 1;          
			}
			
			$bulk->insert(array($mysql['user_id'],"FROM_UNIXTIME('".$from."','$datetype')","FROM_UNIXTIME('".$to."','$datetype')","'breakdown'"));
		}
		$bulk->execute();
		
		$user_id = DB::quote(getUserID());
		$start = DB::quote($time['from']);
		$end = DB::quote($time['to']);
		
		$sql = "
		SELECT COUNT(*) AS clicks, (SUM(click.payout*click.lead) / sum(click.lead)) as payout, SUM(click.lead) AS leads, SUM(click.payout*click.lead) AS income, 
		FROM_UNIXTIME(click.time + $offset,'$datetype') as date

		FROM 
		";
		
		$sql .= getReportFilters('overview/breakdown');

		$sql .= "
		group by date
		order by null
		";
		
		
		//echo $sql . "<br>";
		
		//echo $click_sql;
				
		$click_rows = DB::getRows($sql);
				
		foreach($click_rows as $click_row) {
			//get the stats
			$clicks = 0;  
			$clicks = $click_row['clicks'];
			
			$mysql['date'] = $click_row['date'];
				
			if($clicks) {
				$cost = getArrayVar($spending_data,$click_row['date'],array('cost'=>0));
				
				$cost = $cost['cost'];
				
				$avg_cpc = calculate_cpc($clicks, $cost);
			} 
			else {
				$avg_cpc = 0;
				$cost = 0; 
			}
				
			//leads
			$leads = $click_row['leads'];
				
			//signup ratio
			$conv = calculate_conv($clicks, $leads);
						
			//were not using payout
			//current payout
			$payout = $click_row['payout'];
	
			//income
			$income = 0;
			$income = $click_row['income'];
						
			//grab the EPC
			$epc = calculate_epc($clicks,$income);
								
			//net income
			$net = 0;
			$net = $income - $cost;

			//roi
			$roi = calculate_roi($income,$cost);
									
			//html escape vars
			$mysql['clicks'] = DB::quote($clicks);
			$mysql['leads'] = DB::quote($leads);
			$mysql['conv'] = DB::quote($conv);
			$mysql['epc'] = DB::quote($epc);
			$mysql['avg_cpc'] = DB::quote($avg_cpc);
			$mysql['income'] = DB::quote($income);
			$mysql['cost'] = DB::quote($cost);
			$mysql['net'] = DB::quote($net);
			$mysql['roi'] = DB::quote($roi);
			$mysql['payout'] = DB::quote($payout);
			
			//insert chart
			$sort_breakdown_sql = "
				update
					bt_c_statcache
				SET
					clicks='".$mysql['clicks']."',
					leads='".$mysql['leads']."',
					conv='".$mysql['conv']."',
					payout='" . $mysql['payout'] . "',
					epc='".$mysql['epc']."',
					cpc='".$mysql['avg_cpc']."',
					income='".$mysql['income']."',
					cost='".$mysql['cost']."',
					net='".$mysql['net']."',
					roi='".$mysql['roi']."'
					
					where
					
					time_from='".$mysql['date']."' and
					user_id='".$mysql['user_id']."' and
					type='breakdown'";
			
			DB::query($sort_breakdown_sql) ; //($sort_breakdown_sql);
		}
}

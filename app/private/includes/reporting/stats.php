<?php
function runStats($user_pref){

    //grab time
    $time = grab_timeframe();
    $_to = date('Y-m-d',$time['to']);
    $_from = date('Y-m-d',$time['from']);

    //get stats pref
    $mysql['user_id'] = DB::quote(getUserID());

    $filtered = getFilteredCondition();

    //first delete old report
    $stats_sql = "DELETE FROM bt_c_statcache WHERE user_id='".$mysql['user_id']."' and type='stats'";
    DB::query($stats_sql);

    /** GET SPENDING **/
    $sql = "select sum(amount) as cost, campaign_id from bt_u_spending where date >= '$_from' and date <= '$_to' ";
    $sql .= getSpendingReportFilters('bt_u_spending',getReportOptionsForPage('reports/stats'));
    $sql .= " group by campaign_id";
    $spends = DB::getRows($sql,'campaign_id');
    /** END SPENDING **/

    /** GET INCOME **/
    $sql = "select sum(amount) as cost, campaign_id from bt_u_income where date >= '$_from' and date <= '$_to' ";
    $sql .= getSpendingReportFilters('bt_u_income',getReportOptionsForPage('reports/stats'));
    $sql .= " group by campaign_id";
    $incomes = DB::getRows($sql,'campaign_id');
    /** END INCOME **/

    /** GET CLICK DATA **/
    $sql = "insert into bt_c_statcache select null,
			" . $mysql['user_id'] . " as user_id, 0 as time_from, 0 as time_to,'stats' as type,
			count(click.click_id) as clicks,
			0 as click_throughs, 0 as click_through_rates,
			sum(click.lead) as leads,
			0 as conv,
			(SUM(click.payout*click.lead) / sum(click.lead)) as payout,
			0 as epc, 0 as cpc,
			SUM(click.payout*click.lead) AS income,
			0 as cost, 0 as net, 0 as roi,
			click.campaign_id as meta1, camp.type as meta2, click.offer_id as meta3, click.landing_page_id as meta4
			from
			";
    $extra_join = "LEFT JOIN bt_u_landing_pages AS lp ON (lp.landing_page_id = click.landing_page_id)";
    $sql .= getReportFilters('reports/stats',$extra_join);
    $sql .= "group by click.landing_page_id, click.offer_id order by null";
    DB::query($sql);
    /** END CLICK DATA **/

    DB::query("update bt_c_statcache c
            left join (select
                    meta1,
                    sum(s.clicks) as clicks,
                    sum(s.click_throughs) as click_throughs,
                    sum(s.leads) as leads,
                    (sum(s.income) / sum(s.clicks)) as payout,
                    sum(s.income) as income
                    from bt_c_statcache s
                    where type='stats' AND user_id='".$mysql['user_id']."'
                  ) data
            on data.meta1 > 0
            set
                c.clicks=data.clicks, c.click_throughs=data.click_throughs, c.click_through_rates=(c.click_throughs / c.clicks), c.leads=data.leads, c.conv=(c.leads / c.clicks), c.payout=data.payout, c.income=data.income
                where (c.meta3 is null or c.meta3=0) AND (c.meta4 is null or c.meta4=0) AND type='stats' AND user_id='".$mysql['user_id']."' ");

    /** GET ALL CAMPAIGNS TO FILL ZEROES, AND CALCULATE TOP-LEVEL CAMPAIGNS **/
    $sql = "select meta1 from bt_c_statcache where user_id='".$mysql['user_id']."' and type='stats' and meta1>0 and meta2=2 group by meta1";
    $existing_rows = DB::getRows($sql,'meta1');
    foreach($existing_rows as $campaign) {
        DB::query("insert into bt_c_statcache set user_id='".$mysql['user_id']."', type='stats', meta1='" . DB::quote($campaign['meta1']) . "'");
    }

    reCalculateIncomes($incomes);
    calculateCosts($spends);

}

function reCalculateIncomes($incomes){
    $user_id = DB::quote(getUserID());
    $sql = "select * from bt_c_statcache where user_id='$user_id' and type='stats'";
    $rows = DB::getRows($sql);
    foreach($rows as $row) {
        $income = 0;
        if(isset($incomes[$row['meta1']])) {
            $income = $incomes[$row['meta1']]['cost'];
        }
        $row['income'] = $row['income'] + $income;
        DB::query("update bt_c_statcache set
				income='" . DB::quote($row['income']) . "'
				where id='" . DB::quote($row['id']) . "' AND type='stats' AND user_id='$user_id' ");
    }
}

function calculateCosts($spends) {
    $user_id = DB::quote(getUserID());
    $sql = "select * from bt_c_statcache where user_id='$user_id' and type='stats'";
    $rows = DB::getRows($sql);

    foreach($rows as $row) {
        if(isset($spends[$row['meta1']])) {
            $row['cost'] = $spends[$row['meta1']]['cost'];
        }

        $row['conv'] = calculate_conv($row['clicks'],$row['leads']);
        $row['epc'] = calculate_epc($row['clicks'],$row['income']);
        $row['cpc'] = calculate_cpc($row['clicks'],$row['cost']);
        $row['net'] = calculate_net($row['income'],$row['cost']);
        $row['roi'] = calculate_roi($row['income'],$row['cost']);

        DB::query("update bt_c_statcache set
				cost='" . DB::quote($row['cost']) . "',
				conv='" . DB::quote($row['conv']) . "',
				epc='" . DB::quote($row['epc']) . "',
				cpc='" . DB::quote($row['cpc']) . "',
				net='" . DB::quote($row['net']) . "',
				roi='" . DB::quote($row['roi']) . "'
				where id='" . DB::quote($row['id']) . "' and type='stats' AND user_id='$user_id' ");

        //if it has a campaign, update the top lvl LP as well
        if($row['meta2']) {
            $clicks = DB::quote($row['clicks']);
            $leads = DB::quote($row['leads']);
            $meta1 = DB::quote($row['meta1']);
            $meta2 = DB::quote($row['meta2']);
            $income = DB::quote($row['income']);

            DB::query("update bt_c_statcache set
					clicks=clicks+$clicks,
					leads=leads+$leads,
					income=income+$income
					where user_id='$user_id' and type='stats' and meta1='$meta1' and meta2='0'");
        }
    }
}
?>
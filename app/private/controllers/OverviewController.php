<?php 

class OverviewController extends BTUserController {
	public function __construct() {
		require_once(BT_ROOT . '/private/includes/reporting/export.php');
	}
	
	public function indexAction() {		
		$this->setVar("title", "Campaign Overview");
		$this->render("overview/index");
	}
	
	public function getOverviewData() {
		$time = grab_timeframe(); 
		
		$user_id = DB::quote(getUserID());
		$start = DB::quote($time['from']);
		$end = DB::quote($time['to']);
		
		/** GET SPENDING **/
		$spend_from = date('Y-m-d',$time['from']);
		$spend_to = date('Y-m-d',$time['to']);
		
		$sql = "select sum(amount) as cost, campaign_id from bt_u_spending 
				where date >= '$spend_from' and date <= '$spend_to' ";
		
		$sql .= getSpendingReportFilters('bt_u_spending',getReportOptionsForPage('overview/overview'));
		
		$sql .= " group by campaign_id";
		
		$spends = DB::getRows($sql,'campaign_id');
		/** END SPENDING **/
				
		//Erase old cache
		DB::query("delete from bt_c_statcache where type='overview' and user_id='$user_id'");
		
		/** GET CLICK DATA **/
		$sql = "insert into bt_c_statcache select null,
			" . $user_id . " as user_id, 0 as time_from, 0 as time_to,'overview' as type, 
			count(click.click_id) as clicks,
			0 as click_throughs, 0 as click_through_rates,
			sum(click.lead) as leads, 
			0 as conv, 
			(SUM(click.payout*click.lead) / sum(click.lead)) as payout, 
			0 as epc, 0 as cpc, 
			SUM(click.payout*click.lead) AS income,
			0 as cost, 0 as net, 0 as roi,
			click.campaign_id as meta1, camp.type as meta2, click.offer_id as meta3, null as meta4
			
			from
			";
			
		$sql .= getReportFilters('overview/overview');
		
		$sql .= "group by click.campaign_id, click.offer_id
			order by null";
									
		DB::query($sql);
		/** END CLICK DATA **/
		
		/** GET ALL CAMPAIGNS TO FILL ZEROES, AND CALCULATE TOP-LEVEL CAMPAIGNS **/
		$sql = "select meta1 from bt_c_statcache where user_id='$user_id' and type='overview' and meta1>0 group by meta1";
		
		$conditions = array();
		$ts_id = BTAuth::user()->getPref('traffic_source_id');
		if($ts_id) {
			$conditions['traffic_source_id'] = $ts_id;
		}
		
		$campaigns = CampaignModel::model()->getRows(array('conditions'=>$conditions));
		
		$existing_rows = DB::getRows($sql,'meta1');
				
		//insert blank rows wheren eeded
		foreach($campaigns as $campaign) {
			if(isset($existing_rows[$campaign->id()])) {		
				continue;
			}
			
			if(BTAuth::user()->getPref('campaign_type') == 'lp') {
				if($campaign->type != 1) {
					continue;
				}
			}
			else if(BTAuth::user()->getPref('campaign_type') == 'direct') {
				if($campaign->type != 2) {
					continue;
				}
			}
			
			DB::query("insert into bt_c_statcache set user_id='$user_id', type='overview', meta1='" . DB::quote($campaign->id()) . "'");
		}
		
		//calculate top-level stats
		foreach($campaigns as $campaign) {
			DB::query("update bt_c_statcache c
				
				left join (select 
						meta1, 
						sum(s.clicks) as clicks,
						sum(s.click_throughs) as click_throughs,
						sum(s.leads) as leads,
						(sum(s.income) / sum(s.clicks)) as payout,
						sum(s.income) as income
						
						from bt_c_statcache s where s.meta1=" . $campaign->id() . "
					  ) data
				on data.meta1 > 0
				
				set
					c.clicks=data.clicks, c.click_throughs=data.click_throughs, c.click_through_rates=(c.click_throughs / c.clicks), c.leads=data.leads, c.conv=(c.leads / c.clicks), c.payout=data.payout, c.income=data.income
					
					where c.meta1=" . $campaign->id() . " and (c.meta3 is null or c.meta3=0)");
		}
		/** END TOP LEVEL LP **/
		
		$this->calculateCostsPerCampaign($spends);
	}
	
	public function calculateCostsPerCampaign($spends) {
		$user_id = DB::quote(getUserID());
		$sql = "select * from bt_c_statcache where user_id='$user_id' and type='overview'";
		
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
				
				where id='" . DB::quote($row['id']) . "'");
			
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
					
					where user_id='$user_id' and type='overview' and meta1='$meta1' and meta2='0'");
			}
		}
	}
	
	public function exportOverviewData() {
		$this->getOverviewData();
		
		$start = (int)$_GET['iDisplayStart'];
		$length = (int)$_GET['iDisplayLength'];
		
		$user_id = DB::quote(getUserID());
		
		$sql = "select stat.*, camp.campaign_id, camp.name from bt_c_statcache stat
			
			left join bt_u_campaigns camp on (stat.meta1=camp.campaign_id)
			
			where stat.user_id='$user_id' and stat.type='overview'
			
			order by camp.name asc limit $start, $length";
		
		$stats = DB::getRows($sql);
		
		$cols = array('label','clicks','meta2','leads','conv','payout','epc','cpc','income','cost','net','roi');
		
		foreach($stats as &$stat) {
			$stat['label']  = $stat['campaign_id'] . ' - ' . $stat['name'];
		}
		
		return array('data'=>$stats,'cols'=>$cols,'cnt'=>count($stats));
	}
	
	public function exportOverviewAction() {
		doReportExport('overview',array($this,'exportOverviewData'),'csv','Direct Offer / Landing Page,Clicks,Leads,Conv %,Payout,EPC,Avg CPC,Income,Cost,Net,ROI');
	}
	
	public function viewOverviewAction() {				
		$this->loadView('overview/view_overview');
	}
	
	public function getDataOverview() {
		$cols = array('campaign_id','name','type','clicks','leads','conv','payout','epc','cpc','income','cost','net','roi','actions');
		
		$this->getOverviewData();
		
		$user_id = DB::quote(getUserID());
		
		$sql = "select stat.*, '' as actions, camp.campaign_id, camp.name, (case when camp.type = 1 then 'LP' when camp.type = 2 then 'Direct' end) as type, offer.name as offer_name from bt_c_statcache stat
			
			left join bt_u_campaigns camp on (stat.meta1=camp.campaign_id)
			
			left join bt_u_offers offer on (stat.meta3=offer.offer_id)
			
			where stat.user_id='$user_id' and stat.type='overview'
			
			";
			
		//$sql .= getReportOrder($cols,'stat.meta3 asc');
		
		$stats = DB::getRows($sql);
				
		foreach($stats as &$stat) {			
			if($stat['meta3']) {
				$stat['name'] = $stat['offer_name'];
				$stat['campaign_id'] = '';
				$stat['cpc'] = null;
				$stat['cost'] = null;
				$stat['net'] = null;
				$stat['roi'] = null;
				$stat['type'] = 'Offer';
			}
			else {
				$actions =  '<a class="button grey small" href="/tracker/code?campaign_id=' . $stat['campaign_id'] . '">Edit</a> ';
                $actions .= '<a class="button grey small" href="#" onclick="return clone_campaign(' . $stat['campaign_id'] . ');">Clone</a> ';
                $actions .= '<a class="button grey small" href="#" onclick="return delete_campaign(' . $stat['campaign_id'] . ');">Delete</a>';
                $stat['actions'] = $actions;
			}
		}
		
		return array('data'=>$stats,'cnt'=>count($stats),'cols'=>$cols);
	}
	
	public function dataOverviewAction() {
		extract($this->getDataOverview());
		
		echo getDatatablesReportJson($data,$cnt,$cols);
	}
	
	public function breakdownAction() {
		$this->useActionAsCurrentNav();
		
		$this->setVar("title", "Breakdown");
		$this->render("overview/breakdown");
	}
	
	public function getBreakdownData() {
		$cols = array('time','clicks','leads','conv','payout','epc','cpc','income','cost','net','roi');
		
		$start = (int)$_GET['iDisplayStart'];
		$length = (int)$_GET['iDisplayLength'];
		
		if($start == 0) {
			runBreakdown(true);
		}
		
		$cnt = DB::getVar("select count(*) from bt_c_statcache WHERE user_id='".DB::quote(getUserID())."' and type='breakdown'");
		
		$breakdown_sql = "SELECT * FROM bt_c_statcache WHERE user_id='".DB::quote(getUserID())."' and type='breakdown' limit $start,$length";  
		$breakdown_result = DB::getRows($breakdown_sql);
		
		$breakdown_type = BTAuth::user()->getPref('breakdown');
		
		foreach($breakdown_result as &$row) {
			$ex = explode('-',$row['time_from']);
			
			if ($breakdown_type == 'day') { 
				$row['time'] = date('M d, Y', mktime(0,0,0,$ex[1],$ex[2],$ex[0]));      
			} elseif ($breakdown_type == 'month') { 
				$row['time'] = date('M Y', mktime(0,0,0,$ex[1],1,$ex[0]));      
			} elseif ($breakdown_type == 'year') { 
				$row['time'] = date('Y', mktime(0,0,0,1,1,$ex[0]));      
			}
		}
		
		return array('data'=>$breakdown_result,'cols'=>$cols,'cnt'=>$cnt);
	}
	
	public function exportBreakdownAction() {
		doReportExport('breakdown',array($this,'getBreakdownData'),'csv','Time,Clicks,Leads,Conv %,Payout,EPC,CPC,Income,Cost,Net,ROI');
	}
	
	public function viewBreakdownAction() {
		$_POST['order'] = '';


		//show breakdown
		runBreakdown(true);

		//show real or filtered clicks
		$mysql['user_id'] = DB::quote(getUserID());
		$breakdown = BTAuth::user()->getPref('breakdown');

		//grab breakdown report	
		$breakdown_sql = "SELECT * FROM bt_c_statcache WHERE user_id='".$mysql['user_id']."' and type='breakdown' ";  
		$breakdown_result = DB::getRows($breakdown_sql);
		
		$this->setVar("breakdown",$breakdown);
		$this->setVar("breakdown_result",$breakdown_result);
		
		$this->loadView('overview/view_breakdown');
	}
	
	public function deleteCampaignAction() {
		$camp = CampaignModel::model()->getRowFromPk($_POST['campaign_id']);
		
		if($camp) {
			$camp->delete();
		}
	}

    public function copyCampaignAction() {
        if($_POST['campaign_id']) {
            CampaignModel::model()->duplicate($_POST['campaign_id']);
        }
    }
}
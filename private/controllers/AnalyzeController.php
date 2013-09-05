<?php

class AnalyzeController extends BTUserController {
	public function __construct() {
		require_once(BT_ROOT . '/private/includes/reporting/export.php');
	}
	
	public function IPsAction() {
		$this->setVar("title","Analyze IPs");
		
		$this->useActionAsCurrentNav();
		
		$this->render("analyze/ips");
	}
	
	public function exportIPsAction() {
		doReportExport('ips',array($this,'getIpData'),'csv','IP Address,Clicks,Leads,Conv %,Payout,EPC,Income');
	}
	
	public function ViewIPsAction() {		
		$this->loadView("analyze/view_ips");
	}
	
	public function getIpData() {
		$cols = array('ip_address','clicks','leads','conv','payout','epc','income');
	
		$cnt_query = "select count(1) from (select 1 from";
		
		$cnt_query .= getReportFilters('analyze/ips','left join bt_s_clicks_advanced as adv on (click.click_id=adv.click_id)');
		
		$cnt_query .= " and adv.ip_id>0 group by adv.ip_id) keydata";

		$cnt = DB::getVar($cnt_query);

		$sql = 'select ips.ip_address,';
		
		$sql .= getReportGeneralSelects() . 'from ';
		
		$sql .= getReportFilters('analyze/ips',' left join bt_s_clicks_advanced as adv on (click.click_id=adv.click_id) left join bt_s_ips ips on (adv.ip_id=ips.ip_id)');
		
		$sql .= ' and adv.ip_id>0 group by adv.ip_id ';
		
		$sql .= getReportOrder($cols);
		
		$sql .= getReportLimits();
		
		$keyword_result = DB::getRows($sql);
		
		foreach($keyword_result as &$keyword_row) {
			if (!$keyword_row['ip_address']) { 
				$keyword_row['ip_address'] = '[no ip]';    
			}
		}
		
		return array('data'=>$keyword_result,'cnt'=>$cnt,'cols'=>$cols);
	}
	
	public function dataIpsAction() {
		extract($this->getIpData());
		
		echo getDatatablesReportJson($data,$cnt,$cols);
	}
	
	public function KeywordsAction() {
		$this->setVar("title","Analyze Keywords");
		
		$this->useActionAsCurrentNav();
		
		$this->render("analyze/keywords");
	}
	
	public function ViewKeywordsAction() {		
		$this->loadView("analyze/view_keywords");
	}
	
	public function exportKeywordsAction() {
		doReportExport('keywords',array($this,'getKeywordsData'),'csv','Keyword,Clicks,Click Throughs,CTR,Leads,Conv %,Payout,EPC,Income');
	}
	
	public function getKeywordsData() {
		$cols = array('keyword','clicks','click_throughs','click_through_rates','leads','conv','payout','epc',
				'income');

		$cnt_query = "select count(1) from (select 1 from";
		
		$cnt_query .= getReportFilters('analyze/keywords', ' left join bt_s_clicks_advanced as adv on (click.click_id=adv.click_id) ');
		
		$cnt_query .= " and adv.keyword_id>0 group by adv.keyword_id) keydata";

		$cnt = DB::getVar($cnt_query);

		$sql = 'select keyword.keyword,';
		
		$sql .= getReportGeneralSelects() . 'from ';
		
		$sql .= getReportFilters('analyze/keywords','left join bt_s_clicks_advanced as adv on (click.click_id=adv.click_id) left join bt_s_keywords keyword on (adv.keyword_id=keyword.keyword_id)');
		
		$sql .= ' and adv.keyword_id>0 group by adv.keyword_id ';
		
		$sql .= getReportOrder($cols);
		
		$sql .= getReportLimits();
		
		$keyword_result = DB::getRows($sql);
		
		foreach($keyword_result as &$keyword_row) {
			if (!$keyword_row['keyword']) { 
				$keyword_row['keyword'] = '[no keyword]';    
			}
		}
		
		return array('data'=>$keyword_result,'cnt'=>$cnt,'cols'=>$cols);
	}
	
	public function dataKeywordsAction() {
		extract($this->getKeywordsData());
		
		echo getDatatablesReportJson($data,$cnt,$cols);
	}
	
	public function LPsAction() {
		$this->setVar("title","Analyze Landing Pages");
		
		$this->useActionAsCurrentNav();
		
		$this->render("analyze/landing_pages");
	}
	
	public function ViewLPsAction() {		
		$this->loadView("analyze/view_landing_pages");
	}
	
	public function getLpData() {
		$cols = array('name','clicks','click_throughs','click_through_rates','leads','conv','payout','epc','income');

		$cnt_query = "select count(1) from (select 1 from";
		
		$cnt_query .= getReportFilters('analyze/landing_page');
		
		$cnt_query .= " and click.landing_page_id>0 group by click.landing_page_id) lpdata";
		
		$cnt = DB::getVar($cnt_query);

		$sql = 'select lp.name, ';
		
		$sql .= getReportGeneralSelects() . 'from ';
		
		$sql .= getReportFilters('analyze/landing_page',' left join bt_u_landing_pages as lp on (lp.landing_page_id = click.landing_page_id) ');
		
		$sql .= ' and click.landing_page_id>0 group by click.landing_page_id ';
		
		$sql .= getReportOrder($cols);
		
		$sql .= getReportLimits();
				
		$result = DB::getRows($sql);
		
		return array('data'=>$result,'cnt'=>$cnt,'cols'=>$cols);
	}
	
	public function dataLPsAction() {
		extract($this->getLpData());
		
		echo getDatatablesReportJson($data,$cnt,$cols);
	}
	
	public function exportLpsAction() {
		doReportExport('landing_pages',array($this,'getLpData'),'csv','Landing Page,Clicks,Click Throughs,CTR,Leads,Conv %,Payout,EPC,Income');
	}
	
	public function ReferersAction() {
		$this->setVar("title","Analyze Referers");
		
		$this->useActionAsCurrentNav();
		
		$this->render("analyze/referers");
	}
	
	public function ViewReferersAction() {		
		$this->loadView("analyze/view_referers");
	}
	
	public function exportReferersAction() {
		doReportExport('referers',array($this,'getReferersData'),'csv','Referer,Clicks,Click Throughs,CTR,Leads,Conv %,Payout,EPC,Income');
	}
	
	public function getReferersData() {
		$cols = array('referer_domain','clicks','click_throughs','click_through_rates','leads','conv','payout','epc','income');

		$cnt_query = "select count(1) from (select 1 from";
		
		$cnt_query .= getReportFilters('analyze/referers',' left join bt_s_clicks_site site on site.click_id=click.click_id ');
		
		$cnt_query .= ' and site.referer_domain>\'\' group by site.referer_domain) refdata';
		
		$cnt = DB::getVar($cnt_query);

		$sql = 'select site.referer_domain, ';
		
		$sql .= getReportGeneralSelects() . 'from ';
		
		$sql .= getReportFilters('analyze/referers',' left join bt_s_clicks_site site on site.click_id=click.click_id ');
		
		$sql .= ' and site.referer_domain>\'\' group by site.referer_domain ';
		
		$sql .= getReportOrder($cols);
		
		$sql .= getReportLimits();
				
		$result = DB::getRows($sql);
		
		foreach($result as &$row) {
			if(!$row['referer_domain']) {
				$row['referer_domain'] = '[no referer]';
			}
		}
		
		return array('data'=>$result,'cnt'=>$cnt,'cols'=>$cols); 
	}
	
	public function dataReferersAction() {
		extract($this->getReferersData());
		
		echo getDatatablesReportJson($data,$cnt,$cols);
	}
	
	public function VariablesAction() {
		$this->setVar("title","Analyze Variables");
		
		$this->useActionAsCurrentNav();
		
		$this->render("analyze/variables");
	}
	
	public function ViewVariablesAction() {		
		$this->loadView("analyze/view_variables");
	}
	
	public function getSubIdData() {
		$cols = array('v1', 'v2', 'v3', 'v4', 'clicks', 'click_throughs', 'leads', 'conv', 'payout', 'epc', 'income');
		
		$cnt_query = "select count(1) from (select 1 from";
		
		$cnt_query .= getReportFilters('analyze/variables','left join bt_s_clicks_advanced as adv on (click.click_id=adv.click_id)');
		
		$cnt_query .= " and adv.v1_id>0 and adv.v2_id>0 and adv.v3_id>0 and adv.v4_id>0 GROUP BY adv.v1_id, adv.v2_id, adv.v3_id, adv.v4_id) thedata";
		
		$cnt = DB::getVar($cnt_query);

		$sql = 'select tv1.var_value as v1,
				tv2.var_value as v2,
				tv3.var_value as v3,
				tv4.var_value as v4,';
		
		$sql .= getReportGeneralSelects() . ' from ';
		
		$sql .= getReportFilters('analyze/variables','
			left join bt_s_clicks_advanced as adv on (click.click_id=adv.click_id)
			LEFT JOIN bt_s_variables AS tv1 on tv1.var_id=adv.v1_id
			LEFT JOIN bt_s_variables AS tv2 on tv2.var_id=adv.v2_id
			LEFT JOIN bt_s_variables AS tv3 on tv3.var_id=adv.v3_id
			LEFT JOIN bt_s_variables AS tv4 on tv4.var_id=adv.v4_id
		');
		
		$sql .= ' and adv.v1_id>0 and adv.v2_id>0 and adv.v3_id>0 and adv.v4_id>0 group by adv.v1_id, adv.v2_id, adv.v3_id, adv.v4_id ';
		
		$sql .= getReportOrder($cols);
		
		$sql .= getReportLimits();
		
		$keyword_result = DB::getRows($sql);
		
		return array('data'=>$keyword_result,'cnt'=>$cnt,'cols'=>$cols); 
	}
	
	public function exportVariablesAction() {
		doReportExport('subids',array($this,'getSubIdData'),'csv','Subid1,Subid2,Subid3,Subid4,Clicks,Click Throughs,Leads,Conv %,Payout,EPC,Income');
	}
	
	public function dataVariablesAction() {
		extract($this->getSubIdData());
				
		echo getDatatablesReportJson($data,$cnt,$cols);
	}
	
	public function clickidAction() {
		$this->setVar("title","Analyze Click ID");
		
		$this->useActionAsCurrentNav();
		
		$this->render("analyze/clickid");
	}
	
	public function viewClickidAction() {
		$clickid = $_POST['clickid'];
	
		$sql = "select 
		cl.time as `Click Time`,
		concat('$',cl.payout) as `Payout`,
		if(cl.lead=1,'True','False') as `Converted`,
		(case when (cl.filtered=0) then '' when (cl.filtered=1) then 'Affiliate Click' when (cl.filtered=2) then 'Repeat Visitor' end) as `Filtered`,
		concat(ad_net.ad_network_name,' : ',ad_acct.ad_account_name) as `Ad Account`,
		concat(camp_net.name,' : ',offer.name) as `Offer`,
		kw.keyword as `Keyword`,
		ip.ip_address as `IP Address`,
		adv.platform_id,
		adv.browser_id,
		v1.var_value as `Subid1`,
		v2.var_value as `Subid2`,
		v3.var_value as `Subid3`,
		v4.var_value as `Subid4`,
		concat(geo.city,', ',geo.state_full,', ',geo.country_full) as `Location`,
		coalesce(lp.name,'') as `Landing Page`,
		tracker.name as `Campaign`
		
		from bt_s_clicks cl
		
		left join bt_s_clicks_advanced adv on cl.click_id=adv.click_id
		left join bt_u_ad_accounts ad_acct on ad_acct.ad_account_id=cl.ad_account_id
		left join bt_u_offers camp on cl.offer_id=offer.offer_id
		left join bt_u_aff_networks camp_net on offer.aff_network_id=camp_net.aff_network_id
		left join bt_s_keywords kw on adv.keyword_id=kw.keyword_id
		left join bt_s_ips ip on adv.ip_id=ip.ip_id
		left join bt_u_campaigns tracker on tracker.campaign_id=adv.campaign_id
		
		left join bt_u_landing_pages lp on lp.landing_page_id=cl.landing_page_id
		
		left join bt_s_variables v1 on adv.v1_id=v1.var_id
		left join bt_s_variables v2 on adv.v2_id=v2.var_id
		left join bt_s_variables v3 on adv.v3_id=v3.var_id
		left join bt_s_variables v4 on adv.v4_id=v4.var_id
		
		left join bt_g_geo_locations geo on adv.location_id=geo.location_id
		
		where cl.click_id='" . DB::quote(base_convert($clickid,36,10)) . "' ";
	
		BTApp::firelog($sql);
		
		$data = DB::getRow($sql);
		
		if(!$data) {
			echo 'Invalid Click ID';
			BTApp::end();
		}
		
		$data['Platform'] = Browser::getPlatformName($data['platform_id']);
		unset($data['platform_id']);
		
		$data['Browser'] = Browser::getBrowserName($data['browser_id']);
		unset($data['browser_id']);
		
		$data['Click Time'] = date('Y-m-d H:i:s',$data['Click Time']);
		
		ksort($data); //alphabetize it
			
		$this->setVar('clickid',$clickid);
		$this->setVar("clickid_data",$data);
		
		$this->loadView("analyze/view_clickid");
	}
	
	public function lifetimeAction() {
		$this->setVar('title','Analyze Click Lifetime');
	
		$this->useActionAsCurrentNav();
	
		$this->render('analyze/lifetime');
	}
	
	public function viewlifetimeAction() {
		$this->loadView("analyze/view_lifetime");
	}
	
	private function LifetimeTime($time) {
		require_once(BT_ROOT . '/private/includes/traffic/lifetime.php');
		
		$intervals = clickLifetimeIntervals();
		
		return @$intervals[$time];
	}
	
	public function getLifetimeData() {
		$cols = array('lifetime', 'leads', 'payout', 'income');
		
		$cnt_query = "select count(1) from (select 1 from";
		
		$cnt_query .= getReportFilters('analyze/lifetime');
		
		$cnt_query .= " GROUP BY click.lifetime) thedata";
				
		$cnt = DB::getVar($cnt_query);

		$sql = 'select click.lifetime, ';
		
		$sql .= getReportGeneralSelects() . ' from ';
		
		$sql .= getReportFilters('analyze/lifetime','');
		
		$sql .= ' and lead=1 group by click.lifetime ';
		
		$sql .= getReportOrder($cols);
		
		$sql .= getReportLimits();
				
		$result = DB::getRows($sql);
		
		foreach($result as &$row) {
			$row['lifetime'] = $this->LifetimeTime($row['lifetime']);	
		}
		
		return array('data'=>$result,'cnt'=>$cnt,'cols'=>$cols); 
	}
	
	public function exportLifetimeAction() {
		doReportExport('lifetime',array($this,'getLifetimeData'),'csv','Lifetime,Leads,Payout,Income');
	}
	
	public function dataLifetimeAction() {
		extract($this->getLifetimeData());
		
		echo getDatatablesReportJson($data,$cnt,$cols);
	}
}
<?php

class PlatformsController extends BTUserController {
	public function __construct() {
		require_once(BT_ROOT . '/private/includes/reporting/export.php');
	}
	
	public function BrowserAction() {
		$this->setVar("title","Browser Breakdown");
		
		$this->useActionAsCurrentNav();
		
		$this->render("platforms/browser");
	}
	
	public function ViewBrowserAction() {		
		$this->loadView("platforms/view_browser");
	}
	
	public function getBrowserData() {
		$cols = array('meta1','clicks','click_throughs','click_through_rates','leads','conv','payout','epc','income');

		$sql = 'select adv.browser_id, ';
		
		$sql .= getReportGeneralSelects() . 'from ';
		
		$sql .= getReportFilters('platforms/browser','left join bt_s_clicks_advanced as adv on (click.click_id=adv.click_id)');
		
		$sql .= ' and adv.browser_id>0 group by adv.browser_id ';
								
		$result = DB::getRows($sql);
		
		$cnt = count($result);
		
		DB::query("delete from bt_c_statcache where user_id='" . DB::quote(getUserId()) . "'  and type='platbrow'");
		
		$bulk = new DB_Bulk_Insert('bt_c_statcache',array('user_id','type','clicks','click_throughs','click_through_rates','leads','conv','payout','epc','income','meta1'));
		
		foreach($result as $row) {
			$row['browser_name'] = Browser::getBrowserName($row['browser_id']);
			
			if(!$row['browser_name']) {
				$row['browser_name'] = '[unknown browser]';
			}
						
			$bulk->insert(array(DB::quote(getUserId()),"'platbrow'",$row['clicks'],$row['click_throughs'],$row['click_through_rates'],$row['leads'],$row['conv'],$row['payout'],$row['epc'],$row['income'],"'" . DB::quote($row['browser_name']) . "'"));
		}
		
		$bulk->execute();
		
		$sql = "select * from bt_c_statcache where user_id='" . DB::quote(getUserId()) . "'  and type='platbrow' ";
		
		$sql .= getReportOrder($cols);
		
		$sql .= getReportLimits();
		
		$result = DB::getRows($sql);
		
		return array('data'=>$result,'cnt'=>$cnt,'cols'=>$cols);
	}
	
	public function exportBrowserAction() {
		doReportExport('browsers',array($this,'getBrowserData'),'csv','Browser,Clicks,Click Throughs,CTR,Leads,Conv %,Payout,EPC,Income');
	}
	
	public function dataBrowserAction() {
		extract($this->getBrowserData());
		
		echo getDatatablesReportJson($data,$cnt,$cols);
	}
	
	public function OSAction() {
		$this->setVar("title","OS Breakdown");
		
		$this->useActionAsCurrentNav();
		
		$this->render("platforms/os");
	}
	
	public function ViewOSAction() {		
		$this->loadView("platforms/view_os");
	}
	
	public function getOSData() {
		$cols = array('meta1','clicks','click_throughs','click_through_rates','leads','conv','payout','epc','income');

		$sql = 'select adv.platform_id, ';
		
		$sql .= getReportGeneralSelects() . 'from ';
		
		$sql .= getReportFilters('platforms/os','left join bt_s_clicks_advanced as adv on (click.click_id=adv.click_id)');
		
		$sql .= ' and adv.platform_id > 0 group by adv.platform_id ';
								
		$result = DB::getRows($sql);
		
		$cnt = count($result);
		
		DB::query("delete from bt_c_statcache where user_id='" . DB::quote(getUserId()) . "'  and type='platos'");
		
		$bulk = new DB_Bulk_Insert('bt_c_statcache',array('user_id','type','clicks','click_throughs','click_through_rates','leads','conv','payout','epc','income','meta1'));
		
		foreach($result as $row) {
			$row['platform_name'] = Browser::getPlatformName($row['platform_id']);
			
			if(!$row['platform_name']) {
				$row['platform_name'] = '[unknown os]';
			}
						
			$bulk->insert(array(DB::quote(getUserId()),"'platos'",$row['clicks'],$row['click_throughs'],$row['click_through_rates'],$row['leads'],$row['conv'],$row['payout'],$row['epc'],$row['income'],"'" . DB::quote($row['platform_name']) . "'"));
		}
		
		$bulk->execute();
		
		$sql = "select * from bt_c_statcache where user_id='" . DB::quote(getUserId()) . "'  and type='platos' ";
		
		$sql .= getReportOrder($cols);
		
		$sql .= getReportLimits();
		
		$result = DB::getRows($sql);
		
		return array('data'=>$result,'cnt'=>$cnt,'cols'=>$cols);
	}
	
	public function exportOSAction() {
		doReportExport('os',array($this,'getOSData'),'csv','OS,Clicks,Click Throughs,CTR,Leads,Conv %,Payout,EPC,Income');
	}
	
	public function dataOSAction() {
		extract($this->getOSData());
		
		echo getDatatablesReportJson($data,$cnt,$cols);
	}
	
	public function MobileAction() {
		if(!bt_mobile_enabled()) {
			error404();
		}
		
		$this->setVar("title","Mobile Breakdown");
		
		$this->useActionAsCurrentNav();
		
		$this->render("platforms/mobile");
	}
	
	public function ViewMobileAction() {	
		if(!bt_mobile_enabled()) {
			error404();
		}
		
		$this->loadView("platforms/view_mobile");
	}
	
	public function getMobileData() {
		if(!bt_mobile_enabled()) {
			error404();
		}
		
		$groups = array();
		if(BTAuth::user()->getPref("user_mobile_breakdown_1")) {
			$groups[] = BTAuth::user()->getPref("user_mobile_breakdown_1");

			if(BTAuth::user()->getPref("user_mobile_breakdown_2")) {
				$groups[] = BTAuth::user()->getPref("user_mobile_breakdown_2");

				if(BTAuth::user()->getPref("user_mobile_breakdown_3")) {
					$groups[] = BTAuth::user()->getPref("user_mobile_breakdown_3");

					if(BTAuth::user()->getPref("user_mobile_breakdown_4")) {
						$groups[] = BTAuth::user()->getPref("user_mobile_breakdown_4");
					}
				}
			}
		}

		if(!$groups) {
			$groups = array('devices.type');
		}

		$breakdown_cols = array(
			"devices.brand"=>"Brand",
			"devices.type"=>"Device Type",
			"devices.os"=>"OS",
			"devices.os_version"=>"OS Version",
			"devices.browser"=>"Browser",
			"devices.browser_version"=>"Browser Version",
			"orgs.name"=>"Carrier/ISP"
		);

		foreach($groups as $group) {
			if(!isset($breakdown_cols[$group])) {
				$groups = array('devices.type');
				break;
			}
		}

		$group = " "; 
		$group .= implode(',',$groups);

		//$cols = array('browser_name', 'clicks', 'leads', 'conv', 'payout', 'epc', 'avg_cpc', 'income', 'cost', 'net', 'roi');
		$cols = array('label','clicks', 'click_throughs', 'click_through_rates', 'leads', 'conv', 'payout', 'epc', 'income');
		
		$sql = 'select ' . $group . ', ';
		
		$sql .= getReportGeneralSelects() . 'from ';
		
		$sql .= getReportFilters('platforms/mobile','left join bt_s_clicks_advanced as adv on (click.click_id=adv.click_id) LEFT JOIN bt_s_device_data AS devices on adv.device_id=devices.device_id
			left join bt_g_organizations as orgs on adv.org_id=orgs.org_id ');
		
		$sql .= " and adv.device_id>0 and devices.hash<>'d41d8cd98f00b204e9800998ecf8427e' group by " . $group;
		
		$sql .= ' order by ' . $group . ' ';
						
		$click_results = DB::getRows($sql); 

		for($i = 0;$i < count($groups);$i++) {
			if(($pos = strpos($groups[$i], '.'))) {
				$groups[$i] = substr($groups[$i],$pos + 1);
			}
		}

		$final_rows = array();
		$tree = makeHierarchical($click_results,$groups);

		unset($click_results); //no longer needed

		foreach($tree as $node) {
			getRowsFromTreeNode($node,0,$final_rows);
		}
				
		return array('data'=>$final_rows,'cols'=>$cols,'cnt'=>count($final_rows));
	}
	
	public function dataMobileAction() {
		extract($this->getMobileData());
		
		$output = array();

		for($i = 0;$i < count($data);$i++) {
			if($i < $_GET['iDisplayStart']) {
				continue;
			}
			else if($i >= ($_GET['iDisplayStart'] + $_GET['iDisplayLength'])) {
				break;
			}
			
			$output[] = $data[$i];
		}
				
		echo getDatatablesReportJson($output,$cnt,$cols);
	}
	
	public function exportMobileData() {
		extract($this->getMobileData());
		
		$output = array();

		for($i = 0;$i < count($data);$i++) {
			if($i < $_GET['iDisplayStart']) {
				continue;
			}
			else if($i >= ($_GET['iDisplayStart'] + $_GET['iDisplayLength'])) {
				break;
			}
			
			$output[] = $data[$i];
		}
				
		return array('data'=>$output,'cnt'=>$cnt,'cols'=>$cols);
	}
	
	public function exportMobileAction() {
		doReportExport('mobile',array($this,'exportMobileData'),'csv','Breakdown,Clicks,Click Throughs,CTR,Leads,Conv %,Payout,EPC,Income');
	}
}


/*****
 *THE FOLLOWING ARE ALL FOR MOBILE-BREAKDOWN. TO MAKE THE TREE DISPLAY AND SUCH
 ****/

function findAllRowsWithConditions($rows,$conditions) {
	$good = array();

	foreach($rows as $row) {
		$we_good = true; //assume it is good

		foreach($conditions as $cond=>$val) {		
			if($row[$cond] != $val) {
				$we_good = false;
			}
		}

		if($we_good) {
			$good[] = $row;
		}
	}

	return $good;
}

function getUniqueColumn($rows,$column) {
	$col_vals = array();

	foreach($rows as $row) {
		if(!isset($col_vals[$row[$column]])) {
			$col_vals[$row[$column]] = true;
		}
	}

	return array_keys($col_vals);
}

function makeHierarchical($rows,$groups) {		
	if(!$rows) {
		return array();
	}
	else if(!$groups) {
		return array('totals'=>$rows[0]);
	}

	$subtree = array();
	$group = $groups[0];
	array_shift($groups);

	$unique = getUniqueColumn($rows,$group);

	foreach($unique as $uniq) {
		$subrows = findAllRowsWithConditions($rows,array($group=>$uniq));

		$children = makeHierarchical($subrows,$groups);
		if(isset($children['totals'])) { //this is the leaf
			$subtree[] = array('label'=>$uniq,'children'=>array(),'totals'=>$children['totals']);
		}
		else {
			$totals = array(
				'clicks'=>0,
				'click_throughs'=>0,
				'leads'=>0,
				'payout'=>0,
				'income'=>0
			);

			foreach($children as $child) {
				$totals = doArraySum($totals,$child['totals']);
			}

			$subtree[] = array('label'=>$uniq,'children'=>$children,'totals'=>$totals);
		}
	}

	return $subtree;
}

function doArraySum($arr1,$arr2) {
	$cols = array_keys($arr1);

	foreach($cols as $col) {
		if(isset($arr2[$col])) {
			$arr1[$col] = $arr1[$col] + $arr2[$col];
		}
	}

	return $arr1;
}

function getRowsFromTreeNode($theNode,$depth,&$final_rows) {
	$row = $theNode['totals'];

	if(!$theNode['label']) {
		$theNode['label'] = "[Unknown]";
	}

	$row['label'] = str_repeat("&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;", $depth) . $theNode['label'];

	$row['click_through_rates'] = $row['click_throughs'] / $row['clicks'];
	$row['conv'] = $row['leads'] / $row['clicks'];
	$row['epc'] = $row['income'] / $row['clicks'];

	if(!$depth) {
		$row['highlight'] = 'breakdown_section';
	}
	else {
		$row['highlight'] = '';
	}

	$final_rows[] = $row;

	foreach($theNode['children'] as $node) {
		getRowsFromTreeNode($node,$depth + 1,$final_rows);
	}
}
<?php

class GeographyController extends BTUserController {
	public function __construct() {
		require_once(BT_ROOT . '/private/includes/reporting/export.php');
	}
	
	public function init() {
		parent::init();
		
		if(!bt_geo_enabled()) {
			error404();
		}
	}
	
	public function CitiesAction() {
		$this->setVar("title","City Breakdown");
		
		$this->useActionAsCurrentNav();
		
		$this->render("geography/cities");
	}
	
	public function ViewCitiesAction() {		
		$this->loadView("geography/view_cities");
	}
	
	public function getCitiesData() {
		$cols = array('country_full', 'state_full', 'city', 'clicks', 'click_throughs', 'click_through_rates', 'leads', 'conv', 'payout', 'epc', 'income');
		
		$cnt_query = "select count(1) from (select 1 from";
		
		$cnt_query .= getReportFilters('geography/cities','left join bt_s_clicks_advanced as adv on (click.click_id=adv.click_id) left join bt_g_geo_locations geo on adv.location_id=geo.location_id ');
		
		$cnt_query .= " group by geo.country, geo.state, geo.city) thedata";
		
		$cnt = DB::getVar($cnt_query);

		$sql = 'select geo.country_full, geo.state_full, geo.city, ';
		
		$sql .= getReportGeneralSelects() . 'from ';
		
		$sql .= getReportFilters('geography/cities','left join bt_s_clicks_advanced as adv on (click.click_id=adv.click_id) left join bt_g_geo_locations geo on adv.location_id=geo.location_id ');
		
		$sql .= ' group by geo.country, geo.state, geo.city ';
		
		$sql .= getReportOrder($cols);
		
		$sql .= getReportLimits();
				
		$result = DB::getRows($sql);
		
		foreach($result as &$row) {
			if(!$row['country_full']) {
				$row['country_full'] = '[unknown country]';
			}
			
			if(!$row['state_full']) {
				$row['state_full'] = '[unknown state]';
			}
			
			if(!$row['city']) {
				$row['city'] = '[unknown city]';
			}
		}
		
		return array('data'=>$result,'cnt'=>$cnt,'cols'=>$cols);
	}
	
	public function exportCitiesAction() {
		doReportExport('cities',array($this,'getCitiesData'),'csv','Countries,States,Cities,Clicks,Click Throughs,CTR,Leads,Conv %,Payout,EPC,Income');
	}
	
	public function DataCitiesAction() {		
		extract($this->getCitiesData());
		
		echo getDatatablesReportJson($data,$cnt,$cols);
	}
	
	public function CountriesAction() {
		$this->setVar("title","Country Breakdown");
		
		$this->useActionAsCurrentNav();
		
		$this->render("geography/countries");
	}
	
	public function ViewCountriesAction() {		
		$this->loadView("geography/view_countries");
	}
	
	public function exportCountriesAction() {
		doReportExport('countries',array($this,'getCountriesData'),'csv','Countries,Clicks,Click Throughs,CTR,Leads,Conv %,Payout,EPC,Income');
	}
	
	public function getCountriesData() {
		$cols = array('country_full', 'clicks', 'click_throughs', 'click_through_rates', 'leads', 'conv', 'payout', 'epc', 'income');
		
		$cnt_query = "select count(1) from (select 1 from";
		
		$cnt_query .= getReportFilters('geography/countries','left join bt_s_clicks_advanced as adv on (click.click_id=adv.click_id) left join bt_g_geo_locations geo on adv.location_id=geo.location_id ');
		
		$cnt_query .= " group by geo.country) thedata";
		
		$cnt = DB::getVar($cnt_query);

		$sql = 'select geo.country_full, ';
		
		$sql .= getReportGeneralSelects() . 'from ';
		
		$sql .= getReportFilters('geography/countries','left join bt_s_clicks_advanced as adv on (click.click_id=adv.click_id) left join bt_g_geo_locations geo on adv.location_id=geo.location_id ');
		
		$sql .= ' group by geo.country ';
		
		$sql .= getReportOrder($cols);
		
		$sql .= getReportLimits();
				
		$result = DB::getRows($sql);
		
		foreach($result as &$row) {
			if(!$row['country_full']) {
				$row['country_full'] = '[unknown country]';
			}
		}
		
		return array('data'=>$result,'cnt'=>$cnt,'cols'=>$cols);
	}
	
	public function dataCountriesAction() {
		extract($this->getCountriesData());
		
		echo getDatatablesReportJson($data,$cnt,$cols);
	}
	
	public function StatesAction() {
		$this->setVar("title","State Breakdown");
		
		$this->useActionAsCurrentNav();
		
		$this->render("geography/states");
	}
	
	public function ViewStatesAction() {		
		$this->loadView("geography/view_states");
	}
	
	public function exportStatesAction() {
		doReportExport('states',array($this,'getStatesData'),'csv','Countries,States,Clicks,Click Throughs,CTR,Leads,Conv %,Payout,EPC,Income');
	}
	
	public function dataStatesAction() {		
		extract($this->getStatesData());
		
		echo getDatatablesReportJson($data,$cnt,$cols);
	}
	
	public function getStatesData() {
		$cols = array('country_full', 'state_full', 'clicks', 'click_throughs', 'click_through_rates', 'leads', 'conv', 'payout', 'epc', 'income');
		
		$cnt_query = "select count(1) from (select 1 from";
		
		$cnt_query .= getReportFilters('geography/states','left join bt_s_clicks_advanced as adv on (click.click_id=adv.click_id) left join bt_g_geo_locations geo on adv.location_id=geo.location_id ');
		
		$cnt_query .= " group by geo.country, geo.state) thedata";
		
		$cnt = DB::getVar($cnt_query);

		$sql = 'select geo.country_full, geo.state_full, ';
		
		$sql .= getReportGeneralSelects() . 'from ';
		
		$sql .= getReportFilters('geography/states','left join bt_s_clicks_advanced as adv on (click.click_id=adv.click_id) left join bt_g_geo_locations geo on adv.location_id=geo.location_id ');
		
		$sql .= ' group by geo.country, geo.state ';
		
		$sql .= getReportOrder($cols);
		
		$sql .= getReportLimits();
				
		$result = DB::getRows($sql);
		
		foreach($result as &$row) {
			if(!$row['country_full']) {
				$row['country_full'] = '[unknown country]';
			}
			
			if(!$row['state_full']) {
				$row['state_full'] = '[unknown state]';
			}
		}
		
		return array('data'=>$result,'cnt'=>$cnt,'cols'=>$cols);
	}
	
	public function TimezonesAction() {
		$this->setVar("title","Timezone Breakdown");
		
		$this->useActionAsCurrentNav();
		
		$this->render("geography/time_zones");
	}
	
	public function ViewTimezonesAction() {		
		$this->loadView("geography/view_time_zones");
	}
	
	public function exportTimezoneAction() {
		doReportExport('timezone',array($this,'getTimezoneData'),'csv','Timezone,Clicks,Click Throughs,CTR,Leads,Conv %,Payout,EPC,Income');
	}
	
	public function getTimezoneData() {
		$cols = array('timezone', 'clicks', 'click_throughs', 'click_through_rates', 'leads', 'conv', 'payout', 'epc', 'income');
		
		$cnt_query = "select count(1) from (select 1 from";
		
		$cnt_query .= getReportFilters('geography/time_zones','left join bt_s_clicks_advanced as adv on (click.click_id=adv.click_id) left join bt_g_geo_locations geo on adv.location_id=geo.location_id ');
		
		$cnt_query .= " group by geo.timezone) thedata";
		
		$cnt = DB::getVar($cnt_query);

		$sql = 'select geo.timezone, ';
		
		$sql .= getReportGeneralSelects() . 'from ';
		
		$sql .= getReportFilters('geography/time_zones','left join bt_s_clicks_advanced as adv on (click.click_id=adv.click_id) left join bt_g_geo_locations geo on adv.location_id=geo.location_id ');
		
		$sql .= ' group by geo.timezone ';
		
		$sql .= getReportOrder($cols);
		
		$sql .= getReportLimits();
				
		$result = DB::getRows($sql);
		
		foreach($result as &$row) {
			if(!$row['timezone']) {
				$row['timezone'] = '[unknown timezone]';
			}
		}
		
		return array('data'=>$result,'cnt'=>$cnt,'cols'=>$cols);
	}
	
	public function dataTimezoneAction() {
		extract($this->getTimezoneData());
		
		echo getDatatablesReportJson($data,$cnt,$cols);
	}
}
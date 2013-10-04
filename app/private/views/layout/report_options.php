<?php

function display_mobile_breakdown($id) {
	if($id == 1) {
		$none_label = "--Group By--";
	}
	else {
		$none_label = "--Then Group By--";
	}
	
	$name = "user_mobile_breakdown_" . $id;
	
	$pref = BTAuth::user()->getPref($name);
	
	$opts = array(
		array('value'=>"0",'label'=>$none_label),
		array('value'=>"devices.brand",'label'=>"Brand"),
		array('value'=>"devices.type",'label'=>"Device Type"),
		array('value'=>"devices.os",'label'=>"OS"),
		array('value'=>"devices.os_version",'label'=>"OS Version"),
		array('value'=>"devices.browser",'label'=>"Browser"),
		array('value'=>"devices.browser_version",'label'=>"Browser Version"),
		array('value'=>"orgs.name",'label'=>"Carrier/ISP")
	);
		
	if($id == 1) { echo '<label>Mobile Breakdown</label>'; }
	else {
		echo "<label>&nbsp;</label>";
	}
		
	BTForm::createSelect($name, $opts, $pref);
}

$opt_path = explode('/',$opts);

if(!($node = simplexml_load_file(BT_ROOT . '/private/config/report_options.xml'))) {
	echo 'Invalid report configuration';
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

$page = $page;

//default false. We tell it what we need
$default_opts = array(
	'show_time'=>false,
	'show_traffic_source'=>false,
	'show_bottom'=>false,
	'show_breakdown'=>false,
	'show_bottom_advanced'=>false,
	'show_mobile_breakdown'=>false,
	'show_type'=>false,
	'show_campaign'=>false,
    'show_status'=>false,
    'show_group'=>false,
    'show_timezone'=>false,
    'show_custom_report'=>false,
    'show_button_report'=>false
);

$show_options = array_merge($default_opts,$opt_arr);
$time = grab_timeframe();   
$html['from'] = date('m/d/Y', $time['from']);
$html['to'] = date('m/d/Y', $time['to']);  
$html['page'] = BTHtml::encode($page); ?>


<form onsubmit="return false;" id="user_prefs" novalidate="novalidate">
	<input type="hidden" name="opt_setting" value="<?php echo $opts; ?>"/>
    <div class="grid_12">
        <div class="grid_2">
            <div class="box">
                <select class="" name="time_predefined" id="time_predefined" onchange="set_time_predefined();">
                    <option value="">Custom Date</option>
                    <option <?php if ($time['time_predefined'] == 'today') { echo 'selected=""'; } ?> value="today">Today</option>
                    <option <?php if ($time['time_predefined'] == 'yesterday') { echo 'selected=""'; } ?> value="yesterday">Yesterday</option>
                    <option <?php if ($time['time_predefined'] == 'last7') { echo 'selected=""'; } ?> value="last7">Last 7 Days</option>
                    <option <?php if ($time['time_predefined'] == 'last14') { echo 'selected=""'; } ?> value="last14">Last 14 Days</option>
                    <option <?php if ($time['time_predefined'] == 'last30') { echo 'selected=""'; } ?> value="last30">Last 30 Days</option>
                    <option <?php if ($time['time_predefined'] == 'thismonth') { echo 'selected=""'; } ?> value="thismonth">This Month</option>
                    <option <?php if ($time['time_predefined'] == 'lastmonth') { echo 'selected=""'; } ?> value="lastmonth">Last Month</option>
                </select>
                <label class="tooltip" title="MM/DD/YYYY">Start Date:</label> <input class="date" onchange="unset_time_predefined();" type="text" name="from" id="from" value="<?php echo $html['from']; ?>" />
                <label class="tooltip"title="MM/DD/YYYY">End Date:</label> <input class="date" onchange="unset_time_predefined();" type="text" name="to" id="to" value="<?php echo $html['to']; ?>"  />
            </div>
        </div>
	
        <?php if($show_options['show_campaign']) { ?>
            <div class="grid_2">
                <div class="box">
                    <label>Campaign</label>
                    <?php
                        BTForm::createSelect('campaign_id',CampaignModel::model()->getRows(),
                                             BTAuth::user()->getPref('campaign_id'),'campaign_id','','name','campaign_id','Show All');
                    ?>
                </div>
            </div>
        <?php } ?>

        <?php if($show_options['show_type']) { ?>
            <div class="grid_2">
                <div class="box">
                    <label>Type</label>
                    <?php
                        BTForm::createSelect('campaign_type',
                                             array(array('label'=>'Landing Page','value'=>'lp'),array('label'=>'Direct Link','value'=>'direct')),
                                             BTAuth::user()->getPref('campaign_type'),'campaign_type','','label','value','Show All');
                    ?>
                </div>
            </div>
        <?php } ?>
	
        <?php if($show_options['show_traffic_source']) { ?>
            <div class="grid_2">
                <div class="box">
                    <label>Traffic Source</label>

                    <?php
                        $this->loadModel('TrafficSourceModel');
                        BTForm::createSelect('traffic_source_id',TrafficSourceModel::model()->getRows(),BTAuth::user()->getPref('traffic_source_id'),'traffic_source_id','','name','traffic_source_id','Show All');
                    ?>
                </div>
            </div>
        <?php } ?>

        <?php if($show_options['show_breakdown']) { ?>
            <div class="grid_2">
                <div class="box">
                    <label>Breakdown</label>
                    <select name="breakdown">
                        <option <?php if (BTAuth::user()->getPref('breakdown') == 'day') { echo 'SELECTED'; } ?> value="day">Daily</option>
                        <option <?php if (BTAuth::user()->getPref('breakdown') == 'month') { echo 'SELECTED'; } ?> value="month">Monthly</option>
                        <option <?php if (BTAuth::user()->getPref('breakdown') == 'year') { echo 'SELECTED'; } ?> value="year">Yearly</option>
                    </select>
                </div>
            </div>
        <?php } ?>
	
        <?php if($show_options['show_bottom']) { ?>
            <div class="grid_2">
                <div class="box">
                    <label>Filter</label>
                    <select name="click_filter">
                        <option <?php if (BTAuth::user()->getPref('click_filter') == 'all') { echo 'SELECTED'; } ?> value="all">Show All Clicks</option>
                        <option <?php if (BTAuth::user()->getPref('click_filter') == 'real') { echo 'SELECTED'; } ?> value="real">Hide Filtered Clicks</option>
                    </select>
                </div>
            </div>
        <?php } ?>
	
        <?php if($show_options['show_mobile_breakdown']) { ?>
            <div class="grid_2">
                <div class="box">
                    <?php display_mobile_breakdown(1); ?>

                    <?php display_mobile_breakdown(2); ?>

                    <?php display_mobile_breakdown(3); ?>

                    <?php display_mobile_breakdown(4); ?>
                </div>
            </div>
        <?php } ?>

        <?php if($show_options['show_status']) { ?>
            <div class="grid_2">
                <div class="box">
                    <label>Status</label>
                    <select name="status">
                        <option value="0">All Campaigns</option>
                        <option value="1">Active Campaigns</option>
                        <option value="2">Inactive Campaigns</option>
                    </select>
                </div>
            </div>
        <?php } ?>

        <?php if($show_options['show_group']) { ?>
            <div class="grid_2">
                <div class="box">
                    <label>Group</label>
                    <select name="group">
                        <option value="0">All Groups</option>
                        <option value="1">Unassigned</option>
                    </select>
                </div>
            </div>
        <?php } ?>

        <?php if($show_options['show_timezone']) { ?>
            <div class="grid_2">
                <div class="box">
                    <label>Default Time - America/Anchorage</label>
                    <select name="timezone">
                        <?php
                        static $regions = array(
                            'Africa' => DateTimeZone::AFRICA,
                            'America' => DateTimeZone::AMERICA,
                            'Antarctica' => DateTimeZone::ANTARCTICA,
                            'Arctic' => DateTimeZone::ARCTIC,
                            'Asia' => DateTimeZone::ASIA,
                            'Atlantic' => DateTimeZone::ATLANTIC,
                            'Australia' => DateTimeZone::AUSTRALIA,
                            'Europe' => DateTimeZone::EUROPE,
                            'Indian' => DateTimeZone::INDIAN,
                            'Pacific' => DateTimeZone::PACIFIC,
                            'UTC' => DateTimeZone::UTC,
                        );
                        $tzlist = array();
                        foreach ($regions as $name => $mask) {
                            $tzlist[] = DateTimeZone::listIdentifiers($mask);
                        }
                        foreach($tzlist as $tzregion) {
                            foreach($tzregion as $zone) {
                                if($zone == BTAuth::user()->timezone) {
                                    echo '<option selected="selected" value="' . $zone . '">' . $zone . '</option>' . PHP_EOL;
                                } else {
                                    echo '<option value="' . $zone . '">' . $zone . '</option>' . PHP_EOL;
                                }
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>
        <?php } ?>

    </div>

    <?php if($show_options['show_custom_report']) { ?>
        <div class="grid_12">
        <div id="reportOptions" class="box">
            <h3>Report Options</h3>
            <div class="reportOptions">
                <input type="checkbox" name="cvr" value="1">
                <p class="reportSelect">Restrict to Converting Subids</p>
                <input type="checkbox" name="opt" value="1">
                <p class="reportSelect">Restrict to Opt-ins</p>
                <input type="checkbox" name="filtered" value="1">
                <p class="reportSelect">Include Filtered Clicks</p>
            </div>
            <div class="reportOptions">
                <input type="checkbox" id="allData">
                <p class="reportSelect">Select All Options</p>
            </div>

            <fieldset id="clickData">
                <legend>Click Data</legend>
                <div class="reportOptions">
                    <input type="checkbox" class="selectall"/>
                    <p class="reportSelect">Select All Click Data</p>
                </div>
                <div class="reportOptions">
                    <input type="checkbox" name="clickData[]" value="click.click_id"><p>Click ID</p>
                    <input type="checkbox" name="clickData[]" value="click.time"><p>Timestamp</p>
                    <input type="checkbox" name="clickData[]" value=""><p>Date/Time</p>
                    <input type="checkbox" name="clickData[]" value="ip_address"><p>IP Address</p>
                    <input type="checkbox" name="clickData[]" value="cs.referer_url"><p>Referer</p>
                    <input type="checkbox" name="clickData[]" value="adv.browser_id"><p>User Agent</p>
                </div>
            </fieldset>

            <fieldset>
                <legend>Campaign Data</legend>
                <div class="reportOptions">
                    <input type="checkbox" class="selectall"/>
                    <p class="reportSelect">Select All Campaign Data</p>
                </div>
                <div class="reportOptions">
                    <input type="checkbox" name="campaignData[]" value="c.campaign_id"><p>Campaign ID</p>
                    <input type="checkbox" name="campaignData[]" value="c.name as cName"><p>Campaign Name</p>
                    <input type="checkbox" name="campaignData[]" value=""><p>CPC</p>
                    <input type="checkbox" name="campaignData[]" value="o.name as oName"><p>Offer Name</p>
                    <input type="checkbox" name="campaignData[]" value="click.lead"><p>Lead</p>
                    <input type="checkbox" name="campaignData[]" value="o.payout"><p>Payout</p>
                </div>
            </fieldset>

            <fieldset>
                <legend>Device Data</legend>
                <div class="reportOptions">
                    <input type="checkbox" class="selectall"/>
                    <p class="reportSelect">Select All Device Data</p>
                </div>
                <div class="reportOptions">
                    <input type="checkbox" name="deviceData[]" value="d.brand"><p>Name</p>
                    <input type="checkbox" name="deviceData[]" value="d.type as model"><p>Model</p>
                    <input type="checkbox" name="deviceData[]" value="d.type"><p>Device Type</p>
                    <input type="checkbox" name="deviceData[]" value="d.os"><p>Operating System</p>
                </div>
            </fieldset>

            <fieldset>
                <legend>Carrier Data</legend>
                <div class="reportOptions">
                    <input type="checkbox" class="selectall"/>
                    <p class="reportSelect">Select All Carrier Data</p>
                </div>
                <div class="reportOptions">
                    <input type="checkbox" name="carrier"><p>Carrier</p>
                    <input type="checkbox" name="isp"><p>ISP</p>
                    <input type="checkbox" name="code"><p>Country Code</p>
                    <input type="checkbox" name="country"><p>Country Name</p>
                </div>
            </fieldset>

            <fieldset>
                <legend>Token Data</legend>
                <div class="reportOptions">
                    <input type="checkbox" class="selectall" name="tokenData" value="1"/>
                    <p class="reportSelect">Select All Token Data</p>
                </div>
            </fieldset>
        </div>
        </div>
        <div class="grid_12">
            <div class="box" style="border: none; background-color: transparent; padding: 0;box-shadow: none;">
                <button id="generate_custom_report" class="blue">Create Report</button>
            </div>
        </div>
    <?php } else { ?>
        <div class="grid_12">
            <div class="box" style="border: none; background-color: transparent; padding: 0;box-shadow: none;">
                <input type="submit" id="s-search" class="blue" onclick="set_user_prefs('<?php echo $html['page']; ?>');" value="Save And Reload"/>
            </div>
        </div>
    <?php } ?>
			
</form>

<div id="m-content"></div>                                

<script type="text/javascript">

	/* TIME SETTING FUNCTION */ 
	function set_time_predefined() {	
		var fromdate = 0;
		var todate = 0;

		if($('#time_predefined').val() == 'today') {
			<?php  
				echo 'fromdate = "' . date('m/d/Y',mktime(0,0,0,date('m',time()),date('d',time()),date('Y',time()))) . '";';
				echo 'todate = "' . date('m/d/Y',mktime(23,59,59,date('m',time()),date('d',time()),date('Y',time()))) . '";'; 
			?> 
		}

		if($('#time_predefined').val() == 'yesterday') {
			<?php  
				echo 'fromdate = "' . date('m/d/Y',mktime(0,0,0,date('m',time()-86400),date('d',time()-86400),date('Y',time()-86400))) . '";';
				echo 'todate = "' . date('m/d/Y',mktime(23,59,59,date('m',time()-86400),date('d',time()-86400),date('Y',time()-86400))) . '";'; 
			?>
		}

		if($('#time_predefined').val() == 'last7') {
			<?php  
				echo 'fromdate = "' . date('m/d/Y',mktime(0,0,0,date('m',time()-86400*7),date('d',time()-86400*7),date('Y',time()-86400*7))) . '";';
				echo 'todate = "' . date('m/d/Y',mktime(23,59,59,date('m',time()),date('d',time()),date('Y',time()))) . '";';  
			?>
		}

		if($('#time_predefined').val() == 'last14') {
			<?php  
				echo 'fromdate = "' . date('m/d/Y',mktime(0,0,0,date('m',time()-86400*14),date('d',time()-86400*14),date('Y',time()-86400*14))) . '";';
				echo 'todate = "' . date('m/d/Y',mktime(23,59,59,date('m',time()),date('d',time()),date('Y',time()))) . '";';  
			?>
		}

		if($('#time_predefined').val() == 'last30') {
			<?php  
				echo 'fromdate = "' . date('m/d/Y',mktime(0,0,0,date('m',time()-86400*30),date('d',time()-86400*30),date('Y',time()-86400*30))) . '";';
				echo 'todate = "' . date('m/d/Y',mktime(23,59,59,date('m',time()),date('d',time()),date('Y',time()))) . '";';    
			?>
		}

		if($('#time_predefined').val() == 'thismonth') {
			<?php  
				echo 'fromdate = "' . date('m/d/Y',mktime(0,0,0,date('m',time()),1,date('Y',time()))) . '";';
				echo 'todate = "' . date('m/d/Y',mktime(23,59,59,date('m',time()),date('d',time()),date('Y',time()))) . '";';   
			?>
		}

		if($('#time_predefined').val() == 'lastmonth') {
			<?php  
				echo 'fromdate = "' . date('m/d/Y',mktime(0,0,0,date('m',time()-2629743),1,date('Y',time()-2629743))) . '";';
				echo 'todate = "' . date('m/d/Y',mktime(23,59,59,date('m',time()-2629743),getLastDayOfMonth(date('m',time()-2629743), date('Y',time()-2629743)),date('Y',time()-2629743))) . '";';   
			?> 
		}

		if($('#time_predefined').val() == 'thisyear') {
			<?php  
				echo 'fromdate = "' . date('m/d/Y',mktime(0,0,0,1,1,date('Y',time()))) . '";';
				echo 'todate = "' . date('m/d/Y',mktime(23,59,59,date('m',time()),date('d',time()),date('Y',time()))) . '";';   
			?>
		}

		if($('#time_predefined').val() == 'lastyear') {
			<?php  
				echo 'fromdate = "' . date('m/d/Y',mktime(0,0,0,1,1,date('Y',time()-31556926))) . '";';
				echo 'todate = "' . date('m/d/Y',mktime(0,0,0,12,getLastDayOfMonth(date('m',time()-31556926), date('Y',time()-31556926)),date('Y',time()-31556926))) . '";';  
			?> 
		}

		if($('#time_predefined').val() == 'alltime') {
			<?php  
				echo 'fromdate = "' . date('m/d/Y',mktime(0,0,0,1,1,2000)) . '";';  
				echo 'todate = "' . date('m/d/Y',mktime(23,59,59,date('m',time()),date('d',time()),date('Y',time()))) . '";';    
			?>
		}

		$('#from').val(fromdate);
		$('#to').val(todate);  
	}

	loadContent('<?php echo $page; ?>');
</script>
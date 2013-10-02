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
	'show_campaign'=>false
);

$show_options = array_merge($default_opts,$opt_arr);
$time = grab_timeframe();   
$html['from'] = date('m/d/Y', $time['from']);
$html['to'] = date('m/d/Y', $time['to']);  
$html['page'] = BTHtml::encode($page); ?>


<form onsubmit="return false;" id="user_prefs" class="grid_12" novalidate="novalidate">
	<input type="hidden" name="opt_setting" value="<?php echo $opts; ?>"/>

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
				
	<div class="grid_2">
		<div class="box" style="border: none; background-color: transparent; padding: 0;box-shadow: none;">
			<input type="submit" id="s-search" class="blue" onclick="set_user_prefs('<?php echo $html['page']; ?>');" value="Save And Reload"/>
		</div>
	</div>
			
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
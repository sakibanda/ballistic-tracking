<?php

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
    'show_bottom_advanced'=>false,
    'show_type'=>false,
    'show_campaign'=>false,
    'show_status'=>false,
    'show_group'=>false,
    'show_timezone'=>false,
    'show_button_report'=>false
);

$show_options = array_merge($default_opts,$opt_arr);

$time = grab_timeframe();
$html['from'] = date('m/d/Y', $time['from']);
$html['to'] = date('m/d/Y', $time['to']);
$html['page'] = BTHtml::encode($page); ?>


<form onsubmit="return false;" id="user_prefs" class="grid_12" novalidate="novalidate">
    <input type="hidden" name="opt_setting" value="<?php echo $opts; ?>"/>


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
                            }
                            else {
                                echo '<option value="' . $zone . '">' . $zone . '</option>' . PHP_EOL;
                            }
                        }
                    }

                    ?>
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
</form>

<form method="post" action="">
    <div id="reportOptions">
        <h3>Report Options</h3>
        <div class="reportOptions">
            <input class="reportBox" name="cvr" id="cvr" value="1" type="checkbox">
            <p class="reportSelect">Restrict to Converting Subids</p>
            <input class="reportBox" name="opt" id="opt" value="1" type="checkbox">
            <p class="reportSelect">Restrict to Opt-ins</p>
            <input class="reportBox" name="filtered" id="filtered" value="1" type="checkbox">
            <p class="reportSelect">Include Filtered Clicks</p>
        </div>
        <div class="reportOptions">
            <input class="reportBox" name="allData" id="allData" value="1" onclick="setAllData();" type="checkbox">
            <p class="reportSelect">Select All Options</p>
        </div>

        <fieldset>
            <legend>Click Data</legend>
            <div class="reportOptions">
                <input class="reportBox" name="clickData" id="clickData" value="1" onclick="setReportData(1,0);" type="checkbox">
                <p class="reportSelect">Select All Click Data</p>
            </div>
            <div class="reportOptions">
                <input class="reportBox" name="clickData[]" id="sid" value="click.click_id" type="checkbox"><p class="reportLabel">Click ID</p>
                <input class="reportBox" name="clickData[]" id="ts" value="click.time" type="checkbox"><p class="reportLabel">Timestamp</p>
                <input class="reportBox" name="clickData[]" id="dt" value="datetime as DataTime" type="checkbox"><p class="reportLabel">Date/Time</p>
                <input class="reportBox" name="clickData[]" id="ip" value="ip_address" type="checkbox"><p class="reportLabel">IP Address</p>
                <input class="reportBox" name="clickData[]" id="ref" value="cs.referer_url" type="checkbox"><p class="reportLabel">Referer</p>
                <input class="reportBox" name="clickData[]" id="ua" value="adv.browser_id" type="checkbox"><p class="reportLabel">User Agent</p>
            </div>
        </fieldset>

        <fieldset>
            <legend>Campaign Data</legend>
            <div class="reportOptions">
                <input class="reportBox" name="campData" id="campData" value="1" onclick="setReportData(2,0);" type="checkbox">
                <p class="reportSelect">Select All Campaign Data</p>
            </div>
            <div class="reportOptions">
                <input class="reportBox" name="campaignData[]" id="cid" value="c.campaign_id" type="checkbox"><p class="reportLabel">Campaign ID</p>
                <input class="reportBox" name="campaignData[]" id="cn" value="c.name as cName" type="checkbox"><p class="reportLabel">Campaign Name</p>
                <input class="reportBox" name="campaignData[]" id="cpc" value="cCPC" type="checkbox"><p class="reportLabel">CPC</p>
                <input class="reportBox" name="campaignData[]" id="on" value="o.name oName" type="checkbox"><p class="reportLabel">Offer Name</p>
                <input class="reportBox" name="campaignData[]" id="ld" value="click.lead" type="checkbox"><p class="reportLabel">Lead</p>
                <input class="reportBox" name="campaignData[]" id="po" value="o.payout" type="checkbox"><p class="reportLabel">Payout</p>
            </div>
        </fieldset>

        <fieldset>
            <legend>Device Data</legend>
            <div class="reportOptions">
                <input class="reportBox" name="deviceData" id="deviceData" value="1" onclick="setReportData(3,0);" type="checkbox">
                <p class="reportSelect">Select All Device Data</p>
            </div>
            <div class="reportOptions">
                <input class="reportBox" name="deviceData[]" id="name" value="d.brand" type="checkbox"><p class="reportLabel">Name</p>
                <input class="reportBox" name="deviceData[]" id="mdl" value="d.type as model" type="checkbox"><p class="reportLabel">Model</p>
                <input class="reportBox" name="deviceData[]" id="type" value="d.type" type="checkbox"><p class="reportLabel">Device Type</p>
                <input class="reportBox" name="deviceData[]" id="os" value="d.os" type="checkbox"><p class="reportLabel">Operating System</p>
            </div>
        </fieldset>

        <fieldset>
            <legend>Carrier Data</legend>
            <div class="reportOptions">
                <input class="reportBox" name="carrierData" id="carrierData" value="1" onclick="setReportData(4,0);" type="checkbox">
                <p class="reportSelect">Select All Carrier Data</p>
            </div>
            <div class="reportOptions">
                <input class="reportBox" name="carrier" id="carrier" value="1" type="checkbox"><p class="reportLabel">Carrier</p>
                <input class="reportBox" name="isp" id="isp" value="1" type="checkbox"><p class="reportLabel">ISP</p>
                <input class="reportBox" name="code" id="code" value="1" type="checkbox"><p class="reportLabel">Country Code</p>
                <input class="reportBox" name="country" id="country" value="1" type="checkbox"><p class="reportLabel">Country Name</p>
            </div>
        </fieldset>

        <fieldset>
            <legend>Token Data</legend>
            <div class="reportOptions">
                <input class="reportBox" name="tokenData" id="tokenData" value="1" onclick="setReportData(5,0);" type="checkbox">
                <p class="reportSelect">Select All Token Data</p>
            </div>
        </fieldset>
    </div>

    <div class="box" style="border: none; background-color: transparent; padding: 0;box-shadow: none;">
        <input type="submit" id="s-search" style="float:center;" class="blue" value="Create Report"/>
    </div>
</form>
<?php
    if(isset($_POST['deviceData'])){
    $user_id = DB::quote(getUserID());
    $sql_report = "SELECT ";
    $title_table = "";
    if(!empty($_POST['clickData'])) {
        foreach($_POST['clickData'] as $option){
            $sql_report .="$option,";
            $title_table .= "<th>".$option."</th>";

        }
    }
    if(!empty($_POST['campaignData'])) {
        foreach($_POST['campaignData'] as $option){
            $sql_report .="$option,";
            $title_table .= "<th>".$option."</th>";
        }
    }

    if(!empty($_POST['deviceData'])) {
        foreach($_POST['deviceData'] as $option){
            $sql_report .="$option,";
            $title_table .= "<th>".$option."</th>";
        }
    }
    $sql_report = trim($sql_report, ',');

    $sql_report.=" FROM
    bt_u_campaigns AS c
        JOIN
    bt_u_campaign_offers co ON (co.campaign_id = c.campaign_id)
        JOIN
    bt_u_offers o ON (co.offer_id = o.offer_id)
        JOIN
    bt_s_clicks click ON (click.campaign_id = c.campaign_id)
        JOIN
    bt_s_clicks_site cs USING (click_id)
        JOIN
    bt_s_clicks_advanced adv USING (click_id)
        JOIN
    bt_s_ips ON (bt_s_ips.ip_id = adv.ip_id)
        JOIN
    bt_s_device_data d ON (d.device_id = adv.platform_id)";

    $sql_report .=" WHERE
    c.campaign_id = '18' AND c.deleted = 0 AND c.user_id = '$user_id' limit 3   ";


    echo "SQL__: ".$sql_report;

    $report_rows = DB::getRows($sql_report);

        //echo mysql_field_name($report_rows, 1);

        echo "<table border='1'>";
        echo "<tr>".$title_table."</tr>";

        foreach($report_rows as $row){
            echo "<tr>";
            for($columns = 0;$columns<count($row);$columns++){
                echo "<td>" . $row['brand'] . "</td>";
            }
            echo "</tr>";
         }

//        foreach($report_rows as $row){
//            echo "<tr>";
//                for($columns = 0;$columns<=count($row);$columns++){
//                    echo "<td>" . $row . "</td>";
//                }
//                echo "</tr>";
//         }
        echo "</table>";
}
?>


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

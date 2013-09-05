<?php

function upgradeSql($version,$sql) {
	if(!DB::query($sql)) {
		echo "Upgrade to $version failed at: " . $sql;
		exit;
	}
}

function upgradeVersion($version) {
	$query = "update bt_g_version set version='$version'";
	upgradeSql($version,$query);
	
	echo "Upgraded to $version<br><br>";
	
	return $version;
}

define('BT_IS_ROUTED',false);
define('BYPASS_CONFIG_LOCATION','/bt-config/conf.php');

require_once(__DIR__. '/private/includes/BTApp.php');

$cur_ver = DB::getVar("select version from bt_g_version");

if($cur_ver == '13.04.10') {
	$new = '13.04.11';
	
	$sql = "CREATE TABLE `bt_s_variables` (
		`var_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
		`var_value` varchar(100) CHARACTER SET utf8 NOT NULL,
		PRIMARY KEY (`var_id`),
		UNIQUE KEY `value` (`var_value`)
	  ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8";

	upgradeSql($new,$sql);
	
	upgradeSql($new,"insert into bt_s_variables set var_value=''");
	$id = DB::quote(DB::insertId());
	upgradeSql($new,"update bt_s_clicks_advanced set v1_id='$id', v2_id='$id',v3_id='$id',v4_id='$id'");
	
	upgradeSql($new,"drop table bt_u_tracking_v1");
	upgradeSql($new,"drop table bt_u_tracking_v2");
	upgradeSql($new,"drop table bt_u_tracking_v3");
	upgradeSql($new,"drop table bt_u_tracking_v4");
	
	$cur_ver = upgradeVersion($new);
}

if($cur_ver == '13.04.11') {
	$new = '13.04.12';
	
	upgradeSql($new,"drop table bt_g_cloaker_orgs");
	upgradeSql($new,"drop table bt_g_pixel_types");
	upgradeSql($new,"drop table bt_g_browsers");
	upgradeSql($new,"drop table bt_g_platforms");
	
	$cur_ver = upgradeVersion($new);
}

if($cur_ver == '13.04.12') {
	$new = '13.04.13';
	
	upgradeSql($new,"drop table bt_s_usersession");
	
	upgradeSql($new,"ALTER TABLE `bt_s_clicks_site` ADD COLUMN `site_referer_url` TEXT NOT NULL  AFTER `site_landing_id` ,
			   ADD COLUMN `site_referer_domain` VARCHAR(100) NOT NULL  AFTER `site_referer_url` , ADD COLUMN `site_offer_url` TEXT NOT NULL  AFTER `site_referer_domain` ,
			   ADD COLUMN `site_landing_url` TEXT NOT NULL  AFTER `site_offer_url`");

	upgradeSql($new,"update bt_s_clicks_site site left join bt_s_site_urls url on (site.site_referer_id=url.site_url_id) left join bt_s_site_domains dom on (dom.site_domain_id=url.site_domain_id) set site.site_referer_url=site_url_address, site_referer_domain=site_domain_host");
	upgradeSql($new,"update bt_s_clicks_site site left join bt_s_site_urls url on (site.site_offer_id=url.site_url_id) set site.site_offer_url=site_url_address");
	upgradeSql($new,"update bt_s_clicks_site site left join bt_s_site_urls url on (site.site_landing_id=url.site_url_id) set site.site_landing_url=site_url_address");
	
	upgradeSql($new,"drop table bt_s_site_urls");
	upgradeSql($new,"drop table bt_s_site_domains");
	
	upgradeSql($new,"ALTER TABLE `bt_s_clicks_site` DROP COLUMN `site_landing_id` , DROP COLUMN `site_offer_id` , DROP COLUMN `site_cloaking_id` , DROP COLUMN `site_referer_id` 
, DROP INDEX `click_referer_site_url_id`");

	$cur_ver = upgradeVersion($new);
}

if($cur_ver == '13.04.13') {
	$new = '13.04.14';
	
	upgradeSql($new,"drop table bt_u_cloakers");
	upgradeSql($new,"drop table bt_u_cloaker_options");
	upgradeSql($new,"CREATE TABLE `bt_g_cloaker_orgs` (
  `org_id` int(10) unsigned NOT NULL,
  `org_name` varchar(100) NOT NULL,
  PRIMARY KEY (`org_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8");
	
	upgradeSql($new,"CREATE TABLE `bt_g_geo_locations` (
  `location_id` int(11) NOT NULL AUTO_INCREMENT,
  `location_country` char(2) NOT NULL,
  `location_country_full` varchar(60) NOT NULL,
  `location_state` char(2) NOT NULL,
  `location_state_full` varchar(60) NOT NULL,
  `location_city` varchar(60) NOT NULL,
  `location_timezone` varchar(40) NOT NULL,
  `location_postalcode` varchar(10) NOT NULL,
  PRIMARY KEY (`location_id`),
  KEY `block_drilldown` (`location_country`,`location_state`,`location_city`),
  KEY `block_timezone` (`location_timezone`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT");
	
	upgradeSql($new,"CREATE TABLE `bt_g_organizations` (
  `org_id` int(11) NOT NULL AUTO_INCREMENT,
  `org_name` varchar(200) NOT NULL,
  PRIMARY KEY (`org_id`),
  KEY `name` (`org_name`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8");
	
	upgradeSql($new,"drop table bt_s_click_cloaks");
	
	upgradeSql($new,"ALTER TABLE `bt_s_clicks` DROP COLUMN `click_subid` , CHANGE COLUMN `aff_campaign_id` `aff_campaign_id` MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0'  ,
			   CHANGE COLUMN `landing_page_id` `landing_page_id` MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0'  , CHANGE COLUMN `click_payout` `click_payout` DECIMAL(6,2) NOT NULL DEFAULT '0.00'  ,
			   ADD COLUMN `click_cloaked` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0'  AFTER `click_lifetime` , ADD COLUMN `click_deleted` TINYINT(3) UNSIGNED NOT NULL DEFAULT '0'  AFTER `click_cloaked` 
, DROP INDEX `click_subid`");
	
	upgradeSql($new,"ALTER TABLE `bt_s_clicks_advanced` DROP COLUMN `block_id` , CHANGE COLUMN `org_id` `org_id` INT(10) UNSIGNED NOT NULL DEFAULT '0'  ,
			   ADD COLUMN `tracker_id` INT(10) UNSIGNED NOT NULL DEFAULT '0'  AFTER `click_id` , ADD COLUMN `location_id` INT(10) UNSIGNED NOT NULL  AFTER `v4_id`");
	
	upgradeSql($new,"CREATE TABLE `bt_s_sessionvars` (
  `click_subid` char(10) NOT NULL,
  `vars` text NOT NULL,
  PRIMARY KEY (`click_subid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8");
	
	upgradeSql($new,"CREATE TABLE `bt_s_tracker_lpoffers` (
  `lpoffer_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tracker_id` int(10) unsigned NOT NULL,
  `aff_campaign_id` int(10) unsigned NOT NULL,
  `deleted` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`lpoffer_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8");
	
	upgradeSql($new,"CREATE TABLE `bt_s_tracker_options` (
  `tracker_id` int(10) unsigned NOT NULL,
  `option_name` varchar(45) NOT NULL,
  `option_value` text NOT NULL,
  PRIMARY KEY (`tracker_id`,`option_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8");
	
	upgradeSql($new,"ALTER TABLE `bt_s_trackers` DROP COLUMN `click_cloaker` , DROP COLUMN `tracker_time` , DROP COLUMN `tracker_id_public` , CHANGE COLUMN `tracker_cloaked_visits` `cloaker_id` INT(10) UNSIGNED NOT NULL DEFAULT '0'  , ADD COLUMN `tracker_deleted` TINYINT(3) UNSIGNED NOT NULL DEFAULT '0'  AFTER `cloaker_id` , ADD COLUMN `tracker_name` VARCHAR(50) NOT NULL DEFAULT ''  AFTER `tracker_deleted` 
, DROP INDEX `tracker_id_public`");
	
	upgradeSql($new,"ALTER TABLE `bt_u_ad_accounts` CHANGE COLUMN `ad_account_deleted` `ad_account_deleted` TINYINT(3) UNSIGNED NOT NULL DEFAULT '0'");
	
	upgradeSql($new,"ALTER TABLE `bt_u_ad_networks` CHANGE COLUMN `ad_network_deleted` `ad_network_deleted` TINYINT(3) UNSIGNED NOT NULL DEFAULT '0'");
	
	upgradeSql($new,"ALTER TABLE `bt_u_aff_campaigns` CHANGE COLUMN `aff_campaign_deleted` `aff_campaign_deleted` TINYINT(3) UNSIGNED NOT NULL DEFAULT '0'");
	
	upgradeSql($new,"ALTER TABLE `bt_u_aff_networks` CHANGE COLUMN `aff_network_deleted` `aff_network_deleted` TINYINT(3) UNSIGNED NOT NULL DEFAULT '0'");
		
	upgradeSql($new,"CREATE TABLE `bt_u_cloaker_hostnames` (
  `cloaker_id` int(10) unsigned NOT NULL,
  `hostname` varchar(100) CHARACTER SET utf8 NOT NULL,
  `url` varchar(255) NOT NULL,
  `memo` text NOT NULL,
  `regex` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`hostname`,`cloaker_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8");
	
	upgradeSql($new,"CREATE TABLE `bt_u_cloaker_ips` (
  `cloaker_id` int(10) unsigned NOT NULL,
  `ip_from` int(10) unsigned NOT NULL,
  `ip_to` int(10) unsigned NOT NULL,
  `url` varchar(255) NOT NULL,
  `memo` text NOT NULL,
  PRIMARY KEY (`ip_from`,`ip_to`,`cloaker_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8");
	
	upgradeSql($new,"CREATE TABLE `bt_u_cloaker_referers` (
  `cloaker_id` int(10) unsigned NOT NULL,
  `referer` varchar(100) NOT NULL,
  `url` varchar(255) NOT NULL,
  `memo` text NOT NULL,
  `regex` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`cloaker_id`,`referer`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8");
	
	upgradeSql($new,"CREATE TABLE `bt_u_cloaker_user_agents` (
  `cloaker_id` int(10) unsigned NOT NULL,
  `user_agent` varchar(100) NOT NULL,
  `url` varchar(255) NOT NULL,
  `memo` text NOT NULL,
  `regex` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`cloaker_id`,`user_agent`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8");
	
	upgradeSql($new,"CREATE TABLE `bt_u_cloaker_options` (
  `cloaker_id` int(11) unsigned NOT NULL,
  `option_name` varchar(50) NOT NULL DEFAULT '',
  `option_value` text NOT NULL,
  PRIMARY KEY (`cloaker_id`,`option_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8");
	
	upgradeSql($new,"CREATE TABLE `bt_u_cloakers` (
  `cloaker_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `url` text NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(50) NOT NULL,
  `full_slug` varchar(100) NOT NULL,
  PRIMARY KEY (`cloaker_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8");

	upgradeSql($new,"ALTER TABLE `bt_u_landing_pages` CHANGE COLUMN `landing_page_deleted` `landing_page_deleted` TINYINT(3) UNSIGNED NOT NULL DEFAULT '0'");
	
	$cur_ver = upgradeVersion($new);
}

if($cur_ver == '13.04.14') {
	$new = '13.04.15';
	
	upgradeSql($new,"DROP TABLE IF EXISTS `bt_c_statcache`");

	upgradeSql($new,"CREATE TABLE `bt_c_statcache` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `time_from` varchar(11) NOT NULL DEFAULT '0',
  `time_to` varchar(11) NOT NULL DEFAULT '0',
  `type` varchar(15) NOT NULL,
  `clicks` int(11) NOT NULL DEFAULT '0',
  `click_throughs` int(10) unsigned NOT NULL DEFAULT '0',
  `click_through_rates` decimal(8,2) NOT NULL DEFAULT '0.00',
  `leads` int(11) NOT NULL DEFAULT '0',
  `conv` decimal(8,2) NOT NULL DEFAULT '0.00',
  `payout` decimal(8,2) NOT NULL DEFAULT '0.00',
  `epc` decimal(8,2) NOT NULL DEFAULT '0.00',
  `cpc` decimal(8,2) NOT NULL DEFAULT '0.00',
  `income` decimal(8,2) NOT NULL DEFAULT '0.00',
  `cost` decimal(8,2) NOT NULL DEFAULT '0.00',
  `net` decimal(8,2) NOT NULL DEFAULT '0.00',
  `roi` decimal(8,2) NOT NULL DEFAULT '0.00',
  `meta1` varchar(50) DEFAULT NULL,
  `meta2` varchar(50) DEFAULT NULL,
  `meta3` varchar(50) DEFAULT NULL,
  `meta4` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MEMORY AUTO_INCREMENT=1 DEFAULT CHARSET=utf8");
	
	upgradeSql($new,"ALTER TABLE `bt_s_clicks` 
ADD INDEX `click_time` (`click_time` ASC) 
, DROP INDEX `landing_page_id` 
, DROP INDEX `overview_index2` 
, DROP INDEX `user_id` 
, DROP INDEX `overview_index` 
, DROP INDEX `click_filtered` 
, DROP INDEX `ppc_account_id` 
, DROP INDEX `aff_campaign_id` ");
	
	upgradeSql($new,"ALTER TABLE `bt_s_clicks_advanced` CHANGE COLUMN `platform_id` `platform_id` TINYINT(3) UNSIGNED NOT NULL  , CHANGE COLUMN `browser_id` `browser_id` TINYINT(3) UNSIGNED NOT NULL  
, ADD INDEX `frequency` (`tracker_id` ASC, `ip_id` ASC)");
	
	upgradeSql($new,"ALTER TABLE `bt_s_clicks_site` ADD INDEX `click_referer_site` (`site_referer_domain` ASC)");
	
	upgradeSql($new,"ALTER TABLE `bt_u_ad_accounts` DROP FOREIGN KEY `FKAdAcctUserId` ;
ALTER TABLE `bt_u_ad_accounts` 
DROP INDEX `FKAdAcctUserId_idx` 
, DROP INDEX `FKAdAcctNetID_idx`");
	
	upgradeSql($new,"ALTER TABLE `bt_u_ad_networks` DROP FOREIGN KEY `FKAdNetUserID` ;
ALTER TABLE `bt_u_ad_networks` 
DROP INDEX `FKAdNetUserID_idx`");
	
	upgradeSql($new,"ALTER TABLE `bt_u_spending` CHANGE COLUMN `spending_deleted` `spending_deleted` TINYINT(3) UNSIGNED NOT NULL DEFAULT '0'");
	
	$cur_ver = upgradeVersion($new);
}

if($cur_ver == '13.04.15') {
	$new = '13.04.16';
	
	upgradeSql($new,"drop table bt_c_landing_pages");
	
	$cur_ver = upgradeVersion($new);
}

echo 'Ballistic Tracking is up to date';
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

CREATE TABLE IF NOT EXISTS `bt_c_statcache` (
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
) ENGINE=MEMORY  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

CREATE TABLE IF NOT EXISTS `bt_g_cloaker_orgs` (
  `org_id` int(10) unsigned NOT NULL,
  `org_name` varchar(100) NOT NULL,
  PRIMARY KEY (`org_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `bt_g_geo_locations` (
  `location_id` int(11) NOT NULL AUTO_INCREMENT,
  `country` char(2) NOT NULL,
  `country_full` varchar(60) NOT NULL,
  `state` char(2) NOT NULL,
  `state_full` varchar(60) NOT NULL,
  `city` varchar(60) NOT NULL,
  `timezone` varchar(40) NOT NULL,
  `postalcode` varchar(10) NOT NULL,
  PRIMARY KEY (`location_id`),
  KEY `block_drilldown` (`country`,`state`,`city`),
  KEY `block_timezone` (`timezone`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT AUTO_INCREMENT=28236 ;

CREATE TABLE IF NOT EXISTS `bt_g_organizations` (
  `org_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  PRIMARY KEY (`org_id`),
  KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=16032 ;

CREATE TABLE IF NOT EXISTS `bt_g_syslog` (
  `log_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(20) NOT NULL DEFAULT ' ',
  `date` datetime NOT NULL,
  `level` tinyint(3) unsigned NOT NULL,
  `message` text NOT NULL,
  `extra` longtext NOT NULL,
  PRIMARY KEY (`log_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `bt_g_version` (
  `version` varchar(10) NOT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `bt_s_authsessions` (
  `session_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `time` datetime NOT NULL,
  `expire` datetime NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `key` varchar(50) NOT NULL,
  `fingerprint` varchar(50) NOT NULL,
  `ip_id` int(10) unsigned NOT NULL,
  `success` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `meta` longtext NOT NULL,
  PRIMARY KEY (`session_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=870 ;

CREATE TABLE IF NOT EXISTS `bt_s_clicks` (
  `click_id` int(10) unsigned NOT NULL,
  `user_id` mediumint(8) unsigned NOT NULL,
  `offer_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `landing_page_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `traffic_source_id` mediumint(8) unsigned NOT NULL,
  `payout` decimal(6,2) NOT NULL DEFAULT '0.00',
  `lead_manual` tinyint(1) NOT NULL DEFAULT '0',
  `lead` tinyint(1) NOT NULL DEFAULT '0',
  `filtered` tinyint(4) NOT NULL DEFAULT '0',
  `time` int(10) unsigned NOT NULL,
  `lead_time` int(10) unsigned NOT NULL DEFAULT '0',
  `lifetime` int(10) unsigned NOT NULL DEFAULT '0',
  `cloaked` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `campaign_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`click_id`),
  KEY `click_lead` (`lead`),
  KEY `click_time` (`time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `bt_s_clicks_advanced` (
  `click_id` int(10) unsigned NOT NULL,
  `keyword_id` int(10) unsigned NOT NULL,
  `ip_id` int(10) unsigned NOT NULL,
  `platform_id` tinyint(3) unsigned NOT NULL,
  `browser_id` tinyint(3) unsigned NOT NULL,
  `org_id` int(10) unsigned NOT NULL DEFAULT '0',
  `device_id` int(10) unsigned NOT NULL DEFAULT '0',
  `v1_id` int(10) unsigned NOT NULL,
  `v2_id` int(10) unsigned NOT NULL,
  `v3_id` int(10) unsigned NOT NULL,
  `v4_id` int(10) unsigned NOT NULL,
  `location_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`click_id`),
  KEY `keyword_id` (`keyword_id`),
  KEY `ip_id` (`ip_id`),
  KEY `frequency` (`ip_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `bt_s_clicks_passthrough` (
  `click_id` int(10) unsigned NOT NULL,
  `name` varchar(40) NOT NULL,
  `value` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`click_id`,`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `bt_s_clicks_site` (
  `click_id` int(10) unsigned NOT NULL,
  `referer_url` text NOT NULL,
  `referer_domain` varchar(100) NOT NULL,
  `offer_url` text NOT NULL,
  `landing_url` text NOT NULL,
  PRIMARY KEY (`click_id`),
  KEY `click_referer_site` (`referer_domain`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `bt_s_counter` (
  `click_count` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`click_count`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `bt_s_device_data` (
  `device_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `hash` varchar(32) DEFAULT NULL,
  `brand` varchar(25) DEFAULT NULL,
  `type` varchar(25) DEFAULT NULL,
  `os` varchar(25) DEFAULT NULL,
  `os_version` varchar(10) DEFAULT NULL,
  `browser` varchar(25) DEFAULT NULL,
  `browser_version` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`device_id`),
  UNIQUE KEY `hash` (`hash`),
  KEY `brand` (`brand`),
  KEY `type` (`type`),
  KEY `os` (`os`),
  KEY `os_ver` (`os_version`),
  KEY `browser` (`browser`),
  KEY `browser_ver` (`browser_version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `bt_s_ips` (
  `ip_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(15) NOT NULL,
  PRIMARY KEY (`ip_id`),
  KEY `ip_address` (`ip_address`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=635490 ;

CREATE TABLE IF NOT EXISTS `bt_s_keywords` (
  `keyword_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `keyword` varchar(50) NOT NULL,
  PRIMARY KEY (`keyword_id`),
  KEY `keyword` (`keyword`(10))
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

CREATE TABLE IF NOT EXISTS `bt_s_variables` (
  `var_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `var_value` varchar(100) NOT NULL,
  PRIMARY KEY (`var_id`),
  UNIQUE KEY `var_id` (`var_id`),
  KEY `var_value_2` (`var_value`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

CREATE TABLE IF NOT EXISTS `bt_u_aff_networks` (
  `aff_network_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` mediumint(8) unsigned NOT NULL,
  `name` varchar(50) NOT NULL,
  `deleted` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`aff_network_id`),
  KEY `user_id` (`user_id`),
  KEY `aff_network_deleted` (`deleted`),
  KEY `aff_network_name` (`name`(5))
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

CREATE TABLE IF NOT EXISTS `bt_u_campaigns` (
  `campaign_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` mediumint(8) unsigned NOT NULL,
  `traffic_source_id` mediumint(8) unsigned NOT NULL,
  `rotate` tinyint(4) NOT NULL DEFAULT '0',
  `cloaker_id` int(10) unsigned NOT NULL DEFAULT '0',
  `slug` varchar(50) NOT NULL DEFAULT '',
  `deleted` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `name` varchar(50) NOT NULL DEFAULT '',
  `type` tinyint(3) unsigned NOT NULL,
  `allow_duplicate_conversion` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT 'Allow duplicate conversions',
  PRIMARY KEY (`campaign_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

CREATE TABLE IF NOT EXISTS `bt_u_campaign_lps` (
  `campaign_lp_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `campaign_id` int(10) unsigned NOT NULL,
  `landing_page_id` int(10) unsigned NOT NULL,
  `weight` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`campaign_lp_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

CREATE TABLE IF NOT EXISTS `bt_u_campaign_offers` (
  `campaign_offer_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `campaign_id` int(10) unsigned NOT NULL,
  `position` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `offer_id` int(10) unsigned NOT NULL,
  `weight` tinyint(3) unsigned NOT NULL,
  `deleted` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`campaign_offer_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

CREATE TABLE IF NOT EXISTS `bt_u_campaign_options` (
  `campaign_id` int(10) unsigned NOT NULL,
  `name` varchar(45) NOT NULL,
  `value` text NOT NULL,
  `note` varchar(200) NOT NULL DEFAULT '',
  PRIMARY KEY (`campaign_id`,`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `bt_u_cloakers` (
  `cloaker_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `url` text NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(50) NOT NULL,
  PRIMARY KEY (`cloaker_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `bt_u_cloaker_hostnames` (
  `cloaker_id` int(10) unsigned NOT NULL,
  `hostname` varchar(100) NOT NULL,
  `url` varchar(255) NOT NULL,
  `memo` text NOT NULL,
  `regex` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`hostname`,`cloaker_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `bt_u_cloaker_ips` (
  `cloaker_id` int(10) unsigned NOT NULL,
  `ip_from` int(10) unsigned NOT NULL,
  `ip_to` int(10) unsigned NOT NULL,
  `url` varchar(255) NOT NULL,
  `memo` text NOT NULL,
  PRIMARY KEY (`ip_from`,`ip_to`,`cloaker_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `bt_u_cloaker_options` (
  `cloaker_id` int(11) unsigned NOT NULL,
  `name` varchar(50) NOT NULL DEFAULT '',
  `value` text NOT NULL,
  PRIMARY KEY (`cloaker_id`,`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `bt_u_cloaker_referers` (
  `cloaker_id` int(10) unsigned NOT NULL,
  `referer` varchar(100) NOT NULL,
  `url` varchar(255) NOT NULL,
  `memo` text NOT NULL,
  `regex` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`cloaker_id`,`referer`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `bt_u_cloaker_user_agents` (
  `cloaker_id` int(10) unsigned NOT NULL,
  `user_agent` varchar(100) NOT NULL,
  `url` varchar(255) NOT NULL,
  `memo` text NOT NULL,
  `regex` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`cloaker_id`,`user_agent`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `bt_u_income` (
  `income_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `campaign_id` int(10) unsigned NOT NULL,
  `amount` decimal(8,2) NOT NULL,
  `deleted` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`income_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `bt_u_landing_pages` (
  `landing_page_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` mediumint(8) unsigned NOT NULL,
  `name` varchar(50) NOT NULL,
  `url` varchar(255) NOT NULL,
  `deleted` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`landing_page_id`),
  KEY `landing_page_deleted` (`deleted`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

CREATE TABLE IF NOT EXISTS `bt_u_offers` (
  `offer_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `aff_network_id` mediumint(8) unsigned NOT NULL,
  `deleted` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `name` varchar(50) NOT NULL,
  `url` text NOT NULL,
  `payout` decimal(5,2) NOT NULL,
  PRIMARY KEY (`offer_id`),
  KEY `aff_network_id` (`aff_network_id`),
  KEY `aff_campaign_deleted` (`deleted`),
  KEY `aff_campaign_name` (`name`(5))
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

CREATE TABLE IF NOT EXISTS `bt_u_settings` (
  `settings_id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `user_id` mediumint(9) NOT NULL,
  `pass_key` varchar(250) NOT NULL,
  `api_key` varchar(250) NOT NULL,
  `domain` varchar(250) NOT NULL,
  `buy_date` date NOT NULL,
  `type` varchar(100) NOT NULL,
  `recurrence` mediumint(9) NOT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  `deleted` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`settings_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

CREATE TABLE IF NOT EXISTS `bt_u_spending` (
  `spending_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `campaign_id` int(10) unsigned NOT NULL,
  `amount` decimal(8,2) NOT NULL,
  `deleted` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`spending_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `bt_u_traffic_sources` (
  `traffic_source_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` mediumint(8) unsigned NOT NULL,
  `name` varchar(70) NOT NULL,
  `deleted` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`traffic_source_id`),
  KEY `ppc_account_deleted` (`deleted`),
  KEY `user_id` (`user_id`),
  KEY `ppc_account_name` (`name`(5))
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

CREATE TABLE IF NOT EXISTS `bt_u_users` (
  `user_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `user_name` varchar(50) NOT NULL,
  `pass` char(128) NOT NULL,
  `pass_salt` char(30) NOT NULL,
  `email` varchar(100) NOT NULL,
  `privilege` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `timezone` varchar(40) NOT NULL DEFAULT 'America/New_York',
  `pass_key` varchar(255) NOT NULL,
  `pass_time` int(10) unsigned NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`),
  KEY `user_email` (`email`) USING BTREE,
  KEY `user_name` (`user_name`) USING BTREE
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

CREATE TABLE IF NOT EXISTS `bt_u_users_pref` (
  `user_id` mediumint(8) unsigned NOT NULL,
  `name` varchar(45) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`user_id`,`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `wurfl_cache` (
  `key` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `value` mediumblob NOT NULL,
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`key`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci ROW_FORMAT=DYNAMIC;

CREATE TABLE IF NOT EXISTS `wurfl_persistence` (
  `key` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `value` mediumblob NOT NULL,
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`key`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci ROW_FORMAT=DYNAMIC;

INSERT INTO `bt_g_cloaker_orgs` VALUES(1, 'Facebook');
INSERT INTO `bt_g_cloaker_orgs` VALUES(2, 'Google');
INSERT INTO `bt_g_cloaker_orgs` VALUES(3, 'Bing/Yahoo');
INSERT INTO `bt_g_cloaker_orgs` VALUES(4, 'Pulse360');
INSERT INTO `bt_g_cloaker_orgs` VALUES(6, 'AOL/Advertising.com');
INSERT INTO `bt_g_cloaker_orgs` VALUES(7, 'Catch All');
INSERT INTO `bt_g_cloaker_orgs` VALUES(10, 'Clickbooth CPC');
INSERT INTO `bt_g_cloaker_orgs` VALUES(11, 'WAM CPC');
INSERT INTO `bt_g_cloaker_orgs` VALUES(12, 'Site Scout - AU');
INSERT INTO `bt_g_cloaker_orgs` VALUES(13, 'TrafficVance');

INSERT INTO `bt_g_version` VALUES('13.05.08');

INSERT INTO `bt_s_counter` VALUES(0);

INSERT INTO `bt_u_aff_networks` VALUES(1, 1, 'Tes Network', 0);
INSERT INTO `bt_u_aff_networks` VALUES(2, 1, 'Test Affiliate Net', 0);

INSERT INTO `bt_u_campaigns` VALUES(1, 1, 1, 1, 0, '', 0, 'Your Campaign', 2, 0);
INSERT INTO `bt_u_campaigns` VALUES(2, 1, 2, 0, 0, '', 0, 'Your LP Campaign', 1, 0);

INSERT INTO `bt_u_campaign_lps` VALUES(1, 2, 1, 1);

INSERT INTO `bt_u_campaign_offers` VALUES(1, 1, 0, 1, 1, 0);
INSERT INTO `bt_u_campaign_offers` VALUES(2, 2, 0, 2, 0, 0);

INSERT INTO `bt_u_campaign_options` VALUES(1, 'advanced_redirect_status', '1', '');
INSERT INTO `bt_u_campaign_options` VALUES(1, 'default_var_kw', '', '');
INSERT INTO `bt_u_campaign_options` VALUES(1, 'default_var_v1', '', '');
INSERT INTO `bt_u_campaign_options` VALUES(1, 'default_var_v2', '', '');
INSERT INTO `bt_u_campaign_options` VALUES(1, 'default_var_v3', '', '');
INSERT INTO `bt_u_campaign_options` VALUES(1, 'default_var_v4', '', '');
INSERT INTO `bt_u_campaign_options` VALUES(1, 'pass_vp test', '{"offer":"1"}', 'your note');
INSERT INTO `bt_u_campaign_options` VALUES(1, 'pixel_code', '', '');
INSERT INTO `bt_u_campaign_options` VALUES(1, 'pixel_type', '0', '');
INSERT INTO `bt_u_campaign_options` VALUES(1, 'redirect_method', '31', '');
INSERT INTO `bt_u_campaign_options` VALUES(1, 'var_kw', 'wq', '');
INSERT INTO `bt_u_campaign_options` VALUES(1, 'var_v1', 'uc0tz9r', '');
INSERT INTO `bt_u_campaign_options` VALUES(1, 'var_v2', '3kace', '');
INSERT INTO `bt_u_campaign_options` VALUES(1, 'var_v3', '820gih5', '');
INSERT INTO `bt_u_campaign_options` VALUES(1, 'var_v4', 'zx', '');
INSERT INTO `bt_u_campaign_options` VALUES(2, 'advanced_redirect_status', '1', '');
INSERT INTO `bt_u_campaign_options` VALUES(2, 'default_var_kw', '', '');
INSERT INTO `bt_u_campaign_options` VALUES(2, 'default_var_v1', '', '');
INSERT INTO `bt_u_campaign_options` VALUES(2, 'default_var_v2', '', '');
INSERT INTO `bt_u_campaign_options` VALUES(2, 'default_var_v3', '', '');
INSERT INTO `bt_u_campaign_options` VALUES(2, 'default_var_v4', '', '');
INSERT INTO `bt_u_campaign_options` VALUES(2, 'pass_vp1', '{"lp":"1","offer":"0"}', 'your note');
INSERT INTO `bt_u_campaign_options` VALUES(2, 'pixel_code', '', '');
INSERT INTO `bt_u_campaign_options` VALUES(2, 'pixel_type', '0', '');
INSERT INTO `bt_u_campaign_options` VALUES(2, 'redirect_method', '37', '');
INSERT INTO `bt_u_campaign_options` VALUES(2, 'var_kw', '91b8bf', '');
INSERT INTO `bt_u_campaign_options` VALUES(2, 'var_v1', '93siu3', '');
INSERT INTO `bt_u_campaign_options` VALUES(2, 'var_v2', 'pulyeyc', '');
INSERT INTO `bt_u_campaign_options` VALUES(2, 'var_v3', 'z31', '');
INSERT INTO `bt_u_campaign_options` VALUES(2, 'var_v4', 'cvbli', '');

INSERT INTO `bt_u_landing_pages` VALUES(1, 1, 'lp test', 'http://www.lptest.com/[[clickid]]', 0);

INSERT INTO `bt_u_offers` VALUES(1, 1, 0, 'Test Offer', 'http://www.testoffer.com/[[subid1]]', 123.00);
INSERT INTO `bt_u_offers` VALUES(2, 2, 0, 'Mock Offer', 'http://mock_offer.com/[[subid1]][[subid2]]', 456.00);

INSERT INTO `bt_u_traffic_sources` VALUES(1, 1, 'SEO', 0);
INSERT INTO `bt_u_traffic_sources` VALUES(2, 1, 'Facebook', 0);

INSERT INTO `bt_u_users` VALUES(1, 'Admin', 'fcc89ed9ebb15dd3a5bfc3f1b55f7379eb1d3ea09a90c912e59f52a14c78153d21ceeaf97cc9d286a08eb015952a66aeb08138495b7322ec6e7beeea03b8bd57', 'tJ%wsOr*sjUEIsnL!KM#NT*j9aTxgU', 'admin@admin.com', 10, 'America/Los_Angeles', '', 0, 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
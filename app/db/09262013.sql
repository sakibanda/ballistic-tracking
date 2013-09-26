--
-- Table structure for table `bt_u_income`
--

CREATE TABLE IF NOT EXISTS `bt_u_income` (
  `income_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `campaign_id` int(10) unsigned NOT NULL,
  `amount` decimal(8,2) NOT NULL,
  `deleted` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`income_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;
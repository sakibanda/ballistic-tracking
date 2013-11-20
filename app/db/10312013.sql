--
-- Table structure for table `bt_u_settings`
--

CREATE TABLE IF NOT EXISTS `bt_u_settings` (
  `settings_id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `user_id` mediumint(9) NOT NULL,
  `pass_key` varchar(250) CHARACTER SET utf8 NOT NULL,
  `api_key` varchar(250) CHARACTER SET utf8 NOT NULL,
  `domain` varchar(250) CHARACTER SET utf8 NOT NULL,
  `buy_date` date NOT NULL,
  `type` varchar(100) CHARACTER SET utf8 NOT NULL,
  `recurrence` mediumint(9) NOT NULL,
  `deleted` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`settings_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7;
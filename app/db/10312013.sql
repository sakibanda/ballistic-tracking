--
-- Table structure for table `bt_u_settings`
--

CREATE TABLE IF NOT EXISTS `bt_u_settings` (
  `settings_id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `user_id` mediumint(9) NOT NULL,
  `keyId` varchar(250) CHARACTER SET utf8 NOT NULL,
  `domain` varchar(250) CHARACTER SET utf8 NOT NULL,
  `deleted` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`settings_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;
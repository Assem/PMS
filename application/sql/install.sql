--
-- Community Auth - MySQL table install
--
-- Community Auth is an open source authentication application for CodeIgniter 3
--
-- @package     Community Auth
-- @author      Robert B Gottier
-- @copyright   Copyright (c) 2011 - 2015, Robert B Gottier. (http://brianswebdesign.com/)
-- @license     BSD - http://www.opensource.org/licenses/BSD-3-Clause
-- @link        http://community-auth.com
--

--
-- Table structure for table `ci_session`
--

CREATE TABLE IF NOT EXISTS `ci_sessions` (
  `ai` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id` varchar(40) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) unsigned NOT NULL DEFAULT '0',
  `data` blob NOT NULL,
  PRIMARY KEY (`ai`),
  UNIQUE KEY `ci_sessions_id_ip` (`id`,`ip_address`),
  KEY `ci_sessions_timestamp` (`timestamp`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ips_on_hold`
--

CREATE TABLE IF NOT EXISTS `ips_on_hold` (
  `ai` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `IP_address` varchar(45) NOT NULL,
  `time` datetime NOT NULL,
  PRIMARY KEY (`ai`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `login_errors`
--

CREATE TABLE IF NOT EXISTS `login_errors` (
  `ai` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username_or_email` varchar(255) NOT NULL,
  `IP_address` varchar(45) NOT NULL,
  `time` datetime NOT NULL,
  PRIMARY KEY (`ai`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `denied_access`
--

CREATE TABLE IF NOT EXISTS `denied_access` (
  `ai` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `IP_address` varchar(45) NOT NULL,
  `time` datetime NOT NULL,
  `reason_code` tinyint(2) DEFAULT 0,
  PRIMARY KEY (`ai`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `username_or_email_on_hold`
--

CREATE TABLE IF NOT EXISTS `username_or_email_on_hold` (
  `ai` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username_or_email` varchar(255) NOT NULL,
  `time` datetime NOT NULL,
  PRIMARY KEY (`ai`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(10) unsigned NOT NULL,
  `user_name` varchar(12) DEFAULT NULL,
  `user_email` varchar(255) NOT NULL,
  `user_pass` varchar(60) NOT NULL,
  `user_salt` varchar(32) NOT NULL,
  `user_last_login` datetime DEFAULT NULL,
  `user_login_time` datetime DEFAULT NULL,
  `user_session_id` varchar(40) DEFAULT NULL,
  `user_date` datetime NOT NULL,
  `user_modified` datetime NOT NULL,
  `user_agent_string` varchar(32) DEFAULT NULL,
  `user_level` tinyint(2) unsigned NOT NULL,
  `user_banned` enum('0','1') NOT NULL DEFAULT '0',
  `passwd_recovery_code` varchar(60) DEFAULT NULL,
  `passwd_recovery_date` datetime DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `user_name` (`user_name`),
  UNIQUE KEY `user_email` (`user_email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------


----  PMS custom queries
ALTER TABLE `PMS`.`users` 
ADD COLUMN `pms_user_first_name` VARCHAR(80) NOT NULL AFTER `passwd_recovery_date`,
ADD COLUMN `pms_user_last_name` VARCHAR(80) NOT NULL AFTER `pms_user_first_name`;

ALTER TABLE `PMS`.`users` 
ADD COLUMN `pms_user_gsm` INT(30) NOT NULL AFTER `passwd_recovery_date`,
ADD COLUMN `pms_user_code` VARCHAR(20) NOT NULL AFTER `pms_user_first_name`;

-- --------------------------------------------------------

--
-- Table structure for table `pools`
--

CREATE TABLE `PMS`.`pools` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `label` VARCHAR(80) NOT NULL,
  `description` VARCHAR(255) NULL,
  `code` VARCHAR(20) NOT NULL,
  `start_date` DATE NULL,
  `end_date` DATE NULL,
  `actif` TINYINT NOT NULL DEFAULT 1,
  `max_surveys_number` INT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `code_UNIQUE` (`code` ASC)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE `PMS`.`pools` 
ADD COLUMN `customer` VARCHAR(120) NULL AFTER `max_surveys_number`;

ALTER TABLE `PMS`.`pools` 
ADD COLUMN `creation_date` DATETIME NOT NULL AFTER `customer`,
ADD COLUMN `update_date` DATETIME NOT NULL AFTER `creation_date`,
ADD COLUMN `created_by` INT(10) UNSIGNED NOT NULL AFTER `update_date`,
ADD INDEX `fk_pools_1_idx` (`created_by` ASC);
ALTER TABLE `PMS`.`pools` 
ADD CONSTRAINT `fk_pools_1`
  FOREIGN KEY (`created_by`)
  REFERENCES `PMS`.`users` (`user_id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;

-- --------------------------------------------------------
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

CREATE TABLE `active` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(128) collate utf8_unicode_ci NOT NULL,
  `severity` int(11) NOT NULL,
  `first_time` bigint(20) NOT NULL,
  `message` varchar(512) collate utf8_unicode_ci NOT NULL,
  `contact_group` varchar(64) collate utf8_unicode_ci NOT NULL,
  `ci` varchar(128) collate utf8_unicode_ci NOT NULL,
  `source` varchar(32) collate utf8_unicode_ci NOT NULL,
  `status` int(11) NOT NULL,
  `notes` text collate utf8_unicode_ci NOT NULL,
  `notes_ok` text collate utf8_unicode_ci NOT NULL,
  `count` int(11) NOT NULL,
  `last_time` bigint(20) NOT NULL,
  `last_problem_time` bigint(20) NOT NULL,
  `ack_time` bigint(20) NOT NULL,
  `ack_note` varchar(128) collate utf8_unicode_ci NOT NULL,
  `source_ip` varchar(15) collate utf8_unicode_ci NOT NULL,
  `archived` tinyint(1) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


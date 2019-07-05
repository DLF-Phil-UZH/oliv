-- phpMyAdmin SQL Dump
-- version 3.2.2
-- http://www.phpmyadmin.net
--
-- Erstellungszeit: 12. Februar 2014 um 15:30
-- Server Version: 5.0.96
-- PHP-Version: 5.2.14

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `oliv_documentLists`
--

CREATE TABLE IF NOT EXISTS `oliv_documentLists` (
  `id` int(9) NOT NULL auto_increment,
  `hashedId` varchar(32) NOT NULL,
  `title` varchar(255) NOT NULL,
  `tag` varchar(255) NOT NULL,
  `creator` int(9) NOT NULL,
  `lastUpdated` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `created` timestamp NOT NULL default '0000-00-00 00:00:00',
  `published` tinyint(1) unsigned NOT NULL default '0' COMMENT 'Binary value ',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=43 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `oliv_documentLists_admins`
--

CREATE TABLE IF NOT EXISTS `oliv_documentLists_admins` (
  `documentListId` int(9) NOT NULL,
  `userId` int(9) NOT NULL,
  `lastUpdated` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`documentListId`,`userId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `oliv_documents`
--

CREATE TABLE IF NOT EXISTS `oliv_documents` (
  `id` int(9) NOT NULL auto_increment,
  `hashedId` varchar(32) NOT NULL,
  `explicitId` varchar(100) NOT NULL,
  `fileName` varchar(100) NOT NULL,
  `title` varchar(255) NOT NULL,
  `authors` varchar(255) NOT NULL,
  `publication` varchar(200) NOT NULL,
  `volume` varchar(50) NOT NULL,
  `editors` varchar(255) NOT NULL,
  `publishingHouse` varchar(100) NOT NULL,
  `places` varchar(100) NOT NULL,
  `year` varchar(100) NOT NULL,
  `pages` varchar(100) NOT NULL,
  `webURL` varchar(255) NOT NULL,
  `webAccess` varchar(100) NOT NULL,
  `creator` int(9) NOT NULL,
  `lastUpdated` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `created` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `explicitId` (`explicitId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=168 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `oliv_documents_admins`
--

CREATE TABLE IF NOT EXISTS `oliv_documents_admins` (
  `documentId` int(9) NOT NULL,
  `userId` int(9) NOT NULL,
  `lastUpdated` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`documentId`,`userId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `oliv_documents_documentLists`
--

CREATE TABLE IF NOT EXISTS `oliv_documents_documentLists` (
  `documentId` int(9) NOT NULL,
  `documentListId` int(9) NOT NULL,
  `lastUpdated` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`documentId`,`documentListId`),
  KEY `documentListId` (`documentListId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `oliv_groceryCrudLocks`
--

CREATE TABLE IF NOT EXISTS `oliv_groceryCrudLocks` (
  `tablename` varchar(100) character set utf8 NOT NULL,
  `recordId` int(9) NOT NULL,
  `userId` int(9) NOT NULL,
  `timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`tablename`,`recordId`,`userId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `oliv_migrations`
--

CREATE TABLE IF NOT EXISTS `oliv_migrations` (
  `version` int(3) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `oliv_users`
--

CREATE TABLE IF NOT EXISTS `oliv_users` (
  `id` int(9) NOT NULL auto_increment,
  `aaiId` varchar(100) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `role` enum('admin','user') NOT NULL default 'user',
  `lastLogin` timestamp NOT NULL default '0000-00-00 00:00:00',
  `created` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `username` (`aaiId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=35 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `oliv_user_requests`
--

CREATE TABLE IF NOT EXISTS `oliv_user_requests` (
  `id` int(9) NOT NULL auto_increment,
  `aaiId` varchar(100) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `created` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Constraints
--

--
-- Constraints der Tabelle `oliv_documents_documentLists`
--
ALTER TABLE `oliv_documents_documentLists`
  ADD CONSTRAINT `documents_documentLists_ibfk_1` FOREIGN KEY (`documentId`) REFERENCES `oliv_documents` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `documents_documentLists_ibfk_2` FOREIGN KEY (`documentListId`) REFERENCES `oliv_documentLists` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

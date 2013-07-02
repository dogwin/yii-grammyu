-- phpMyAdmin SQL Dump
-- version 3.5.7
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 14, 2013 at 08:37 PM
-- Server version: 5.5.29
-- PHP Version: 5.4.10

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `wil`
--
CREATE DATABASE `wil` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `wil`;

-- --------------------------------------------------------

--
-- Table structure for table `curators`
--

DROP TABLE IF EXISTS `curators`;
CREATE TABLE `curators` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL,
  `description` text,
  `slug` varchar(100) DEFAULT NULL,
  `twitter` varchar(100) NOT NULL,
  `image` varchar(200) NOT NULL,
  `titleimage` varchar(200) DEFAULT NULL,
  `reach` int(11) NOT NULL,
  `artists` varchar(100) DEFAULT NULL,
  `displayorder` int(11) NOT NULL DEFAULT '10',
  `dateadded` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `isactive` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `curators`
--

INSERT INTO `curators` (`id`, `title`, `description`, `slug`, `twitter`, `image`, `titleimage`, `reach`, `artists`, `displayorder`, `dateadded`, `isactive`) VALUES
(1, 'Ozzy', 'Throughout his more than four-decade career, GRAMMY winner Ozzy Osbourne is an innovator and an icon. A founding member of pioneering metal band Black Sabbath, Ozzy is also a multiplatinum solo artist and an inductee in the Rock & Roll Hall of Fame.', 'ozzy', '', 'curators_ozzy.png', NULL, 9727756, '2815', 1, '2012-12-03 02:37:21', 1),
(2, 'RZA', 'Multi-instrumentalist. Founder of the legendary Wu-Tang Clan. Solo artist. Producer. Actor. Filmmaker. GRAMMY winner. RZA is one of the most influential figures in the history of hip-hop, with millions of records sold worldwide.', 'rza', '', 'curators_rza.png', NULL, 618166, '1555', 2, '2012-12-03 02:38:38', 1),
(3, 'Linkin Park', 'Two-time GRAMMY winners Linkin Park have sold over 50 million abums worldwide. Known for pushing musical boundaries through cross-genre experimentation and collaborations, the band is also highly dedicated to giving back through extensive charity work.', 'linkin-park', '', 'curators_linkinpark.png', NULL, 49733978, '1510,1555,5587,3553,3708', 3, '2012-12-03 02:38:55', 1),
(6, 'Kelly Clarkson', 'Since winning the first season of "American Idol," two-time GRAMMY winner Kelly Clarkson has gone on to be one one of the best-selling artists of the past decade. Her massive worldwide hit "Stronger" is nominated in three categories at this year''s GRAMMY Awards.', 'kelly-clarkson', '', 'curators_kellyclarkson.png', NULL, 9438834, NULL, 5, '2013-01-12 08:40:32', 1),
(7, 'Jim James', 'Founder and frontman of the GRAMMY nominated band My Morning Jacket, Jim James is a respected songwriter, producer, and guitarist. His latest project is his solo full-length debut, "Regions of Light and Sound of God."', 'jim-james', '', 'curators_jimjames.png', NULL, 31111, '4631,4692', 4, '2013-01-29 19:43:36', 1),
(8, 'Kaskade', 'Born Ryan Raddon, Kaskade is one of the top DJs and producers in the world. His most recent album, Fire & Ice, is nominated for Best Dance/Electronica Album at this year''s GRAMMY Awards.', 'kaskade', '', 'curators_kaskade.png', NULL, 1165943, NULL, 6, '2013-02-06 19:50:46', 1),
(9, 'Snoop Lion', 'Snoop Lion aka Snoop Dogg aka Calvin Broadus is a legendary rapper, producer, multiplatinum-selling artist, and actor. He is a twelve-time GRAMMY nominee, including a nomination for Best Rap Song at this year''s Awards show.', 'snoop-lion', '', 'curators_snoop.png', NULL, 33514617, '4609', 7, '2013-02-06 22:40:16', 1);

-- --------------------------------------------------------

--
-- Table structure for table `genres`
--

DROP TABLE IF EXISTS `genres`;
CREATE TABLE `genres` (
  `title` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `genres`
--

INSERT INTO `genres` (`title`) VALUES
('Pop'),
('Dance'),
('Electronica'),
('Rock'),
('Alternative'),
('R&B'),
('Rap'),
('Country'),
('Jazz'),
('New Age'),
('Gospel/Contemporary Christian'),
('Latin'),
('Americana'),
('Bluegrass'),
('Folk'),
('Blues'),
('Reggae'),
('World Music'),
('Classical');

-- --------------------------------------------------------

--
-- Table structure for table `points`
--

DROP TABLE IF EXISTS `points`;
CREATE TABLE `points` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `track_id` int(11) NOT NULL,
  `session_id` varchar(200) NOT NULL,
  `ipaddress` varchar(15) NOT NULL,
  `value` int(11) NOT NULL DEFAULT '0',
  `type` varchar(20) NOT NULL,
  `external_id` varchar(200) DEFAULT NULL,
  `dateadded` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `dateinserted` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `isactive` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `track_id` (`track_id`),
  KEY `value` (`value`),
  KEY `isactive` (`isactive`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(200) CHARACTER SET utf8 NOT NULL,
  `full_name` varchar(200) CHARACTER SET utf8 DEFAULT NULL,
  `facebook` varchar(200) DEFAULT NULL,
  `twitter` varchar(200) DEFAULT NULL,
  `bitly` varchar(200) DEFAULT NULL,
  `soundcloud_id` int(11) NOT NULL,
  `soundcloud_accesstoken` varchar(200) CHARACTER SET utf8 NOT NULL,
  `soundcloud_data` text CHARACTER SET utf8 NOT NULL,
  `description` text CHARACTER SET utf8,
  `slug` varchar(200) CHARACTER SET utf8 NOT NULL,
  `track_id` int(11) DEFAULT NULL,
  `track_title` varchar(200) CHARACTER SET utf8 DEFAULT NULL,
  `track_genre` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `track_tags` text CHARACTER SET utf8,
  `track_data` text CHARACTER SET utf8,
  `points` int(11) NOT NULL DEFAULT '0',
  `points_dateupdated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `trend` int(11) NOT NULL DEFAULT '0',
  `qualified` tinyint(4) NOT NULL DEFAULT '0',
  `dateupdated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `dateadded` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `isactive` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `username` (`username`),
  KEY `full_name` (`full_name`),
  KEY `track_title` (`track_title`),
  KEY `track_genre` (`track_genre`),
  KEY `trend` (`trend`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

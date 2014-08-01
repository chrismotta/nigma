-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 15, 2014 at 12:31 PM
-- Server version: 5.5.37
-- PHP Version: 5.3.10-1ubuntu3.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `kickads_appserver`
--
CREATE DATABASE `kickads_appserver` DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;
USE `kickads_appserver`;

-- --------------------------------------------------------

--
-- Table structure for table `AuthAssignment`
--

DROP TABLE IF EXISTS `AuthAssignment`;
CREATE TABLE IF NOT EXISTS `AuthAssignment` (
  `itemname` varchar(64) COLLATE utf8_spanish_ci NOT NULL,
  `userid` varchar(64) COLLATE utf8_spanish_ci NOT NULL,
  `bizrule` text COLLATE utf8_spanish_ci,
  `data` text COLLATE utf8_spanish_ci,
  PRIMARY KEY (`itemname`,`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Dumping data for table `AuthAssignment`
--

INSERT INTO `AuthAssignment` (`itemname`, `userid`, `bizrule`, `data`) VALUES
('admin', '1', NULL, 'N;'),
('admin', '5', NULL, NULL),
('admin', '6', NULL, NULL),
('media', '2', NULL, 'N;');

-- --------------------------------------------------------

--
-- Table structure for table `AuthItem`
--

DROP TABLE IF EXISTS `AuthItem`;
CREATE TABLE IF NOT EXISTS `AuthItem` (
  `name` varchar(64) COLLATE utf8_spanish_ci NOT NULL,
  `type` int(11) NOT NULL,
  `description` text COLLATE utf8_spanish_ci,
  `bizrule` text COLLATE utf8_spanish_ci,
  `data` text COLLATE utf8_spanish_ci,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Dumping data for table `AuthItem`
--

INSERT INTO `AuthItem` (`name`, `type`, `description`, `bizrule`, `data`) VALUES
('admin', 2, '', NULL, 'N;'),
('finance', 2, '', NULL, 'N;'),
('media', 2, '', NULL, 'N;');

-- --------------------------------------------------------

--
-- Table structure for table `AuthItemChild`
--

DROP TABLE IF EXISTS `AuthItemChild`;
CREATE TABLE IF NOT EXISTS `AuthItemChild` (
  `parent` varchar(64) COLLATE utf8_spanish_ci NOT NULL,
  `child` varchar(64) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  KEY `child` (`child`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `advertisers`
--

DROP TABLE IF EXISTS `advertisers`;
CREATE TABLE IF NOT EXISTS `advertisers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rec` tinyint(1) NOT NULL DEFAULT '0',
  `name` varchar(128) COLLATE utf8_spanish_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0:active,1:inactive',
  `finance_entities_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_finance_entities` (`finance_entities_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `api_cron_log`
--

DROP TABLE IF EXISTS `api_cron_log`;
CREATE TABLE IF NOT EXISTS `api_cron_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `networks_id` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=5 ;

--
-- Dumping data for table `api_cron_log`
--

INSERT INTO `api_cron_log` (`id`, `networks_id`, `date`) VALUES
(1, 1, '2014-06-03 21:16:40'),
(2, 1, '2014-06-04 18:59:10'),
(3, 1, '2014-06-05 20:57:04'),
(4, 1, '2014-06-06 16:03:43');

-- --------------------------------------------------------

--
-- Table structure for table `campaign_categories`
--

DROP TABLE IF EXISTS `campaign_categories`;
CREATE TABLE IF NOT EXISTS `campaign_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rec` tinyint(1) NOT NULL DEFAULT '0',
  `name` varchar(128) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=175 ;

--
-- Dumping data for table `campaign_categories`
--

INSERT INTO `campaign_categories` (`id`, `rec`, `name`) VALUES
(1, 0, 'Adult'),
(2, 0, 'Alcohol, Liquor, and Spirits'),
(3, 0, 'Autos'),
(4, 0, 'Blogging Software'),
(5, 0, 'Business : B2B'),
(6, 0, 'Business : Credit Card'),
(7, 0, 'Dating: Dating Adult/Alternative'),
(8, 0, 'Dating: Easy-Dating'),
(9, 0, 'Dating: Match Making'),
(10, 0, 'Dating: Matrimony'),
(11, 0, 'Download: Free - Download - Adware'),
(12, 0, 'Download: Free Trial - Demo'),
(13, 0, 'Download: Paid Software'),
(14, 0, 'Drugs: Drugs - Recreational'),
(15, 0, 'Drugs: Prescription/OTC'),
(16, 0, 'eCom: Auction'),
(17, 0, 'eCom: Auction - HQ'),
(18, 0, 'eCom: Other'),
(19, 0, 'eCom: Photo / Albums'),
(20, 0, 'Education: Education - adult education'),
(21, 0, 'Education: Learning'),
(22, 0, 'Education: Pre-college prep and tutoring services'),
(23, 0, 'Education: Translator'),
(24, 0, 'Employment: Job recruitment'),
(25, 0, 'Employment: Work from Home'),
(26, 0, 'Entertainment : Movies'),
(27, 0, 'Entertainment: Food/Restaurants'),
(28, 0, 'Entertainment: Music'),
(29, 0, 'Entertainment: Other'),
(30, 0, 'Finance: Banking'),
(31, 0, 'Finance: Binary Trading'),
(32, 0, 'Finance: Credit'),
(33, 0, 'Finance: Forex'),
(34, 0, 'Finance: Insurance'),
(35, 0, 'Finance: Loans'),
(36, 0, 'Finance: Personal investments'),
(37, 0, 'Gambling: Bingo'),
(38, 0, 'Gambling: Casino'),
(39, 0, 'Gambling: Poker'),
(40, 0, 'Gambling: Skill Gambling'),
(41, 0, 'Gambling: Sports Betting'),
(42, 0, 'Gaming: Adware Gaming'),
(43, 0, 'Gaming: Casual Gaming'),
(44, 0, 'Gaming: Evony'),
(45, 0, 'Gaming: Skill Gaming'),
(46, 0, 'Giveaways: Free with Disclosure Language'),
(47, 0, 'Health: Acai'),
(48, 0, 'Health: Cosmetic'),
(49, 0, 'Health: Fitness/Diet/Nutrition'),
(50, 0, 'Health: medical'),
(51, 0, 'Health: online pharmacy'),
(52, 0, 'Home: Family and Parenting'),
(53, 0, 'Home: Home and Gardening'),
(54, 0, 'Immigration: Lawyers'),
(55, 0, 'Immigration: Service'),
(56, 0, 'Internet : Football Fantasy'),
(57, 0, 'Internet: Instant messaging'),
(58, 0, 'Internet: Other'),
(59, 0, 'Internet: Reunion/Classmates'),
(60, 0, 'Internet: Social'),
(61, 0, 'LeadGeneration: ** Incentive level : Win, you won, you lost!'),
(62, 0, 'LeadGeneration: Surveys'),
(63, 0, 'Lottery: Lottery'),
(64, 0, 'Lottery: StateLottery'),
(65, 0, 'Mixed/Premium'),
(66, 0, 'Mixed/Unknown'),
(67, 0, 'Mobile: Downloads - Mobile phone software'),
(68, 0, 'Mobile: Mobile Entetainment'),
(69, 0, 'Mobile: Mobile Services'),
(70, 0, 'Mobile: Win stuff - Sweepstakes'),
(71, 0, 'Multi-level Marketing (MLM)'),
(72, 0, 'Offer Type Difficult to Determine'),
(73, 0, 'Other'),
(74, 0, 'Public Service'),
(75, 0, 'Service'),
(76, 0, 'Shopping: Coupons and Deals'),
(77, 0, 'Shopping: Shopping (General)'),
(78, 0, 'Shopping: Shopping - clothing/shoes/accessories'),
(79, 0, 'Shopping: Shopping - cosmetics and personal appearance products'),
(80, 0, 'Shopping: Shopping Online'),
(81, 0, 'Sports'),
(82, 0, 'Technology'),
(83, 0, 'Technology - Computers: Computer Hardware (not software)'),
(84, 0, 'Technology - Computers: Computer software (not free downloads)'),
(85, 0, 'Telecom: Carriers'),
(86, 0, 'Telecom: ISP'),
(87, 0, 'Telecom: Other'),
(88, 0, 'Telecom: VOIP'),
(89, 0, 'Tobacco'),
(90, 0, 'Travel: Air-Line'),
(91, 0, 'Travel: Car Rental'),
(92, 0, 'Travel: Travel'),
(93, 0, 'Books'),
(94, 0, 'Business'),
(95, 0, 'Book &amp; Reference'),
(96, 0, 'Catalogs'),
(97, 0, 'Comics'),
(98, 0, 'Education'),
(99, 0, 'Entertainment'),
(100, 0, 'Finance'),
(101, 0, 'Food &amp; Drink'),
(102, 0, 'Health &amp; Fitness'),
(103, 0, 'Libraries &amp; Demo'),
(104, 0, 'Lifestyle'),
(105, 0, 'Live Wallpaper'),
(106, 0, 'Media &amp; Video'),
(107, 0, 'Medical'),
(108, 0, 'Music &amp; Audio'),
(109, 0, 'Navigation'),
(110, 0, 'News &amp; Magazines'),
(111, 0, 'Personalization'),
(112, 0, 'Photo &amp; Video'),
(113, 0, 'Productivity'),
(114, 0, 'Refrence'),
(115, 0, 'Shopping'),
(116, 0, 'Social Networking'),
(117, 0, 'Tools'),
(118, 0, 'Transportation'),
(119, 0, 'Travel &amp; Local'),
(120, 0, 'Utilities'),
(121, 0, 'Weather'),
(122, 0, 'Widgets'),
(123, 0, 'communication'),
(124, 0, 'Games : Arcade &amp; Action'),
(125, 0, 'Games : Brain &amp; Puzzle'),
(126, 0, 'Games : Cards &amp; Casino'),
(127, 0, 'Games : Casual'),
(128, 0, 'Games : Live Wallpaper'),
(129, 0, 'Games : Racing'),
(130, 0, 'Games : Sports Games'),
(131, 0, 'Games : Widgets'),
(132, 0, 'Games : Adventure'),
(133, 0, 'Games : Board'),
(134, 0, 'Games : Dice'),
(135, 0, 'Games : Educational'),
(136, 0, 'Games : Family'),
(137, 0, 'Games : Music'),
(138, 0, 'Games : Role Playing'),
(139, 0, 'Games : Simulation'),
(140, 0, 'Games : Strategy'),
(141, 0, 'Games : Trivia'),
(142, 0, 'Games : Word'),
(143, 0, 'Newsstand'),
(144, 0, 'Games'),
(145, 0, 'Newsstand : Arts &amp; Photography'),
(146, 0, 'Newsstand : Automotive'),
(147, 0, 'Newsstand : Brides &amp; Weddings'),
(148, 0, 'Newsstand : Business &amp; Investing'),
(149, 0, 'Newsstand : Children''s Magazines'),
(150, 0, 'Newsstand : Computers &amp; Internet'),
(151, 0, 'Newsstand : Cooking, Food &amp; Drink'),
(152, 0, 'Newsstand : Crafts &amp; Hobbies'),
(153, 0, 'Newsstand : Electronics &amp; Audio'),
(154, 0, 'Newsstand : Fashion &amp; Style'),
(155, 0, 'Newsstand : Entertainment'),
(156, 0, 'Newsstand : Health, Mind &amp; Body'),
(157, 0, 'Newsstand : History'),
(158, 0, 'Newsstand : Home &amp; Garden'),
(159, 0, 'Newsstand : Literary Magazines &amp; Journals'),
(160, 0, 'Newsstand : Men''s Interest'),
(161, 0, 'Newsstand : Movies &amp; Music'),
(162, 0, 'Newsstand : News &amp; Politics'),
(163, 0, 'Newsstand : Outdoors &amp; Nature'),
(164, 0, 'Newsstand :  Parenting &amp; Family'),
(165, 0, 'Newsstand : Pets'),
(166, 0, 'Newsstand : Professional &amp; Trade'),
(167, 0, 'Newsstand : Regional News'),
(168, 0, 'Newsstand :  Science'),
(169, 0, 'Newsstand : Sports &amp; Leisure'),
(170, 0, 'Newsstand : Teens'),
(171, 0, 'Newsstand :  Travel &amp; Regional'),
(172, 0, 'Newsstand : Women''s Interest'),
(173, 0, 'Newsstand : Science'),
(174, 0, 'Newsstand : Travel &amp; Regional');

-- --------------------------------------------------------

--
-- Table structure for table `campaign_urls`
--

DROP TABLE IF EXISTS `campaign_urls`;
CREATE TABLE IF NOT EXISTS `campaign_urls` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rec` tinyint(1) NOT NULL DEFAULT '0',
  `networks_id` int(11) NOT NULL,
  `url` varchar(128) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `campaigns`
--

DROP TABLE IF EXISTS `campaigns`;
CREATE TABLE IF NOT EXISTS `campaigns` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rec` tinyint(1) NOT NULL DEFAULT '0',
  `opportunities_id` int(11) NOT NULL,
  `name` varchar(128) COLLATE utf8_spanish_ci NOT NULL,
  `url` varchar(256) COLLATE utf8_spanish_ci NOT NULL,
  `campaign_categories_id` int(11) NOT NULL,
  `offer_type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0:VAS,1:app owners,2:branding,3:lead generation',
  `currency` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0:peso,1:dollar,2:euro,3:real',
  `budget_type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0:open,1:fixed,2:payment',
  `budget` decimal(11,2) NOT NULL,
  `cap` decimal(11,2) NOT NULL,
  `model` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0:CPA,1:CPC,2:CPM,2:CPL',
  `bid` decimal(11,2) NOT NULL,
  `comment` varchar(128) COLLATE utf8_spanish_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0:Active,1:Inactive,2:',
  `date_start` date NOT NULL,
  `date_end` date NOT NULL,
  `gc_id` varchar(10) COLLATE utf8_spanish_ci DEFAULT NULL,
  `gc_language` varchar(10) COLLATE utf8_spanish_ci DEFAULT NULL,
  `gc_format` tinyint(2) DEFAULT NULL,
  `gc_color` varchar(10) COLLATE utf8_spanish_ci DEFAULT NULL,
  `gc_label` varchar(30) COLLATE utf8_spanish_ci DEFAULT NULL,
  `gr_only` varchar(10) COLLATE utf8_spanish_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_campaigns_opportunities` (`opportunities_id`),
  KEY `fk_campaigns_campaign_categories` (`campaign_categories_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=7 ;

--
-- Dumping data for table `campaigns`
--

INSERT INTO `campaigns` (`id`, `rec`, `opportunities_id`, `name`, `url`, `campaign_categories_id`, `offer_type`, `currency`, `budget_type`, `budget`, `cap`, `model`, `bid`, `comment`, `status`, `date_start`, `date_end`, `gc_id`, `gc_language`, `gc_format`, `gc_color`, `gc_label`, `gr_only`) VALUES
(1, 0, 0, '**KICK ads - ARGENTINA - SUBWAY SURF TEST', 'http://wap.renxo.com/subscriptionPlanDetail.do?grp=1430&opd=false&pbid=780&ceid=12300&wc=12816&trk=true', 0, 0, 0, 0, 0.00, 0.00, 0, 0.00, 'a', 0, '2014-06-27', '2014-06-29', NULL, NULL, NULL, NULL, NULL, NULL),
(2, 0, 0, '-KICK ads - ARGENTINA - SUBWAY SURF TEST', 'http://wap.renxo.com/subscriptionPlanDetail.do?grp=1430&opd=false&pbid=780&ceid=12300&wc=12816&trk=true', 0, 0, 0, 0, 0.00, 0.00, 0, 0.00, 'a', 0, '2014-06-27', '2014-06-29', '970651684', 'es', 3, 'ffffff', 'wAWJCLyWhQoQpPDrzgM', 'false'),
(3, 0, 0, 'KICK ads - ARGENTINA - SUBWAY SURF TEST', 'http://wap.renxo.com/subscriptionPlanDetail.do?grp=1430&opd=false&pbid=780&ceid=12300&wc=12816&trk=true', 0, 0, 0, 0, 0.00, 0.00, 0, 0.00, 'a', 0, '2014-06-27', '2014-06-29', '970651684', 'es', 3, 'ffffff', 'wAWJCLyWhQoQpPDrzgM', 'false');

-- --------------------------------------------------------

--
-- Table structure for table `clicks_log`
--

DROP TABLE IF EXISTS `clicks_log`;
CREATE TABLE IF NOT EXISTS `clicks_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `campaigns_id` int(11) NOT NULL,
  `networks_id` int(11) NOT NULL,
  `tid` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `server_ip` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `server_name` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `user_agent` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `languaje` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `referer` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=56 ;

--
-- Dumping data for table `clicks_log`
--

INSERT INTO `clicks_log` (`id`, `campaigns_id`, `networks_id`, `tid`, `date`, `server_ip`, `server_name`, `user_agent`, `languaje`, `referer`) VALUES
(1, 1, 2, '', '2014-05-23 13:57:10', '', '', '', '', ''),
(2, 1, 2, '', '0000-00-00 00:00:00', '', '', '', '', ''),
(3, 1, 2, '', '0000-00-00 00:00:00', '', '', '', '', ''),
(4, 1, 2, '', '0000-00-00 00:00:00', '', '', '', '', ''),
(5, 1, 2, '', '2014-05-23 14:22:17', '', '', '', '', ''),
(6, 1, 2, '', '2014-05-23 14:25:32', '', '', '', '', ''),
(7, 1, 2, '', '2014-05-23 14:32:30', '', '', '', '', ''),
(8, 1, 2, '', '2014-05-23 14:32:57', '', '', '', '', ''),
(9, 1, 2, '', '2014-05-23 15:56:27', '', '', '', '', ''),
(10, 1, 2, '', '2014-05-23 15:57:48', '', '', '', '', ''),
(11, 1, 2, '', '2014-05-23 15:58:04', '', '', '', '', ''),
(12, 1, 2, '', '2014-05-23 16:00:45', '', '', '', '', ''),
(13, 1, 2, '', '2014-05-23 16:33:26', '', '', '', '', ''),
(14, 1, 2, '', '2014-05-23 16:33:43', '', '', '', '', ''),
(15, 1, 2, '', '2014-05-23 16:33:57', '', '', '', '', ''),
(16, 1, 2, '', '2014-05-23 16:49:00', '127.0.0.1', 'localhost', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/34.0.1847.116 Chrome/34.0.1847.116 Safari/537.36', 'es-ES,es;q=0.8,en;q=0.6,fr;q=0.4,it;q=0.2,pt;q=0.2', ''),
(17, 1, 2, '', '2014-05-23 16:49:16', '127.0.0.1', 'localhost', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/34.0.1847.116 Chrome/34.0.1847.116 Safari/537.36', 'es-ES,es;q=0.8,en;q=0.6,fr;q=0.4,it;q=0.2,pt;q=0.2', ''),
(18, 1, 2, '', '2014-05-23 16:49:50', '127.0.0.1', 'localhost', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/34.0.1847.116 Chrome/34.0.1847.116 Safari/537.36', 'es-ES,es;q=0.8,en;q=0.6,fr;q=0.4,it;q=0.2,pt;q=0.2', ''),
(19, 1, 2, '', '2014-05-23 16:50:15', '127.0.0.1', 'localhost', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/34.0.1847.116 Chrome/34.0.1847.116 Safari/537.36', 'es-ES,es;q=0.8,en;q=0.6,fr;q=0.4,it;q=0.2,pt;q=0.2', ''),
(20, 1, 2, '', '2014-05-23 17:26:45', '127.0.0.1', 'localhost', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/34.0.1847.116 Chrome/34.0.1847.116 Safari/537.36', 'es-ES,es;q=0.8,en;q=0.6,fr;q=0.4,it;q=0.2,pt;q=0.2', ''),
(21, 1, 2, '', '2014-05-23 17:28:09', '127.0.0.1', 'localhost', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/34.0.1847.116 Chrome/34.0.1847.116 Safari/537.36', 'es-ES,es;q=0.8,en;q=0.6,fr;q=0.4,it;q=0.2,pt;q=0.2', ''),
(22, 1, 2, '', '2014-05-23 17:28:26', '127.0.0.1', 'localhost', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/34.0.1847.116 Chrome/34.0.1847.116 Safari/537.36', 'es-ES,es;q=0.8,en;q=0.6,fr;q=0.4,it;q=0.2,pt;q=0.2', ''),
(23, 1, 2, '', '2014-05-23 17:29:22', '127.0.0.1', 'localhost', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/34.0.1847.116 Chrome/34.0.1847.116 Safari/537.36', 'es-ES,es;q=0.8,en;q=0.6,fr;q=0.4,it;q=0.2,pt;q=0.2', ''),
(24, 1, 2, '', '2014-05-23 17:29:23', '127.0.0.1', 'localhost', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/34.0.1847.116 Chrome/34.0.1847.116 Safari/537.36', 'es-ES,es;q=0.8,en;q=0.6,fr;q=0.4,it;q=0.2,pt;q=0.2', ''),
(25, 1, 2, '', '2014-05-23 17:29:51', '127.0.0.1', 'localhost', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/34.0.1847.116 Chrome/34.0.1847.116 Safari/537.36', 'es-ES,es;q=0.8,en;q=0.6,fr;q=0.4,it;q=0.2,pt;q=0.2', ''),
(26, 1, 2, '', '2014-05-23 17:32:15', '127.0.0.1', 'localhost', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/34.0.1847.116 Chrome/34.0.1847.116 Safari/537.36', 'es-ES,es;q=0.8,en;q=0.6,fr;q=0.4,it;q=0.2,pt;q=0.2', ''),
(27, 1, 2, '', '2014-05-23 17:32:30', '127.0.0.1', 'localhost', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/34.0.1847.116 Chrome/34.0.1847.116 Safari/537.36', 'es-ES,es;q=0.8,en;q=0.6,fr;q=0.4,it;q=0.2,pt;q=0.2', ''),
(28, 2, 2, '', '2014-05-23 17:37:36', '127.0.0.1', 'localhost', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/34.0.1847.116 Chrome/34.0.1847.116 Safari/537.36', 'es-ES,es;q=0.8,en;q=0.6,fr;q=0.4,it;q=0.2,pt;q=0.2', ''),
(29, 2, 2, '', '2014-05-23 18:04:42', '127.0.0.1', 'localhost', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/34.0.1847.116 Chrome/34.0.1847.116 Safari/537.36', 'es-ES,es;q=0.8,en;q=0.6,fr;q=0.4,it;q=0.2,pt;q=0.2', ''),
(30, 2, 2, '', '2014-05-23 18:05:00', '127.0.0.1', 'localhost', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/34.0.1847.116 Chrome/34.0.1847.116 Safari/537.36', 'es-ES,es;q=0.8,en;q=0.6,fr;q=0.4,it;q=0.2,pt;q=0.2', ''),
(31, 2, 2, '', '2014-05-23 18:05:02', '127.0.0.1', 'localhost', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/34.0.1847.116 Chrome/34.0.1847.116 Safari/537.36', 'es-ES,es;q=0.8,en;q=0.6,fr;q=0.4,it;q=0.2,pt;q=0.2', ''),
(32, 2, 2, '', '2014-05-23 18:05:05', '127.0.0.1', 'localhost', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/34.0.1847.116 Chrome/34.0.1847.116 Safari/537.36', 'es-ES,es;q=0.8,en;q=0.6,fr;q=0.4,it;q=0.2,pt;q=0.2', ''),
(33, 2, 2, '', '2014-05-23 18:05:09', '127.0.0.1', 'localhost', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/34.0.1847.116 Chrome/34.0.1847.116 Safari/537.36', 'es-ES,es;q=0.8,en;q=0.6,fr;q=0.4,it;q=0.2,pt;q=0.2', ''),
(34, 2, 2, '', '2014-05-23 18:05:10', '127.0.0.1', 'localhost', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/34.0.1847.116 Chrome/34.0.1847.116 Safari/537.36', 'es-ES,es;q=0.8,en;q=0.6,fr;q=0.4,it;q=0.2,pt;q=0.2', ''),
(35, 1, 2, '', '2014-06-04 19:07:18', '127.0.0.1', 'localhost', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/34.0.1847.116 Chrome/34.0.1847.116 Safari/537.36', 'es-ES,es;q=0.8,en;q=0.6,fr;q=0.4,it;q=0.2,pt;q=0.2', ''),
(36, 1, 2, '', '2014-06-04 19:15:08', '127.0.0.1', 'localhost', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/34.0.1847.116 Chrome/34.0.1847.116 Safari/537.36', 'es-ES,es;q=0.8,en;q=0.6,fr;q=0.4,it;q=0.2,pt;q=0.2', ''),
(37, 1, 2, '', '2014-06-04 20:49:16', '127.0.0.1', 'localhost', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/34.0.1847.116 Chrome/34.0.1847.116 Safari/537.36', 'es-ES,es;q=0.8,en;q=0.6,fr;q=0.4,it;q=0.2,pt;q=0.2', ''),
(38, 1, 2, '5b384ce32d8cdef02bc3a139d4cac0a22bb029e8', '2014-06-04 20:59:33', '127.0.0.1', 'localhost', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/34.0.1847.116 Chrome/34.0.1847.116 Safari/537.36', 'es-ES,es;q=0.8,en;q=0.6,fr;q=0.4,it;q=0.2,pt;q=0.2', ''),
(39, 1, 2, 'ca3512f4dfa95a03169c5a670a4c91a19b3077b4', '2014-06-04 21:00:43', '127.0.0.1', 'localhost', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/34.0.1847.116 Chrome/34.0.1847.116 Safari/537.36', 'es-ES,es;q=0.8,en;q=0.6,fr;q=0.4,it;q=0.2,pt;q=0.2', ''),
(40, 1, 2, 'd645920e395fedad7bbbed0eca3fe2e0', '2014-06-04 21:03:21', '127.0.0.1', 'localhost', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/34.0.1847.116 Chrome/34.0.1847.116 Safari/537.36', 'es-ES,es;q=0.8,en;q=0.6,fr;q=0.4,it;q=0.2,pt;q=0.2', ''),
(41, 1, 2, '3416a75f4cea9109507cacd8e2f2aefc', '2014-06-04 21:36:32', '127.0.0.1', 'localhost', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/34.0.1847.116 Chrome/34.0.1847.116 Safari/537.36', 'es-ES,es;q=0.8,en;q=0.6,fr;q=0.4,it;q=0.2,pt;q=0.2', ''),
(42, 1, 2, 'a1d0c6e83f027327d8461063f4ac58a6', '2014-06-04 21:36:46', '127.0.0.1', 'localhost', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/34.0.1847.116 Chrome/34.0.1847.116 Safari/537.36', 'es-ES,es;q=0.8,en;q=0.6,fr;q=0.4,it;q=0.2,pt;q=0.2', ''),
(43, 2, 2, '17e62166fc8586dfa4d1bc0e1742c08b', '2014-06-11 21:21:43', '127.0.0.1', 'localhost', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/34.0.1847.116 Chrome/34.0.1847.116 Safari/537.36', 'es-ES,es;q=0.8,en;q=0.6,fr;q=0.4,it;q=0.2,pt;q=0.2', ''),
(44, 2, 1, 'f7177163c833dff4b38fc8d2872f1ec6', '2014-06-11 21:21:49', '127.0.0.1', 'localhost', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/34.0.1847.116 Chrome/34.0.1847.116 Safari/537.36', 'es-ES,es;q=0.8,en;q=0.6,fr;q=0.4,it;q=0.2,pt;q=0.2', ''),
(45, 2, 2, '6c8349cc7260ae62e3b1396831a8398f', '2014-06-13 16:03:29', '127.0.0.1', 'localhost', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/34.0.1847.116 Chrome/34.0.1847.116 Safari/537.36', 'es-ES,es;q=0.8,en;q=0.6,fr;q=0.4,it;q=0.2,pt;q=0.2', ''),
(46, 2, 2, 'd9d4f495e875a2e075a1a4a6e1b9770f', '2014-06-13 16:26:02', '127.0.0.1', 'localhost', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/34.0.1847.116 Chrome/34.0.1847.116 Safari/537.36', 'es-ES,es;q=0.8,en;q=0.6,fr;q=0.4,it;q=0.2,pt;q=0.2', ''),
(47, 2, 2, '67c6a1e7ce56d3d6fa748ab6d9af3fd7', '2014-06-13 16:27:24', '127.0.0.1', 'localhost', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/34.0.1847.116 Chrome/34.0.1847.116 Safari/537.36', 'es-ES,es;q=0.8,en;q=0.6,fr;q=0.4,it;q=0.2,pt;q=0.2', ''),
(48, 2, 3, '642e92efb79421734881b53e1e1b18b6', '2014-06-13 16:27:30', '127.0.0.1', 'localhost', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/34.0.1847.116 Chrome/34.0.1847.116 Safari/537.36', 'es-ES,es;q=0.8,en;q=0.6,fr;q=0.4,it;q=0.2,pt;q=0.2', ''),
(49, 2, 3, 'f457c545a9ded88f18ecee47145a72c0', '2014-06-13 16:32:59', '127.0.0.1', 'localhost', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/34.0.1847.116 Chrome/34.0.1847.116 Safari/537.36', 'es-ES,es;q=0.8,en;q=0.6,fr;q=0.4,it;q=0.2,pt;q=0.2', ''),
(50, 2, 3, 'c0c7c76d30bd3dcaefc96f40275bdc0a', '2014-06-13 16:33:02', '127.0.0.1', 'localhost', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/34.0.1847.116 Chrome/34.0.1847.116 Safari/537.36', 'es-ES,es;q=0.8,en;q=0.6,fr;q=0.4,it;q=0.2,pt;q=0.2', ''),
(51, 2, 3, '2838023a778dfaecdc212708f721b788', '2014-06-13 18:26:51', '127.0.0.1', 'localhost', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/34.0.1847.116 Chrome/34.0.1847.116 Safari/537.36', 'es-ES,es;q=0.8,en;q=0.6,fr;q=0.4,it;q=0.2,pt;q=0.2', ''),
(52, 2, 3, '9a1158154dfa42caddbd0694a4e9bdc8', '2014-06-13 19:14:17', '127.0.0.1', 'localhost', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/34.0.1847.116 Chrome/34.0.1847.116 Safari/537.36', 'es-ES,es;q=0.8,en;q=0.6,fr;q=0.4,it;q=0.2,pt;q=0.2', ''),
(53, 2, 3, 'd82c8d1619ad8176d665453cfb2e55f0', '2014-06-13 19:18:01', '127.0.0.1', 'localhost', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/34.0.1847.116 Chrome/34.0.1847.116 Safari/537.36', 'es-ES,es;q=0.8,en;q=0.6,fr;q=0.4,it;q=0.2,pt;q=0.2', ''),
(54, 2, 3, 'a684eceee76fc522773286a895bc8436', '2014-06-13 19:42:34', '127.0.0.1', 'localhost', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/34.0.1847.116 Chrome/34.0.1847.116 Safari/537.36', 'es-ES,es;q=0.8,en;q=0.6,fr;q=0.4,it;q=0.2,pt;q=0.2', ''),
(55, 2, 3, 'b53b3a3d6ab90ce0268229151c9bde11', '2014-06-13 20:03:17', '127.0.0.1', 'localhost', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/34.0.1847.116 Chrome/34.0.1847.116 Safari/537.36', 'es-ES,es;q=0.8,en;q=0.6,fr;q=0.4,it;q=0.2,pt;q=0.2', '');

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

DROP TABLE IF EXISTS `contacts`;
CREATE TABLE IF NOT EXISTS `contacts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rec` tinyint(1) NOT NULL DEFAULT '0',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0:comercial,1:administrative,2:legal',
  `name` varchar(128) COLLATE utf8_spanish_ci NOT NULL,
  `lastname` varchar(128) COLLATE utf8_spanish_ci NOT NULL,
  `email` varchar(128) COLLATE utf8_spanish_ci NOT NULL,
  `phone` varchar(128) COLLATE utf8_spanish_ci NOT NULL,
  `cell` varchar(128) COLLATE utf8_spanish_ci NOT NULL,
  `skype` varchar(128) COLLATE utf8_spanish_ci NOT NULL,
  `comment` varchar(128) COLLATE utf8_spanish_ci NOT NULL,
  `country` varchar(128) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `conv_log`
--

DROP TABLE IF EXISTS `conv_log`;
CREATE TABLE IF NOT EXISTS `conv_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tid` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=8 ;

--
-- Dumping data for table `conv_log`
--

INSERT INTO `conv_log` (`id`, `tid`, `date`) VALUES
(1, 'a1d0c6e83f027327d8461063f4ac58a6', '2014-06-05 15:51:43'),
(2, 'a1d0c6e83f027327d8461063f4ac58a6', '2014-06-05 16:01:42'),
(3, '3416a75f4cea9109507cacd8e2f2aefc', '2014-06-05 16:06:19'),
(4, '9a1158154dfa42caddbd0694a4e9bdc8', '2014-06-13 19:15:38'),
(5, 'd82c8d1619ad8176d665453cfb2e55f0', '2014-06-13 19:18:18'),
(6, 'a684eceee76fc522773286a895bc8436', '2014-06-13 19:42:47'),
(7, 'b53b3a3d6ab90ce0268229151c9bde11', '2014-06-13 20:03:45');

-- --------------------------------------------------------

--
-- Table structure for table `daily_report`
--

DROP TABLE IF EXISTS `daily_report`;
CREATE TABLE IF NOT EXISTS `daily_report` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `campaigns_id` int(11) NOT NULL,
  `networks_id` int(11) NOT NULL,
  `imp` int(11) NOT NULL,
  `clics` int(11) NOT NULL,
  `conv` int(11) NOT NULL,
  `spend` decimal(11,2) NOT NULL,
  `model` tinyint(1) NOT NULL,
  `value` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=168 ;

--
-- Dumping data for table `daily_report`
--

INSERT INTO `daily_report` (`id`, `campaigns_id`, `networks_id`, `imp`, `clics`, `conv`, `spend`, `model`, `value`, `date`) VALUES
(1, 0, 0, 0, 0, 0, 0.00, 0, 0, '2014-05-30 18:46:39'),
(2, 0, 0, 0, 0, 0, 0.00, 0, 0, '2014-05-30 18:47:03'),
(3, 0, 0, 0, 0, 0, 0.00, 0, 0, '2014-05-30 18:47:03'),
(4, 0, 0, 0, 0, 0, 0.00, 0, 0, '2014-05-30 18:47:03'),
(5, 0, 0, 0, 0, 0, 0.00, 0, 0, '2014-05-30 18:47:04'),
(6, 0, 0, 0, 0, 0, 0.00, 0, 0, '2014-05-30 18:47:04'),
(7, 0, 0, 0, 0, 0, 0.00, 0, 0, '2014-05-30 21:34:40'),
(8, 0, 0, 0, 0, 0, 0.00, 0, 0, '2014-05-30 21:36:10'),
(9, 0, 0, 0, 0, 0, 0.00, 0, 0, '2014-05-30 21:39:19'),
(10, 0, 0, 0, 0, 0, 0.00, 0, 0, '2014-05-30 21:42:53'),
(11, 0, 0, 0, 0, 0, 0.00, 0, 0, '2014-05-30 21:43:46'),
(12, 0, 0, 0, 0, 0, 0.00, 0, 0, '2014-05-30 21:44:25'),
(13, 0, 0, 0, 0, 0, 0.00, 0, 0, '2014-05-30 21:44:44'),
(14, 0, 0, 0, 0, 0, 0.00, 0, 0, '2014-05-30 21:46:58'),
(15, 0, 0, 0, 0, 0, 0.00, 0, 0, '2014-05-30 21:48:32'),
(16, 0, 0, 0, 0, 0, 0.00, 0, 0, '2014-05-30 21:50:16'),
(17, 0, 0, 0, 0, 0, 0.00, 0, 0, '2014-05-30 21:51:01'),
(18, 0, 0, 0, 0, 0, 0.00, 0, 0, '2014-05-30 21:52:13'),
(19, 0, 0, 0, 0, 0, 0.00, 0, 0, '2014-05-30 21:52:16'),
(20, 0, 0, 0, 0, 0, 0.00, 0, 0, '2014-05-30 21:52:17'),
(21, 0, 0, 0, 0, 0, 0.00, 0, 0, '2014-05-30 21:52:31'),
(22, 0, 0, 0, 0, 0, 0.00, 0, 0, '2014-05-30 21:53:15'),
(23, 0, 0, 0, 0, 0, 0.00, 0, 0, '2014-06-02 18:48:49'),
(24, 0, 0, 0, 0, 0, 0.00, 0, 0, '2014-06-02 18:49:06'),
(25, 0, 0, 0, 0, 0, 0.00, 0, 0, '2014-06-02 18:55:10'),
(26, 0, 0, 0, 0, 0, 0.00, 0, 0, '2014-06-02 18:55:13'),
(27, 0, 0, 0, 0, 0, 0.00, 0, 0, '2014-06-02 19:05:40'),
(28, 0, 0, 0, 0, 0, 0.00, 0, 0, '2014-06-02 19:07:03'),
(29, 0, 0, 0, 0, 0, 0.00, 0, 0, '2014-06-02 19:07:10'),
(30, 0, 0, 0, 0, 0, 0.00, 0, 0, '2014-06-02 19:07:36'),
(31, 0, 0, 0, 0, 0, 0.00, 0, 0, '2014-06-02 19:07:37'),
(32, 0, 0, 0, 0, 0, 0.00, 0, 0, '2014-06-02 19:07:38'),
(33, 0, 0, 0, 0, 0, 0.00, 0, 0, '2014-06-02 19:07:38'),
(34, 0, 0, 0, 0, 0, 0.00, 0, 0, '2014-06-02 19:07:38'),
(35, 0, 0, 0, 0, 0, 0.00, 0, 0, '2014-06-02 19:08:20'),
(36, 0, 0, 0, 0, 0, 0.00, 0, 0, '2014-06-02 19:09:12'),
(37, 0, 0, 0, 0, 0, 0.00, 0, 0, '2014-06-02 19:09:49'),
(38, 0, 0, 0, 0, 0, 0.00, 0, 0, '2014-06-02 19:10:20'),
(39, 0, 0, 0, 0, 0, 0.00, 0, 0, '2014-06-02 20:01:26'),
(40, 0, 0, 0, 0, 0, 0.00, 0, 0, '2014-06-02 20:02:47'),
(41, 0, 0, 0, 0, 0, 0.00, 0, 0, '2014-06-02 20:05:55'),
(42, 0, 0, 0, 0, 0, 0.00, 0, 0, '2014-06-02 20:08:06'),
(43, 0, 0, 0, 0, 0, 0.00, 0, 0, '2014-06-02 20:33:29'),
(44, 0, 0, 0, 0, 0, 0.00, 0, 0, '2014-06-02 20:34:23'),
(45, 0, 0, 0, 0, 0, 0.00, 0, 0, '2014-06-02 20:35:35'),
(46, 0, 0, 0, 0, 0, 0.00, 0, 0, '2014-06-02 20:56:27'),
(47, 0, 0, 0, 0, 0, 0.00, 0, 0, '2014-06-02 20:56:46'),
(48, 0, 0, 0, 0, 0, 0.00, 0, 0, '2014-06-02 21:30:27'),
(49, 0, 0, 0, 0, 0, 0.00, 0, 0, '2014-06-02 21:32:26'),
(50, 0, 0, 0, 0, 0, 0.00, 0, 0, '2014-06-02 21:32:42'),
(51, 0, 0, 0, 0, 0, 0.00, 0, 0, '2014-06-02 21:34:51'),
(52, 0, 0, 0, 0, 0, 0.00, 0, 0, '2014-06-02 21:35:05'),
(53, 0, 0, 0, 0, 0, 0.00, 0, 0, '2014-06-02 21:35:29'),
(54, 0, 0, 0, 0, 0, 0.00, 0, 0, '2014-06-02 21:36:04'),
(55, 0, 0, 0, 0, 0, 0.00, 0, 0, '2014-06-02 21:37:27'),
(56, 0, 0, 0, 0, 0, 0.00, 0, 0, '2014-06-02 21:38:13'),
(57, 0, 0, 0, 0, 0, 0.00, 0, 0, '2014-06-02 21:39:18'),
(58, 1167170, 2, 56581, 634, 0, 9.00, 0, 0, '2014-06-02 21:57:16'),
(59, 1201884, 2, 42723, 903, 0, 0.00, 0, 0, '2014-06-03 19:58:45'),
(60, 1167170, 2, 15690, 287, 0, 0.00, 0, 0, '2014-06-03 19:58:45'),
(61, 1201886, 2, 152, 0, 0, 0.00, 0, 0, '2014-06-03 19:58:45'),
(62, 1201884, 2, 42723, 903, 0, 0.00, 0, 0, '2014-06-03 19:58:54'),
(63, 1167170, 2, 15690, 287, 0, 0.00, 0, 0, '2014-06-03 19:58:54'),
(64, 1201886, 2, 152, 0, 0, 0.00, 0, 0, '2014-06-03 19:58:54'),
(65, 1201884, 2, 42723, 903, 0, 22.00, 0, 0, '2014-06-03 20:13:17'),
(66, 1167170, 2, 15690, 287, 0, 22.00, 0, 0, '2014-06-03 20:13:17'),
(67, 1201886, 2, 152, 0, 0, 22.00, 0, 0, '2014-06-03 20:13:17'),
(68, 1201884, 2, 42723, 903, 0, 22.00, 0, 0, '2014-06-03 20:27:44'),
(69, 1167170, 2, 15690, 287, 0, 22.00, 0, 0, '2014-06-03 20:27:44'),
(70, 1201886, 2, 152, 0, 0, 22.00, 0, 0, '2014-06-03 20:27:44'),
(71, 1201884, 2, 42723, 903, 0, 9.03, 0, 0, '2014-06-03 20:30:00'),
(72, 1167170, 2, 15690, 287, 0, 4.30, 0, 0, '2014-06-03 20:30:00'),
(73, 1201886, 2, 152, 0, 0, 0.30, 0, 0, '2014-06-03 20:30:00'),
(74, 1201884, 2, 42723, 903, 0, 9.03, 0, 0, '2014-06-03 20:42:02'),
(75, 1167170, 2, 15690, 287, 0, 4.30, 0, 0, '2014-06-03 20:42:02'),
(76, 1201886, 2, 152, 0, 0, 0.30, 0, 0, '2014-06-03 20:42:02'),
(77, 1201884, 2, 42723, 903, 0, 9.03, 0, 0, '2014-06-03 20:46:22'),
(78, 1167170, 2, 15690, 287, 0, 4.30, 0, 0, '2014-06-03 20:46:22'),
(79, 1201886, 2, 152, 0, 0, 0.30, 0, 0, '2014-06-03 20:46:22'),
(80, 1201884, 2, 42723, 903, 0, 9.03, 0, 0, '2014-06-03 20:46:47'),
(81, 1167170, 2, 15690, 287, 0, 4.30, 0, 0, '2014-06-03 20:46:48'),
(82, 1201886, 2, 152, 0, 0, 0.30, 0, 0, '2014-06-03 20:46:48'),
(83, 1201884, 2, 42723, 903, 0, 9.03, 0, 0, '2014-06-03 20:46:50'),
(84, 1167170, 2, 15690, 287, 0, 4.30, 0, 0, '2014-06-03 20:46:50'),
(85, 1201886, 2, 152, 0, 0, 0.30, 0, 0, '2014-06-03 20:46:50'),
(86, 1201884, 2, 42723, 903, 0, 9.03, 0, 0, '2014-06-03 20:47:17'),
(87, 1167170, 2, 15690, 287, 0, 4.30, 0, 0, '2014-06-03 20:47:17'),
(88, 1201886, 2, 152, 0, 0, 0.30, 0, 0, '2014-06-03 20:47:17'),
(89, 1201884, 2, 42723, 903, 0, 9.03, 0, 0, '2014-06-03 20:47:25'),
(90, 1167170, 2, 15690, 287, 0, 4.30, 0, 0, '2014-06-03 20:47:25'),
(91, 1201886, 2, 152, 0, 0, 0.30, 0, 0, '2014-06-03 20:47:25'),
(92, 1201884, 2, 42723, 903, 0, 9.03, 0, 0, '2014-06-03 20:47:43'),
(93, 1167170, 2, 15690, 287, 0, 4.30, 0, 0, '2014-06-03 20:47:43'),
(94, 1201886, 2, 152, 0, 0, 0.30, 0, 0, '2014-06-03 20:47:43'),
(95, 1201884, 2, 42723, 903, 0, 9.03, 0, 0, '2014-06-03 20:53:05'),
(96, 1167170, 2, 15690, 287, 0, 4.30, 0, 0, '2014-06-03 20:53:05'),
(97, 1201886, 2, 152, 0, 0, 0.30, 0, 0, '2014-06-03 20:53:05'),
(98, 1201884, 2, 42723, 903, 0, 9.03, 0, 0, '2014-06-03 20:55:34'),
(99, 1167170, 2, 15690, 287, 0, 4.30, 0, 0, '2014-06-03 20:55:34'),
(100, 1201886, 2, 152, 0, 0, 0.30, 0, 0, '2014-06-03 20:55:34'),
(101, 1201884, 2, 42723, 903, 0, 9.03, 0, 0, '2014-06-03 20:56:31'),
(102, 1167170, 2, 15690, 287, 0, 4.30, 0, 0, '2014-06-03 20:56:31'),
(103, 1201886, 2, 152, 0, 0, 0.30, 0, 0, '2014-06-03 20:56:31'),
(104, 1201884, 2, 42723, 903, 0, 9.03, 0, 0, '2014-06-03 20:56:36'),
(105, 1167170, 2, 15690, 287, 0, 4.30, 0, 0, '2014-06-03 20:56:36'),
(106, 1201886, 2, 152, 0, 0, 0.30, 0, 0, '2014-06-03 20:56:36'),
(107, 1201884, 2, 42723, 903, 0, 9.03, 0, 0, '2014-06-03 20:56:54'),
(108, 1167170, 2, 15690, 287, 0, 4.30, 0, 0, '2014-06-03 20:56:54'),
(109, 1201886, 2, 152, 0, 0, 0.30, 0, 0, '2014-06-03 20:56:54'),
(110, 1201884, 2, 42723, 903, 0, 9.03, 0, 0, '2014-06-03 20:59:34'),
(111, 1167170, 2, 15690, 287, 0, 4.30, 0, 0, '2014-06-03 20:59:34'),
(112, 1201886, 2, 152, 0, 0, 0.30, 0, 0, '2014-06-03 20:59:34'),
(113, 1201884, 2, 42723, 903, 0, 9.03, 0, 0, '2014-06-03 20:59:49'),
(114, 1167170, 2, 15690, 287, 0, 4.30, 0, 0, '2014-06-03 20:59:49'),
(115, 1201886, 2, 152, 0, 0, 0.30, 0, 0, '2014-06-03 20:59:49'),
(116, 1201884, 2, 42723, 903, 0, 9.03, 0, 0, '2014-06-03 21:00:20'),
(117, 1167170, 2, 15690, 287, 0, 4.30, 0, 0, '2014-06-03 21:00:20'),
(118, 1201886, 2, 152, 0, 0, 0.30, 0, 0, '2014-06-03 21:00:20'),
(119, 1201884, 2, 42723, 903, 0, 9.03, 0, 0, '2014-06-03 21:00:28'),
(120, 1167170, 2, 15690, 287, 0, 4.30, 0, 0, '2014-06-03 21:00:29'),
(121, 1201886, 2, 152, 0, 0, 0.30, 0, 0, '2014-06-03 21:00:29'),
(122, 1201884, 2, 42723, 903, 0, 9.03, 0, 0, '2014-06-03 21:01:23'),
(123, 1167170, 2, 15690, 287, 0, 4.30, 0, 0, '2014-06-03 21:01:24'),
(124, 1201886, 2, 152, 0, 0, 0.30, 0, 0, '2014-06-03 21:01:24'),
(125, 1201884, 2, 42723, 903, 0, 9.03, 0, 0, '2014-06-03 21:01:35'),
(126, 1167170, 2, 15690, 287, 0, 4.30, 0, 0, '2014-06-03 21:01:35'),
(127, 1201886, 2, 152, 0, 0, 0.30, 0, 0, '2014-06-03 21:01:35'),
(128, 1201884, 2, 42723, 903, 0, 9.03, 0, 0, '2014-06-03 21:01:42'),
(129, 1167170, 2, 15690, 287, 0, 4.30, 0, 0, '2014-06-03 21:01:42'),
(130, 1201886, 2, 152, 0, 0, 0.30, 0, 0, '2014-06-03 21:01:42'),
(131, 1201884, 2, 42723, 903, 0, 9.03, 0, 0, '2014-06-03 21:02:11'),
(132, 1167170, 2, 15690, 287, 0, 4.30, 0, 0, '2014-06-03 21:02:11'),
(133, 1201886, 2, 152, 0, 0, 0.30, 0, 0, '2014-06-03 21:02:11'),
(134, 1201884, 2, 42723, 903, 0, 9.03, 0, 0, '2014-06-03 21:02:44'),
(135, 1167170, 2, 15690, 287, 0, 4.30, 0, 0, '2014-06-03 21:02:44'),
(136, 1201886, 2, 152, 0, 0, 0.30, 0, 0, '2014-06-03 21:02:45'),
(137, 1201884, 2, 42723, 903, 0, 9.03, 0, 0, '2014-06-03 21:05:16'),
(138, 1167170, 2, 15690, 287, 0, 4.30, 0, 0, '2014-06-03 21:05:16'),
(139, 1201886, 2, 152, 0, 0, 0.30, 0, 0, '2014-06-03 21:05:16'),
(140, 1201884, 2, 42723, 903, 0, 9.03, 0, 0, '2014-06-03 21:05:54'),
(141, 1167170, 2, 15690, 287, 0, 4.30, 0, 0, '2014-06-03 21:05:54'),
(142, 1201886, 2, 152, 0, 0, 0.30, 0, 0, '2014-06-03 21:05:54'),
(143, 1201884, 2, 42723, 903, 0, 9.03, 0, 0, '2014-06-03 21:05:57'),
(144, 1167170, 2, 15690, 287, 0, 4.30, 0, 0, '2014-06-03 21:05:58'),
(145, 1201886, 2, 152, 0, 0, 0.30, 0, 0, '2014-06-03 21:05:58'),
(146, 1201884, 2, 42723, 903, 0, 9.03, 0, 0, '2014-06-03 21:16:40'),
(147, 1167170, 2, 15690, 287, 0, 4.30, 0, 0, '2014-06-03 21:16:40'),
(148, 1201886, 2, 152, 0, 0, 0.30, 0, 0, '2014-06-03 21:16:40'),
(149, 1201884, 2, 42723, 903, 0, 9.03, 0, 0, '2014-06-03 21:17:04'),
(150, 1167170, 2, 15690, 287, 0, 4.30, 0, 0, '2014-06-03 21:17:04'),
(151, 1201886, 2, 152, 0, 0, 0.30, 0, 0, '2014-06-03 21:17:04'),
(152, 1201884, 2, 36502, 979, 0, 9.79, 0, 0, '2014-06-04 18:59:11'),
(153, 1201886, 2, 10925, 0, 0, 21.85, 0, 0, '2014-06-04 18:59:11'),
(154, 1167170, 2, 7450, 110, 0, 1.65, 0, 0, '2014-06-04 18:59:11'),
(155, 1201884, 2, 32650, 914, 0, 12.45, 0, 0, '2014-06-05 20:57:06'),
(156, 1167170, 2, 13436, 309, 0, 4.64, 0, 0, '2014-06-05 20:57:06'),
(157, 1201886, 2, 11541, 0, 0, 23.08, 0, 0, '2014-06-05 20:57:06'),
(158, 1202754, 2, 10922, 254, 0, 2.54, 0, 0, '2014-06-05 20:57:06'),
(159, 1202812, 2, 90, 2, 0, 0.06, 0, 0, '2014-06-05 20:57:06'),
(160, 1201884, 2, 31538, 976, 0, 14.61, 0, 0, '2014-06-06 16:03:46'),
(161, 1202754, 2, 23235, 564, 0, 5.64, 0, 0, '2014-06-06 16:03:46'),
(162, 1203390, 2, 5777, 213, 0, 2.13, 0, 0, '2014-06-06 16:03:46'),
(163, 1201886, 2, 3845, 0, 0, 7.69, 0, 0, '2014-06-06 16:03:46'),
(164, 1203395, 2, 1029, 19, 0, 10.29, 0, 0, '2014-06-06 16:03:46'),
(165, 1202812, 2, 215, 4, 0, 0.16, 0, 0, '2014-06-06 16:03:46'),
(166, 1203415, 2, 138, 0, 0, 0.14, 0, 0, '2014-06-06 16:03:47'),
(167, 1203383, 2, 42, 2, 0, 0.03, 0, 0, '2014-06-06 16:03:47');

-- --------------------------------------------------------

--
-- Table structure for table `finance_entities`
--

DROP TABLE IF EXISTS `finance_entities`;
CREATE TABLE IF NOT EXISTS `finance_entities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rec` tinyint(1) NOT NULL DEFAULT '0',
  `commercial` varchar(128) COLLATE utf8_spanish_ci NOT NULL,
  `address` varchar(128) COLLATE utf8_spanish_ci NOT NULL,
  `tax_id` varchar(128) COLLATE utf8_spanish_ci NOT NULL,
  `tax_percent` varchar(128) COLLATE utf8_spanish_ci NOT NULL,
  `zip_code` varchar(128) COLLATE utf8_spanish_ci NOT NULL,
  `country` varchar(128) COLLATE utf8_spanish_ci NOT NULL,
  `state` varchar(128) COLLATE utf8_spanish_ci NOT NULL,
  `web` varchar(128) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `imp_log`
--

DROP TABLE IF EXISTS `imp_log`;
CREATE TABLE IF NOT EXISTS `imp_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `campaigns_id` int(11) NOT NULL,
  `networks_id` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `ios`
--

DROP TABLE IF EXISTS `ios`;
CREATE TABLE IF NOT EXISTS `ios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rec` tinyint(1) NOT NULL DEFAULT '0',
  `advertisers_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(128) COLLATE utf8_spanish_ci NOT NULL,
  `offer_type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0:VAS,1:app owners,2:branding,3:lead generation',
  `currency` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0:peso,1:dollar,2:euro,3:real',
  `budget_type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0:open,1:fixed,2:payment',
  `budget` decimal(11,2) NOT NULL,
  `model` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0:CPA,1:CPC,2:CPM,2:CPL',
  `bid` decimal(11,2) NOT NULL,
  `invoice_type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0:LTD,1:SRL',
  `net` decimal(11,2) NOT NULL,
  `comment` varchar(128) COLLATE utf8_spanish_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0:,1:,2:',
  `date_start` date NOT NULL,
  `date_end` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_ios_advertisers` (`advertisers_id`),
  KEY `fk_ios_users` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `monthly_traffic`
--

DROP TABLE IF EXISTS `monthly_traffic`;
CREATE TABLE IF NOT EXISTS `monthly_traffic` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `campaigns_id` int(11) NOT NULL,
  `networks_id` int(11) NOT NULL,
  `imp` int(11) NOT NULL,
  `clics` int(11) NOT NULL,
  `conv` int(11) NOT NULL,
  `spend` int(11) NOT NULL,
  `model` tinyint(1) NOT NULL,
  `value` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `networks`
--

DROP TABLE IF EXISTS `networks`;
CREATE TABLE IF NOT EXISTS `networks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rec` tinyint(1) NOT NULL DEFAULT '0',
  `name` varchar(128) COLLATE utf8_spanish_ci NOT NULL,
  `query_string` varchar(128) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=7 ;

--
-- Dumping data for table `networks`
--

INSERT INTO `networks` (`id`, `rec`, `name`, `query_string`) VALUES
(1, 0, 'Airpush', ''),
(2, 0, 'Reporo', ''),
(3, 0, 'Ajillion', ''),
(4, 0, 'Adwords', ''),
(5, 0, 'Kimia', ''),
(6, 0, 'LeadBolt', '');

-- --------------------------------------------------------

--
-- Table structure for table `opportunities`
--

DROP TABLE IF EXISTS `opportunities`;
CREATE TABLE IF NOT EXISTS `opportunities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rec` tinyint(1) NOT NULL DEFAULT '0',
  `ios_id` int(11) NOT NULL,
  `model` tinyint(1) NOT NULL COMMENT '1:CPA, 2:CPC, 3:CPM',
  `budget` decimal(11,2) NOT NULL,
  `rate` decimal(11,2) NOT NULL,
  `carrier` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `product` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_opportunities_ios` (`ios_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `publishers`
--

DROP TABLE IF EXISTS `publishers`;
CREATE TABLE IF NOT EXISTS `publishers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rec` tinyint(1) NOT NULL DEFAULT '0',
  `name` varchar(128) COLLATE utf8_spanish_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0:active,1:inactive',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user`
--

DROP TABLE IF EXISTS `tbl_user`;
CREATE TABLE IF NOT EXISTS `tbl_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(128) COLLATE utf8_spanish_ci NOT NULL,
  `password` varchar(128) COLLATE utf8_spanish_ci NOT NULL,
  `email` varchar(128) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=4 ;

--
-- Dumping data for table `tbl_user`
--

INSERT INTO `tbl_user` (`id`, `username`, `password`, `email`) VALUES
(1, 'test1', 'pass1', 'test1@example.com'),
(2, 'test2', 'pass2', 'test2@example.com'),
(3, 'christian', 'mil998', 'subespacios@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rec` tinyint(1) NOT NULL DEFAULT '0',
  `username` varchar(128) COLLATE utf8_spanish_ci NOT NULL,
  `password` varchar(128) COLLATE utf8_spanish_ci NOT NULL,
  `email` varchar(128) COLLATE utf8_spanish_ci NOT NULL,
  `name` varchar(128) COLLATE utf8_spanish_ci NOT NULL,
  `lastname` varchar(128) COLLATE utf8_spanish_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0:active,1:inactive',
  `role` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0:network,1:advertiser,2:publisher',
  `permissions` bit(5) NOT NULL DEFAULT b'0' COMMENT 'media,sales,finance,daily,admin',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=7 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `rec`, `username`, `password`, `email`, `name`, `lastname`, `status`, `role`, `permissions`) VALUES
(1, 0, 'hipercubus', '25aa0371a79f28acc97fd67e941e2295afe62e30', 'christian.motta@kickads.mobi', 'Christian', 'Motta', 0, 0, b'10001'),
(2, 0, 'pedroforwe', '265392dc2782778664cc9d56c8e3cd9956661bb0', 'pedro.forwe@kickads.mobi', 'Pedro', 'Forwe', 0, 0, b'10001'),
(5, 0, 'fernandoragel', '258c724cba05fdb365a0209d11a1866fe2aed7d7', 'fernando@vanega.com', 'Fernando', 'Ragel', 0, 0, b'10001'),
(6, 0, 'matiascerrotta', 'd5c781d3c9ee8f957978e10293e38e49820bb5e1', '', '', '', 0, 0, b'10000');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `AuthAssignment`
--
ALTER TABLE `AuthAssignment`
  ADD CONSTRAINT `AuthAssignment_ibfk_1` FOREIGN KEY (`itemname`) REFERENCES `AuthItem` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `AuthItemChild`
--
ALTER TABLE `AuthItemChild`
  ADD CONSTRAINT `AuthItemChild_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `AuthItem` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `AuthItemChild_ibfk_2` FOREIGN KEY (`child`) REFERENCES `AuthItem` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `advertisers`
--
ALTER TABLE `advertisers`
  ADD CONSTRAINT `fk_finance_entities` FOREIGN KEY (`finance_entities_id`) REFERENCES `finance_entities` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `campaigns`
--
ALTER TABLE `campaigns`
  ADD CONSTRAINT `fk_campaigns_opportunities` FOREIGN KEY (`opportunities_id`) REFERENCES `opportunities` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_campaigns_campaign_categories` FOREIGN KEY (`campaign_categories_id`) REFERENCES `campaign_categories` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `ios`
--
ALTER TABLE `ios`
  ADD CONSTRAINT `fk_ios_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_ios_advertisers` FOREIGN KEY (`advertisers_id`) REFERENCES `advertisers` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `opportunities`
--
ALTER TABLE `opportunities`
  ADD CONSTRAINT `fk_opportunities_ios` FOREIGN KEY (`ios_id`) REFERENCES `ios` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

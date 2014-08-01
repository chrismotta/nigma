--
-- Dumping data for table `advertisers`
--

INSERT INTO `advertisers` (`id`, `name`, `cat`, `commercial_id`) VALUES
(1, 'advertiser test', 'Branding', 1);


--
-- Dumping data for table `ios`
--

INSERT INTO `ios`(`id`, `name`, `address`, `country`, `state`, `zip_code`, `phone`, `email`, `contact_adm`, `currency`, `ret`, `tax_id`, `commercial_id`, `entity`, `net_payment`, `advertisers_id`) VALUES 
(1,'ios test','address 1234',2004,'state test','zip code tests','11-1111-1111','mail@test.mobi','contact name tests','Euro','ret tests','22-2222-2222',1,'SRL','net payment test',1);


--
-- Dumping data for table `opportunities`
--

INSERT INTO `opportunities`(`id`, `carriers_id`, `rate`, `model_adv`, `product`, `account_manager_id`, `comment`, `country_id`, `wifi`, `budget`, `server_to_server`, `startDate`, `endDate`, `ios_id`) VALUES 
(1,1,11.20,7,NULL,1,'comment tests',2004,'false',1000.90,'s2sTests','2014-06-03 21:16:40','2014-07-03 21:16:40',1);


--
-- Dumping data for table `campaigns`
--

INSERT INTO `campaigns`(`id`, `name`, `networks_id`, `campaign_categories_id`, `wifi`, `formats_id`, `cap`, `model`, `ip`, `devices_id`, `url`, `status`, `opportunities_id`) VALUES 
(1,'AJ-TIMWE-CH-AP-CLA-A-1003140',1,1,'true',2,10.20,'CPC','false',3,'http://wap.renxo.com/subscriptionPlanDetail.do?grp=1430&opd=false&pbid=780&ceid=12300&wc=12816&trk=true','Active',1),
(2,'AJ-TIMWE-CH-AP-CLA-A-1003141',1,1,'true',2,10.20,'CPC','false',3,'http://wap.renxo.com/subscriptionPlanDetail.do?grp=1430&opd=false&pbid=780&ceid=12300&wc=12816&trk=true','Active',1),
(3,'AJ-TIMWE-CH-AP-CLA-A-1003142',1,1,'true',2,10.20,'CPC','false',3,'http://wap.renxo.com/subscriptionPlanDetail.do?grp=1430&opd=false&pbid=780&ceid=12300&wc=12816&trk=true','Active',1);


--
-- Dumping data for table `daily_report`
--

INSERT INTO `daily_report` (`id`, `campaigns_id`, `networks_id`, `imp`, `clics`, `conv_api`, `conv_adv`, `spend`, `model`, `value`, `date`) VALUES
(41, 1, 1, 57818, 2159, 1, 0, 32.38, 0, 0, '2014-07-16 03:00:00'),
(42, 1, 1, 26932, 584, 1, 0, 5.84, 0, 0, '2014-07-15 03:00:00'),
(43, 1, 1, 16846, 485, 1, 0, 7.28, 0, 0, '2014-07-14 03:00:00'),
(44, 1, 1, 152, 0, 0, 0, 0.00, 0, 0, '2014-07-13 19:58:45'),
(45, 1, 1, 42723, 903, 0, 0, 0.00, 0, 0, '2014-07-12 19:58:54'),
(46, 1, 1, 15690, 287, 0, 0, 0.00, 0, 0, '2014-07-11 19:58:54'),
(47, 1, 1, 152, 0, 0, 0, 0.00, 0, 0, '2014-07-10 19:58:54'),
(48, 1, 1, 42723, 903, 0, 0, 22.00, 0, 0, '2014-07-09 20:13:17'),
(49, 1, 1, 15690, 287, 0, 0, 22.00, 0, 0, '2014-07-08 20:13:17'),
(50, 1, 1, 152, 0, 0, 0, 22.00, 0, 0, '2014-07-07 20:13:17'),

(51, 2, 2, 12344, 1159, 1, 0, 32.38, 0, 0, '2014-07-16 03:00:00'),
(52, 2, 2, 98264, 684, 1, 0, 5.84, 0, 0, '2014-07-15 03:00:00'),
(53, 2, 2, 8373, 985, 1, 0, 7.28, 0, 0, '2014-07-14 03:00:00'),
(54, 2, 2, 991, 0, 0, 0, 0.00, 0, 0, '2014-07-13 19:58:45'),
(55, 2, 2, 93751, 703, 0, 0, 0.00, 0, 0, '2014-07-12 19:58:54'),
(56, 2, 2, 12389, 187, 0, 0, 0.00, 0, 0, '2014-07-11 19:58:54'),
(57, 2, 2, 5544, 0, 0, 0, 0.00, 0, 0, '2014-07-10 19:58:54'),
(58, 2, 2, 32723, 1003, 0, 0, 22.00, 0, 0, '2014-07-09 20:13:17'),
(59, 2, 2, 5690, 587, 0, 0, 22.00, 0, 0, '2014-07-08 20:13:17'),
(60, 2, 2, 7889, 0, 0, 0, 22.00, 0, 0, '2014-07-07 20:13:17');




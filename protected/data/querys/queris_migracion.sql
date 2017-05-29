
# query 1

ALTER TABLE `nigma`.`F_Imp` 
DROP FOREIGN KEY `fk_F_Impressions_D_UserAgent2`,
DROP FOREIGN KEY `fk_F_Impressions_D_GeoLocation2`;
ALTER TABLE `nigma`.`F_Imp` 
DROP INDEX `fk_F_Impressions_D_UserAgent2_idx` ,
DROP INDEX `fk_F_Impressions_D_GeoLocation2_idx` ;

# query 2

ALTER TABLE `nigma`.`F_Imp` 
CHANGE COLUMN `D_GeoLocation_id` `D_GeoLocation_id` VARCHAR(255) NOT NULL ,
CHANGE COLUMN `D_UserAgent_id` `D_UserAgent_id` VARCHAR(255) NOT NULL ,
ADD COLUMN `imps` INT NULL DEFAULT 1 AFTER `referer_app`;

ALTER TABLE `nigma`.`F_Imp` 
ADD COLUMN `imps` INT NULL DEFAULT 1 AFTER `referer_app`;

# query 3

ALTER TABLE `nigma`.`D_GeoLocation` 
CHANGE COLUMN `id` `id` VARCHAR(255) NOT NULL ;

# query 4

ALTER TABLE `nigma`.`D_UserAgent` 
CHANGE COLUMN `id` `id` VARCHAR(255) NOT NULL ;



CREATE TABLE `F_Imp_Compact` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `D_Demand_id` int(11) NOT NULL,
  `D_Supply_id` int(11) NOT NULL,
  `D_GeoLocation_id` varchar(255) COLLATE utf8_bin NOT NULL,
  `D_UserAgent_id` varchar(255) COLLATE utf8_bin NOT NULL,
  `date_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `unique_id` varchar(40) COLLATE utf8_bin NOT NULL,
  `pubid` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `ip_forwarded` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `referer_url` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `referer_app` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `imps` int(11) DEFAULT '1',
  `unique_imps` int(11) DEFAULT '1',
  `revenue` decimal(11,6) DEFAULT '0.000000',
  `cost` decimal(11,6) DEFAULT '0.000000',
  PRIMARY KEY (`id`)
  KEY `fk_F_Impressions_D_Supply2_idx` (`D_Supply_id`),
  KEY `fk_F_Impressions_D_Demand2` (`D_Demand_id`),
  KEY `idx_F_Imp_date_time` (`date_time`),
  CONSTRAINT `fk_F_Impressions_D_Demand2` FOREIGN KEY (`D_Demand_id`) REFERENCES `D_Demand` (`tag_id`) ON UPDATE NO ACTION,
  CONSTRAINT `fk_F_Impressions_D_Supply2` FOREIGN KEY (`D_Supply_id`) REFERENCES `D_Supply` (`placement_id`) ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE `F_Imp_Compact` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `D_Demand_id` int(11) NOT NULL,
  `D_Supply_id` int(11) NOT NULL,
  `D_GeoLocation_id` varchar(255) COLLATE utf8_bin NOT NULL,
  `D_UserAgent_id` varchar(255) COLLATE utf8_bin NOT NULL,
  `date_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `unique_id` varchar(40) COLLATE utf8_bin NOT NULL,
  `pubid` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `ip_forwarded` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `referer_url` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `referer_app` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `imps` int(11) DEFAULT '1',
  `unique_imps` int(11) DEFAULT '1',
  `revenue` decimal(11,6) DEFAULT '0.000000',
  `cost` decimal(11,6) DEFAULT '0.000000',
  PRIMARY KEY (`id`),
  KEY `fk_F_Impressions_D_Supply2_idx` (`D_Supply_id`),
  KEY `fk_F_Impressions_D_Demand2` (`D_Demand_id`),
  KEY `idx_F_Imp_date_time` (`date_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;



INSERT INTO F_Imp_Compact
	(
		D_Demand_id,
		D_Supply_id,
		D_Geolocation_id,
		D_UserAgent_id,
		imps,
		date_time,
		unique_id,
		unique_imps,
        revenue,
        cost
    )
SELECT
	i.D_Demand_id AS D_Demand_id, 
	i.D_Supply_id AS D_Supply_id,
    i.D_Geolocation_id AS D_Geolocation_id,
    i.D_UserAgent_id AS D_UserAgent_id,
	count(i.id) AS imps,
    i.date_time AS date_time,
    i.unique_id AS unique_id,
    count(distinct i.unique_id) AS unique_imps,
    sum(b.revenue) AS revenue,
    sum(b.cost) AS cost

FROM F_Imp i

LEFT JOIN D_GeoLocation g ON (g.id = i.D_GeoLocation_id)
LEFT JOIN D_UserAgent a ON (a.id = i.D_UserAgent_id)
LEFT JOIN D_Bid b ON (b.F_Impressions_id = i.id)

WHERE month(date_time)=2 AND year(date_time)=2017 
GROUP BY i.D_Demand_id, i.D_Supply_id, g.country, a.os_type, 



# update new columns compact

UPDATE F_Imp_Compact i 
LEFT JOIN D_GeoLocation g ON (g.id = i.D_GeoLocation_id)
LEFT JOIN D_UserAgent a ON (a.id = i.D_UserAgent_id)
SET 
i.os_type = a.os_type,
i.country = g.country,
i.connection_type = CASE
WHEN g.connection_type = 'WIFI' THEN 'WIFI'
WHEN g.connection_type = '3G' THEN 'MOBILE'
WHEN g.connection_type = '' THEN NULL
ELSE NULL
END
WHERE YEAR(i.date_time) = 2016 AND MONTH(i.date_time) = 2

# update new columns not compact

UPDATE F_Imp_Compact i 
LEFT JOIN D_GeoLocation g ON (g.id = i.D_GeoLocation_id)
LEFT JOIN D_UserAgent a ON (a.id = i.D_UserAgent_id)
SET 
i.os_type = a.os_type,
i.user_agent = a.user_agent,
i.device_type = a.device_type,
i.device_brand = a.device_brand,
i.device_model = a.device_model,
i.os_version = a.os_version,
i.browser_type = a.browser_type,
i.browser_version = a.browser_version,
i.server_ip = g.server_ip,
i.carrier = g.carrier,
i.country = g.country,
i.connection_type = CASE
WHEN g.connection_type = 'WIFI' THEN 'WIFI'
WHEN g.connection_type = '3G' THEN 'MOBILE'
WHEN g.connection_type = '' THEN NULL
ELSE NULL
END
WHERE DATE(i.date_time) >= '2017-05-17' 


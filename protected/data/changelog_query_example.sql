
--
-- Create 1st version of all models in table opportunities_version
--
INSERT INTO `opportunities_version`(`id`, `carriers_id`, `rate`, `model_adv`, `product`, `account_manager_id`, `comment`, `country_id`, `wifi`, `budget`, `server_to_server`, `startDate`, `endDate`, `ios_id`, `freq_cap`, `imp_per_day`, `imp_total`, `targeting`, `sizes`, `channel`, `channel_description`, `status`,`created_time`) SELECT * FROM opportunities;

--
-- Update date from 1st version of all models in table opportunities_version
--
UPDATE opportunities_version SET created_time='2014-01-01 00:00:00';

--
-- Update current version from table opportunities
--
UPDATE opportunities o SET version=(SELECT version FROM opportunities_version ov WHERE ov.id=o.id);

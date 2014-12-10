-- ------------------------------ --
-- Insert elements into providers --
-- ------------------------------ --
INSERT INTO providers SELECT id, name FROM networks;
INSERT INTO providers SELECT id, name FROM providers;


-- ------------------------------ --
-- Update and change campaigns FK --
-- ------------------------------ --
ALTER TABLE `kickads_appserver`.`campaigns` ADD COLUMN `providers_id` INT(11) NULL DEFAULT NULL AFTER `name`;
UPDATE `kickads_appserver`.`campaigns` SET providers_id = networks_id;

ALTER TABLE `kickads_appserver`.`campaigns` 
	DROP FOREIGN KEY `fk_campaigns_networks`,
	DROP INDEX `fk_campaigns_networks_idx`,
	DROP COLUMN `networks_id`;

ALTER TABLE `kickads_appserver`.`campaigns` 
	ADD INDEX `fk_campaigns_providers_idx` (`providers_id` ASC),
	ADD CONSTRAINT `fk_campaigns_providers`
	  FOREIGN KEY (`providers_id`)
	  REFERENCES `kickads_appserver`.`providers` (`id`)
	  ON DELETE RESTRICT
	  ON UPDATE NO ACTION;


-- --------------------------------- --
-- Update and change api_cron_log FK --
-- --------------------------------- --
ALTER TABLE `kickads_appserver`.`api_cron_log` ADD COLUMN `providers_id` INT(11) NOT NULL AFTER `campaigns_id`;
UPDATE `kickads_appserver`.`api_cron_log`  SET providers_id = networks_id;

ALTER TABLE `kickads_appserver`.`api_cron_log` 
	DROP COLUMN `networks_id`,
	DROP FOREIGN KEY `fk_api_cron_log_networks`,
	DROP INDEX `fk_api_cron_log_networks_idx`;

ALTER TABLE `kickads_appserver`.`api_cron_log` 
	ADD INDEX `fk_api_cron_log_providers_idx` (`providers_id` ASC),
	ADD CONSTRAINT `fk_api_cron_log_providers`
	  FOREIGN KEY (`providers_id`)
	  REFERENCES `kickads_appserver`.`providers` (`id`)
	  ON DELETE RESTRICT
	  ON UPDATE NO ACTION;


-- --------------------------------- --
-- Update and change daily_report FK --
-- --------------------------------- --
ALTER TABLE `kickads_appserver`.`daily_report` ADD COLUMN `providers_id` INT(11) NULL DEFAULT NULL AFTER `campaigns_id`;
UPDATE `kickads_appserver`.`daily_report` SET providers_id = networks_id;

ALTER TABLE `kickads_appserver`.`daily_report` 
	DROP FOREIGN KEY `fk_daily_report_networks`,
	DROP COLUMN `networks_id`,
	DROP INDEX `fk_daily_report_networks_idx`;

ALTER TABLE `kickads_appserver`.`daily_report` 
	ADD INDEX `fk_daily_report_providers_idx` (`providers_id` ASC),
	ADD CONSTRAINT `fk_daily_report_providers`
	  FOREIGN KEY (`providers_id`)
	  REFERENCES `kickads_appserver`.`providers` (`id`)
	  ON DELETE RESTRICT
	  ON UPDATE NO ACTION;


-- ---------------------------- --
-- Update and change vectors FK --
-- ---------------------------- --
ALTER TABLE `kickads_appserver`.`vectors` ADD COLUMN `providers_id` INT(11) NOT NULL AFTER `id`;
UPDATE `kickads_appserver`.`vectors` SET providers_id = networks_id;

ALTER TABLE `kickads_appserver`.`vectors` 
	DROP FOREIGN KEY `fk_vectors_networks`,
	DROP COLUMN `networks_id`,
	DROP INDEX `fk_vectors_networks1_idx`;

ALTER TABLE `kickads_appserver`.`vectors` 
	ADD INDEX `fk_vectors_providers_idx` (`providers_id` ASC),
	ADD CONSTRAINT `fk_vectors_providers`
	  FOREIGN KEY (`providers_id`)
	  REFERENCES `kickads_appserver`.`providers` (`id`)
	  ON DELETE RESTRICT
	  ON UPDATE NO ACTION;


-- ---------------------------------------------- --
-- Update and change placements and publishers FK --
-- ---------------------------------------------- --
ALTER TABLE `kickads_appserver`.`placements` ADD COLUMN `publishers_providers_id` INT(11) NOT NULL AFTER `id`;
UPDATE `kickads_appserver`.`placements` SET `publishers_providers_id` = `publishers_id`;

ALTER TABLE `kickads_appserver`.`placements` 
	DROP FOREIGN KEY `fk_placements_publishers1`,
	DROP COLUMN `publishers_id`,
	DROP INDEX `fk_placements_publishers1_idx`;

ALTER TABLE `kickads_appserver`.`publishers` ADD COLUMN `providers_id` INT(11) NOT NULL FIRST;
UPDATE `kickads_appserver`.`publishers` SET providers_id = id;

ALTER TABLE `kickads_appserver`.`publishers`
	DROP COLUMN `id`,
	DROP PRIMARY KEY;

ALTER TABLE `kickads_appserver`.`publishers` 
	ADD PRIMARY KEY (`providers_id`),
	ADD INDEX `fk_publishers_providers1_idx` (`providers_id` ASC),
	ADD CONSTRAINT `fk_publishers_providers`
	  FOREIGN KEY (`providers_id`)
	  REFERENCES `kickads_appserver`.`providers` (`id`)
	  ON DELETE RESTRICT
	  ON UPDATE NO ACTION;

ALTER TABLE `kickads_appserver`.`placements` 
	ADD INDEX `fk_placements_publishers1_idx` (`publishers_providers_id` ASC),
	ADD CONSTRAINT `fk_placements_publishers1`
	  FOREIGN KEY (`publishers_providers_id`)
	  REFERENCES `kickads_appserver`.`publishers` (`providers_id`)
	  ON DELETE RESTRICT
	  ON UPDATE NO ACTION;


-- ------------------------------- --
-- Update and change clicks_log FK --
-- ------------------------------- --
ALTER TABLE `kickads_appserver`.`clicks_log` 
	-- CHANGE COLUMN `keyword` `keyword` VARCHAR(128) NULL DEFAULT NULL ,
	-- CHANGE COLUMN `creative` `creative` VARCHAR(128) NULL DEFAULT NULL ,
	ADD COLUMN `providers_id` INT(11) NULL DEFAULT NULL AFTER `campaigns_id`;
UPDATE `kickads_appserver`.`clicks_log` SET providers_id = networks_id;

ALTER TABLE `kickads_appserver`.`clicks_log` 
	DROP FOREIGN KEY `fk_clicks_log_networks`,
	DROP COLUMN `networks_id`,
	DROP INDEX `fk_clicks_log_networks1_idx`;

ALTER TABLE `kickads_appserver`.`clicks_log` 
	ADD INDEX `fk_clicks_log_providers_idx` (`providers_id` ASC),
	ADD CONSTRAINT `fk_clicks_log_providers`
	  FOREIGN KEY (`providers_id`)
	  REFERENCES `kickads_appserver`.`providers` (`id`)
	  ON DELETE RESTRICT
	  ON UPDATE NO ACTION;


-- ------------------------------- --
-- Update and change affiliates PK --
-- ------------------------------- --
ALTER TABLE `kickads_appserver`.`affiliates` ADD COLUMN `providers_id` INT(11) NOT NULL FIRST;
UPDATE `kickads_appserver`.`affiliates` SET providers_id = networks_id;

ALTER TABLE `kickads_appserver`.`affiliates` 
	DROP COLUMN `id`,
	DROP PRIMARY KEY,
	DROP INDEX `fk_affiliates_networks1_idx`,
	DROP FOREIGN KEY `fk_affiliates_users1`,
	DROP FOREIGN KEY `fk_affiliates_networks1`;

ALTER TABLE `kickads_appserver`.`affiliates` 
	ADD PRIMARY KEY (`providers_id`),
	ADD INDEX `fk_affiliates_providers1_idx` (`providers_id` ASC),
	ADD CONSTRAINT `fk_affiliates_users`
	  FOREIGN KEY (`users_id`)
	  REFERENCES `kickads_appserver`.`users` (`id`)
	  ON DELETE SET NULL
	  ON UPDATE NO ACTION,
	ADD CONSTRAINT `fk_affiliates_providers`
	  FOREIGN KEY (`providers_id`)
	  REFERENCES `kickads_appserver`.`providers` (`id`)
	  ON DELETE RESTRICT
	  ON UPDATE NO ACTION;

-- ----------------------------- --
-- Update and change networks PK --
-- ----------------------------- --
ALTER TABLE `kickads_appserver`.`networks` ADD COLUMN `providers_id` INT(11) NOT NULL FIRST;
UPDATE `kickads_appserver`.`networks` SET providers_id = id;

ALTER TABLE `kickads_appserver`.`networks` 
	DROP COLUMN `id`,
	DROP PRIMARY KEY;

ALTER TABLE `kickads_appserver`.`networks` 
	ADD PRIMARY KEY (`providers_id`),
	ADD INDEX `fk_networks_providers1_idx` (`providers_id` ASC),
	ADD CONSTRAINT `fk_networks_providers1`
	  FOREIGN KEY (`providers_id`)
	  REFERENCES `kickads_appserver`.`providers` (`id`)
	  ON DELETE RESTRICT
	  ON UPDATE NO ACTION;


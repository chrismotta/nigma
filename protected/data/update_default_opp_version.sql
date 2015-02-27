--
-- Add initial and default opportunities version for all those opportunities who 
-- has been enter in 2015.
-- The inserted values are the current info with 'created_time' fixed to 2014-01-01
--
-- This is to fix the backend modification that change campaigns's opportunities.
--
-- An example of this bug: 
--		An opportunity enter in 15-01-2015 that has campaigns with traffic during 
--		December, 2014.
--

INSERT INTO opportunities_version(
	id,
	carriers_id,
	rate,
	model_adv,
	product,
	account_manager_id,
	comment,
	country_id,
	wifi,
	budget,
	server_to_server,
	startDate,
	endDate,
	ios_id,
	freq_cap,
	imp_per_day,
	imp_total,
	targeting,
	sizes,
	channel,
	channel_description,
	status,
	created_time)
SELECT DISTINCT
	id,
	carriers_id,
	rate,
	model_adv,
	product,
	account_manager_id,
	comment,
	country_id,
	wifi,
	budget,
	server_to_server,
	startDate,
	endDate,
	ios_id,
	freq_cap,
	imp_per_day,
	imp_total,
	targeting,
	sizes,
	channel,
	channel_description,
	status,
	'2014-01-01' AS created_time
FROM opportunities_version
WHERE YEAR(created_time)='2015'
	AND id NOT IN (SELECT id FROM opportunities_version WHERE YEAR(created_time)='2014');
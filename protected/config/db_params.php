<?php

	$db_params = array(
		'connectionString'   => 'mysql:host=localhost;dbname=nigma',
		'emulatePrepare'     => true,
		'username'           => 'root',
		'password'           => 'mil998',
		'charset'            => 'utf8',
		// Uncomment to show db log
		// 'enableParamLogging' =>true,
		// 'enableProfiling'    =>true,
		'initSQLs'           => array(
           "SET SESSION time_zone = '".$dbTimeZone."'",
		)
	);
?>
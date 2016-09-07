<?php

	class dbConfig{

		const CONNECTION_STRING = 'mysql:host=localhost;dbname=nigma';
		const EMULATE_PREPARE = true;
		const USERNAME = 'root'
		const PASSWORD = 'mil998';
		const CHARSET = 'utf8';
		const PARAM_LOGGIN = null;
		const PROFILING = null;
		const INIT_SQL = serialize ( array(
           "SET SESSION time_zone = '".$dbTimeZone."'",
		) );
	}

?>
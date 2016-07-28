<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// yiibooster
Yii::setPathOfAlias('yiibooster', dirname(__FILE__).'/../extensions/yiibooster');
$dbTimeZone = isset($_COOKIE['dbTimeZone']) ? $_COOKIE['dbTimeZone'] : '+00:00';

// MySql Policy
function mysqlPolicy( $httpHost, $dbTimeZone='+00:00' ){

	switch ( $httpHost ) {
		
		// local
		case '127.0.0.1':
		case 'localhost':
					$mysqlConnect = array(
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
						),

					);
					
					$mailLog = array(
						'class'   =>'CPhpMailerLogRoute',
						'levels'  =>'',
						'subject' =>'',
						'emails'  =>array(),
					);
			break;
		
		// amazon prod
		case '52.206.109.37':
		case '52.70.104.25':
		case '54.172.221.175':
		case 'ec2-54-172-221-175.compute-1.amazonaws.com':
		case '52.91.118.99':
		case 'ec2-52-91-118-99.compute-1.amazonaws.com':
		case 'tmlbox.co':
		case 'www.tmlbox.co':
		case 'bidbox.co':
		case 'www.bidbox.co':
		case 'console.themedialab.co':
		case 'www.games4mobi.com':
		case 'games4mobi.com':
		case 'www.ringtones4mobile.co':
		case 'ringtones4mobile.co':
					$mysqlConnect = array(
						'connectionString' => 'mysql:host=tml.cch7ui9gbr3f.us-east-1.rds.amazonaws.com;dbname=nigma',
						'emulatePrepare'   => true,
						'username'         => 'www-data',
						'password'         => 'th3m3d14l4b',
						'charset'          => 'utf8',
						'initSQLs'         => array(
				           "SET SESSION time_zone = '".$dbTimeZone."'",
						),
					);

					$mailLog = array(
						'class'   =>'CPhpMailerLogRoute',
						'levels'  =>'',
						'subject' =>'',
						'emails'  =>array(),
					);
					/*$mailLog = array(
						'class'   =>'CPhpMailerLogRoute',
						'levels'  =>'error, mail',
						'subject' =>'Automatic Mail Log',
						'emails'  =>array(
							'chris@themedialab.co',
						),
						'config'  =>array(
							'From'       => 'no-reply@themedialab.co',
							'FromName'   => 'no-reply themedialab.co',
							// 'Host'       => "email-smtp.us-east-1.amazonaws.com",
							// 'SMTPAuth'   => true,
							// 'SMTPSecure' => "tls",
							// 'Port'       => 25,
							// 'Username'   => 'AKIAIQTRLJHEZETZDRSQ',
							// 'Password'   => 'Ag/ctgxpxYGrnQPxiahJiLNKldgoBJBr2M9mtf/Hz//F',
							'CharSet'    => "UTF-8",
	                    ),
	                );*/
			break;

		// amazon test
		case 'dev.tmlbox.co':
					$mysqlConnect = array(
						'connectionString' => 'mysql:host=tml.cch7ui9gbr3f.us-east-1.rds.amazonaws.com;dbname=nigma_dev',
						'emulatePrepare'   => true,
						'username'         => 'www-data',
						'password'         => 'th3m3d14l4b',
						'charset'          => 'utf8',
						'initSQLs'         => array(
				           "SET SESSION time_zone = '00:00'",
						),
					);

					$mailLog = array(
						'class'   =>'CPhpMailerLogRoute',
						'levels'  =>'',
						'subject' =>'',
						'emails'  =>array(),
					);
			break;

		// amazon sourcecode
		case 'kick.tmlbox.co':
					$mysqlConnect = array(
						'connectionString' => 'mysql:host=tml.cch7ui9gbr3f.us-east-1.rds.amazonaws.com;dbname=kickads_appserver',
						'emulatePrepare'   => true,
						'username'         => 'www-data',
						'password'         => 'th3m3d14l4b',
						'charset'          => 'utf8',
						// 'initSQLs'         => array(
				  //          "SET SESSION time_zone = '-3:00'",
						// ),
					);

					$mailLog = array(
						'class'   =>'CPhpMailerLogRoute',
						'levels'  =>'',
						'subject' =>'',
						'emails'  =>array(),
					);
			break;
		
		default:
			# code...
			break;
	}

	return $mysqlConnect;
}

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'       => dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'           => '',
	'theme'          => 'tml',
	'timeZone'       => 'GMT',
	'language'       => 'en',
	'sourceLanguage' => 'en',
	'charset'        => 'UTF-8',

	// preloading 'log' component
	'preload' =>array(
		'log',
		'bootstrap',
	),

	// autoloading model and component classes
	'import' =>array(
		'application.models.*',
		'application.models.api.*',
		'application.models.api.publisher.*',
		'application.components.*',
		'application.external.ip2location.IP2Location',
		'application.external.wurfl.WurflManager',
		'ext.eexcelwriter.components.EExcelWriter',
		'ext.pdffactory.EPdfFactoryDoc',
		'ext.samodelversioning.SAModelVersioning',
		'ext.ecsvexport.ECSVExport',
	),

	'modules'=>array(
		// uncomment the following to enable the Gii tool
		
		'gii'=>array(
			'generatorPaths'=>array(
                'yiibooster.gii',
            ),
			'class'=>'system.gii.GiiModule',
			'password'=>'mil998',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1'),
		),
		
	),

	// application components
	'components'=>array(
    
        'bootstrap'=>array(
            'class'=>'yiibooster.components.Bootstrap',
        ),
		'authManager'=>array(
			'class'=>'CDbAuthManager',
			'connectionID'=>'db'
		),
		'curl' =>array(
		   'class' => 'application.extensions.curl.Curl',
		),
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
		),
		// uncomment the following to enable URLs in path-format
		'urlManager'=>array(
			'urlFormat'=>'path',
			'showScriptName'=>false,
			'rules'=>array(
				// aliases //
				'<controller:clickslog>/<action:\w+>/<id:\d+>'   => 'clicklog/<action>',
				'<controller:clickLog>/<action:\w+>/<id:\d+>'   => 'clicklog/<action>',
				'<controller:clicksLog>/<action:\w+>/<id:\d+>'   => 'clicklog/<action>',
				// generic //
				'<controller:\w+>/<id:\d+>'                               =>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'                  =>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'                           =>'<controller>/<action>',
				// custom parameters //
				'<controller:apiUpdate>/<action:\w+>/<hash:\w+>'          =>'<controller>/<action>',
				'<controller:externalForms>/<action:\w+>/<hash:\w+>'      =>'<controller>/<action>',
				'<controller:wurfl>/<action:\w+>/<hash:\w+>'              =>'<controller>/<action>',
				'<controller:dailyPublishers>/<action:totals>/<hash:\w+>' =>'<controller>/<action>',
				'<controller:providers>/<action:admin>/<hash:\w+>'        =>'<controller>/<action>',
				'<controller:providers>/<action:create>/<hash:\w+>'       =>'<controller>/<action>',
				'<controller:tag>/<action:testIP>/<hash:\S+>'             =>'<controller>/<action>',
				//'<controller:\w+>/<action:\w+>/<hash:\w+>/<id:\d+>'     =>'<controller>/<action>',
			),
		),
		'eexcelwriter'=>array(
            'class'=>'eexcelwriter.components.EExcelWriter',
        ),
		'pdfFactory'=>array(
            'class'=>'ext.pdffactory.EPdfFactory',
 
            //'tcpdfPath'=>'ext.pdffactory.vendors.tcpdf', //=default: the path to the tcpdf library
            //'fpdiPath'=>'ext.pdffactory.vendors.fpdi', //=default: the path to the fpdi library
 
            //the cache duration
            'cacheHours'=>-1, //-1 = cache disabled, 0 = never expires, hours if >0
 
             //The alias path to the directory, where the pdf files should be created
            'pdfPath'=>'application.runtime.pdf',
 
            //The alias path to the *.pdf template files
            //'templatesPath'=>'application.pdf.templates', //= default
 
            //the params for the constructor of the TCPDF class  
            // see: http://www.tcpdf.org/doc/code/classTCPDF.html 
            'tcpdfOptions'=>array(
                  /* default values
                    'format'=>'A4',
                    'orientation'=>'P', //=Portrait or 'L' = landscape
                    'unit'=>'mm', //measure unit: mm, cm, inch, or point
                    'unicode'=>true,
                    'encoding'=>'UTF-8',
                    'diskcache'=>false,
                    'pdfa'=>false,
                   */
            )
        ),

		// uncomment the following to use a sqlite database
		/*
		'db'=>array(
			'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
		),
		*/
		
		// uncomment the following to use a MySQL database
		'db'=> mysqlPolicy($_SERVER['HTTP_HOST'], $dbTimeZone),
		
		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',
		),
		'log'=> array(
			'class'  =>'CLogRouter',
			'routes' =>array(
                array(
					'class'        =>'CDbLogRoute',
					'connectionID' =>'db',
					'logTableName' =>'log',
					'levels'       =>'info, profile, error, warning',
					'except' 	   =>'system.db.CDbCommand.*',
					'categories'   =>array('php.*', 'exception.*', 'system.*'),
                ),
				array( 
			 	    'class'	=>'CProfileLogRoute', 
			 	    'report'=>'callstack',  /* summary or callstack */ 
			 	), 
                // $mailLog,
        	),
        ),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'dbTimeZone' => isset($_COOKIE['dbTimeZone']) ? $_COOKIE['dbTimeZone'] : '+00:00',
		'adminEmail'=>'info@themedialab.co',
	    'defaultPageSize'=>50,
	    'serverIP'=>'52.70.104.25',
	    'landingDomains' => array('www.games4mobi.com','games4mobi.com'),
		'ipDbFile'       => dirname(__FILE__).'/../data/ip2location/ipdb.bin',
	),
);

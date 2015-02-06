<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// yiibooster
Yii::setPathOfAlias('yiibooster', dirname(__FILE__).'/../extensions/yiibooster');

// MySql Policy
switch ( $_SERVER['HTTP_HOST'] ) {
	// local
	case '127.0.0.1':
	case 'localhost':
				$mysqlConnect = array(
					'connectionString'   => 'mysql:host=localhost;dbname=kickads_appserver',
					'emulatePrepare'     => true,
					'username'           => 'root',
					'password'           => 'pernambuco',
					'charset'            => 'utf8',
					// Uncomment to show db log
					// 'enableParamLogging' =>true,
					// 'enableProfiling'    =>true,
					'initSQLs'           => array(
			           "SET SESSION time_zone = '-3:00'",
					),

				);
				
				$mailLog = array(
					'class'   =>'CPhpMailerLogRoute',
					'levels'  =>'',
					'subject' =>'',
					'emails'  =>array(),
				);
		break;
	// iweb prod
	case '70.38.54.231':
				$mysqlConnect = array(
					'connectionString' => 'mysql:host=localhost;dbname=kickads_appserver',
					'emulatePrepare'   => true,
					'username'         => 'root',
					'password'         => 'pernambuco',
					'charset'          => 'utf8',
					'initSQLs'         => array(
			           "SET SESSION time_zone = '-3:00'",
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
	case '54.88.85.63':
	case 'ec2-54-88-85-63.compute-1.amazonaws.com':
	case 'app.kickadserver.mobi':
	case 'kickadserver.mobi':
	case 'www.kickadserver.mobi':
				$mysqlConnect = array(
					'connectionString' => 'mysql:host=kickads-db-3.ccqfyxyzmdiq.us-east-1.rds.amazonaws.com;dbname=kickads_appserver',
					'emulatePrepare'   => true,
					'username'         => 'www-data',
					'password'         => 'k1ck4ds3rv3r',
					'charset'          => 'utf8',
					'initSQLs'         => array(
			           "SET SESSION time_zone = '-3:00'",
					),
				);

				$mailLog = array(
					'class'   =>'CPhpMailerLogRoute',
					'levels'  =>'error, mail',
					'subject' =>'Automatic Mail Log',
					'emails'  =>array(
						'christian.motta@kickads.mobi',
						'matias.cerrotta@kickads.mobi',
						'santiago.mena@kickads.mobi',
					),
					'config'  =>array(
						'From'       => 'no-reply@kickads.mobi',
						'FromName'   => 'no-reply Kickads adServer',
						// 'Host'       => "email-smtp.us-east-1.amazonaws.com",
						// 'SMTPAuth'   => true,
						// 'SMTPSecure' => "tls",
						// 'Port'       => 25,
						// 'Username'   => 'AKIAIQTRLJHEZETZDRSQ',
						// 'Password'   => 'Ag/ctgxpxYGrnQPxiahJiLNKldgoBJBr2M9mtf/Hz//F',
						'CharSet'    => "UTF-8",
                    ),
                );
		break;
	// amazon test
	case 'test.kickadserver.mobi':
				$mysqlConnect = array(
					'connectionString' => 'mysql:host=kickads-db-3.ccqfyxyzmdiq.us-east-1.rds.amazonaws.com;dbname=kickads_appserver_dev',
					'emulatePrepare'   => true,
					'username'         => 'www-data',
					'password'         => 'k1ck4ds3rv3r',
					'charset'          => 'utf8',
					'initSQLs'         => array(
			           "SET SESSION time_zone = '-3:00'",
					),
				);

				$mailLog = array(
					'class'   =>'CPhpMailerLogRoute',
					'levels'  =>'',
					'subject' =>'',
					'emails'  =>array(),
				);
		break;
	case 'tmp.kickadserver.mobi':
				$mysqlConnect = array(
					'connectionString' => 'mysql:host=kickads-db-3.ccqfyxyzmdiq.us-east-1.rds.amazonaws.com;dbname=kickads_appserver',
					'emulatePrepare'   => true,
					'username'         => 'www-data',
					'password'         => 'k1ck4ds3rv3r',
					'charset'          => 'utf8',
					'initSQLs'         => array(
			           "SET SESSION time_zone = '-3:00'",
					),
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

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'       => dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'           => '',
	'theme'          => 'bootstrap',
	'timeZone'       =>  'America/Argentina/Buenos_Aires',
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
	            'gii'=>'gii',
	            'gii/<controller:\w+>'=>'gii/<controller>',
	            'gii/<controller:\w+>/<action:\w+>'=>'gii/<controller>/<action>',
				'<controller:\w+>/<id:\d+>'                           =>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'              =>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'                       =>'<controller>/<action>',
				// custom parameters //
				'<controller:\w+>/<action:\w+>/<hash:\w+>'  =>'<controller>/<action>',
				//'<controller:\w+>/<action:\w+>/<hash:\w+>/<id:\d+>' =>'<controller>/<action>',
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
		'db'=> $mysqlConnect,
		
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
                $mailLog,
        	),
        ),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'info@kickads.mobi',
	),
);
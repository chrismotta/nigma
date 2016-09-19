<?php
// die('<h3>Currently undergoing maintenance</h3>');

ini_set('memory_limit', '-1');
set_time_limit(0);

// change the following paths if necessary
$yii=dirname(__FILE__).'/../../yii/framework/yii.php';
$config=dirname(__FILE__).'/protected/config/main.php';

// remove the following lines when in production mode
if ( $_SERVER['HTTP_HOST'] == 'localhost' || $_SERVER['HTTP_HOST'] == 'dev.tmlbox.co' ) 
	defined('YII_DEBUG') or define('YII_DEBUG',true);
// specify how many levels of call stack should be shown in each log message
// defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);

require_once(dirname(__FILE__).'/protected/config/localConfig.php');

require_once($yii);
Yii::createWebApplication($config)->run();
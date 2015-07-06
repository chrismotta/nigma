<?php

class ApiUpdateController extends Controller
{

	// /**
	//  * @return array action filters
	//  */
	// public function filters()
	// {
	// 	return array(
	// 		'accessControl', // perform access control for CRUD operations
	// 		'postOnly + delete', // we only allow deletion via POST request
	// 	);
	// }

	// /**
	//  * Specifies the access control rules.
	//  * This method is used by the 'accessControl' filter.
	//  * @return array access control rules
	//  */
	public function accessRules()
	{
		return array(
			array('allow',
				'actions'=>array('index', 'log', 'adWords', 'airpush', 'ajillion', 'buzzCity' , 'leadBolt', 'reporo', 'vServ', 'mobfox', 'eroAdvertising', 'inMobi', 'bingAds', 'adultmoda', 'smaato', 'campaign', 'ajillionPublisher', 'affiliates', 'mobads', 'plugRush', 'jampp'),
				'roles'=>array('admin', 'media_manager'),
			),
			array('allow',
				'actions'=>array('index', 'log', 'adWords', 'airpush', 'ajillion', 'buzzCity' , 'leadBolt', 'reporo', 'vServ', 'mobfox', 'eroAdvertising', 'inMobi', 'bingAds', 'adultmoda', 'smaato', 'campaign', 'ajillionPublisher', 'affiliates', 'mobads', 'plugRush', 'jampp'),
				'ips'=>array('54.172.221.175'),
			),
			array('allow',
				'actions'=>array('oAuthRedirect'),
				'users'=>array('*'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	public function actionIndex()
	{
		// $this->actionAirpush();
		$this->actionEroAdvertising();
		$this->actionAjillion();
		// $this->actionBuzzCity();
		// $this->actionLeadBolt();
		
		// $this->actionReporo(); 
		// reporo disabled (works on localhost only)
		// error:
		/*
		file_get_contents(http://www.reporo.com/analytics/data-api.php?action=inventory/advertiser): failed to open stream: HTTP request failed! HTTP/1.0 401 Unauthorized
		 (/var/www/html/prod/protected/models/api/Reporo.php:127)
		Stack trace:
		#0 /var/www/html/prod/protected/controllers/ApiUpdateController.php(120): Reporo->downloadInfo()
		#1 /var/www/yii/framework/web/actions/CInlineAction.php(49): ApiUpdateController->actionReporo()
		#2 /var/www/yii/framework/web/CController.php(308): CInlineAction->runWithParams()
		#3 /var/www/yii/framework/web/CController.php(286): ApiUpdateController->runAction()
		#4 /var/www/yii/framework/web/CController.php(265): ApiUpdateController->runActionWithFilters()
		#5 /var/www/yii/framework/web/CWebApplication.php(282): ApiUpdateController->run()
		#6 /var/www/yii/framework/web/CWebApplication.php(141): CWebApplication->runController()
		#7 /var/www/yii/framework/base/CApplication.php(184): CWebApplication->processRequest()
		#8 /var/www/html/prod/index.php(13): CWebApplication->run()
		REQUEST_URI=/apiUpdate/reporo
		1 row in set (0.19 sec)
		*/

		// $this->actionInMobi();
		// $this->actionBingAds();
		// $this->actionSmaato();
		// $this->actionAdultmoda();
		// $this->actionMobads();
		// $this->actionPlugRush();
		// $this->actionJampp();
		// $this->actionAffiliates();
		
		$this->actionAjillionPublisher();
	}

	public function actionBingCode(){
		echo $_REQUEST['code'];
	}

	public function actionAdWords()
	{
		try {
			$adWords = new AdWords;
			$adWords->downloadInfo();
		} catch (Exception $e) {
			Yii::log($e->getCode()." ".$e->getMessage(), 'error', 'system.model.api.apiUpdate');
		}
	}

	public function actionAirpush()
	{
		try {
			$airpush = new Airpush;
			$airpush->downloadInfo();
		} catch (Exception $e) {
			Yii::log($e->getCode()." ".$e->getMessage(), 'error', 'system.model.api.apiUpdate');
		}
	}

	public function actionAjillion()
	{
		try {
			$ajillion = new Ajillion;
			$ajillion->downloadInfo();
		} catch (Exception $e) {
			Yii::log($e->getCode()." ".$e->getMessage(), 'error', 'system.model.api.apiUpdate');			
		}
	}

	public function actionBuzzCity()
	{
		try {
			$buzzCity = new BuzzCity;
			$buzzCity->downloadInfo();
		} catch (Exception $e) {
			Yii::log($e->getCode()." ".$e->getMessage(), 'error', 'system.model.api.apiUpdate');
		}
	}

	public function actionLeadBolt()
	{
		try {
			$leadBolt = new LeadBolt;
			$leadBolt->downloadInfo();
		} catch (Exception $e) {
			Yii::log($e->getCode()." ".$e->getMessage(), 'error', 'system.model.api.apiUpdate');
		}
	}

	public function actionReporo()
	{
		try {
			$reporo = new Reporo;
			$reporo->downloadInfo();
		} catch (Exception $e) {
			Yii::log($e->getCode()." ".$e->getMessage(), 'error', 'system.model.api.apiUpdate');
		}
	}

	public function actionVServ()
	{
		try {
			$vServ = new VServ;
			$vServ->downloadInfo();
		} catch (Exception $e) {
			Yii::log($e->getCode()." ".$e->getMessage(), 'error', 'system.model.api.apiUpdate');
		}
	}

	public function actionMobfox()
	{
		try {
			$mobfox = new Mobfox;
			$mobfox->downloadInfo();
		} catch (Exception $e) {
			Yii::log($e->getCode()." ".$e->getMessage(), 'error', 'system.model.api.apiUpdate');
		}
	}

	public function actionEroAdvertising()
	{
		try {
			$eroAdvertising = new EroAdvertising;
			$eroAdvertising->downloadInfo();
		} catch (Exception $e) {
			Yii::log($e->getCode()." ".$e->getMessage(), 'error', 'system.model.api.apiUpdate');
		}
	}

	public function actionInMobi()
	{
		try {
			$inMobi = new InMobi;
			$inMobi->downloadInfo();
		} catch (Exception $e) {
			Yii::log($e->getCode()." ".$e->getMessage(), 'error', 'system.model.api.apiUpdate');
		}
	}

	public function actionBingAds()
	{
		try {
			$bingAds = new BingAds;
			$bingAds->downloadInfo();
		} catch (Exception $e) {
			Yii::log($e->getCode()." ".$e->getMessage(), 'error', 'system.model.api.apiUpdate');
		}
	}

	public function actionSmaato()
	{
		try {
			$smaato = new Smaato;
			$smaato->downloadInfo();	
		} catch (Exception $e) {
			Yii::log($e->getCode()." ".$e->getMessage(), 'error', 'system.model.api.apiUpdate');
		}
	}

	public function actionAdultmoda()
	{
		try {
			$adultmoda = new Adultmoda;
			$adultmoda->downloadInfo();
		} catch (Exception $e) {
			Yii::log($e->getCode()." ".$e->getMessage(), 'error', 'system.model.api.apiUpdate');
		}
	}

	public function actionAjillionPublisher($hash=null)
	{
		try {
			$ajillion = new AjillionPublisher;
			$return = $ajillion->downloadInfo();
			if(isset($hash) && $hash=='echo')
				echo $return;
		} catch (Exception $e) {
			Yii::log($e->getCode()." ".$e->getMessage(), 'error', 'system.model.api.apiUpdate.publisher');			
		}
	}

	public function actionAffiliates()
	{
		try {
			$affiliates = new AffiliatesAPI;
			$affiliates->downloadInfo();
		} catch (Exception $e) {
			Yii::log($e->getCode()." ".$e->getMessage(), 'error', 'system.model.api.apiUpdate.affiliates');			
		}
	}

	public function actionMobads()
	{
		try {
			$mobads = new MobAds;
			$mobads->downloadInfo();
		} catch (Exception $e) {
			Yii::log($e->getCode()." ".$e->getMessage(), 'error', 'system.model.api.apiUpdate.mobads');			
		}
	}

	public function actionPlugRush()
	{
		try {
			$plugRush = new PlugRush;
			$plugRush->downloadInfo();
		} catch (Exception $e) {
			Yii::log($e->getCode()." ".$e->getMessage(), 'error', 'system.model.api.apiUpdate.mobads');			
		}
	}

	public function actionJampp()
	{
		try {
			$Jampp = new Jampp;
			$Jampp->downloadInfo();
		} catch (Exception $e) {
			Yii::log($e->getCode()." ".$e->getMessage(), 'error', 'system.model.api.apiUpdate.mobads');			
		}
	}

	public function actionLog()
	{	
		$fromDate = strtotime('today');
		$network = '%';

		if ( isset( $_GET['date']) )
			$fromDate = strtotime($_GET['date']);
		$endDate = strtotime('+1 day', $fromDate);

		if ( isset( $_GET['network']) )
			$network = $_GET['network'];

		$logs = Log::model()->findAll(array('order'=>'logtime DESC', 'condition'=>'category LIKE "system.model.api.' . $network . '" AND logtime>=' . $fromDate . ' AND logtime<=' . $endDate));
		foreach ($logs as $log) {
			echo '<strong>date: </strong>' . date('d-m-Y', $log->logtime) . ' - <strong>level: </strong>' . $log->level . ' - <strong>category: </strong>' . $log->category . ' - <strong>message: </strong>' . $log->message . '<hr>';
		}

		echo "Log SUCCESS <hr>";
	}

	public function actionCampaign() 
	{
		echo "IMPORTANT - Implemented only for Ajillion <br>";
		$ajillion = new Ajillion;
		$ajillion->downloadInfo();
	}

	public function actionOAuthRedirect(){
		echo "OAuth Autentication Data:<hr/>";
		if(isset($_POST)) echo json_encode($_POST);
	}
}
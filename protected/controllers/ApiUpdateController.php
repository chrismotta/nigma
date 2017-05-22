<?php

class ApiUpdateController extends Controller
{

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		$adminAllowedActions = array(
			'index', 
			'log', 
			'adWords', 
			'adWordsByCsv', 
			'adWordsConversions',
			'airpush', 
			'ajillion', 
			'buzzCity', 
			'leadBolt', 
			'reporo', 
			'vServ', 
			'mobfox', 
			'eroAdvertising', 
			'inMobi', 
			'bingAds', 
			'adultmoda', 
			'smaato', 
			'campaign', 
			'ajillionExchange', 
			'ajillionCompare',
			'smaatoExchange', 
			'inmobiExchange', 
			'affiliates', 
			'mobads', 
			'plugRush', 
			'jampp',
			'startApp',
			'cpmCampaigns',
			'impLog',
			'MobusiCPC',
			);
		
		return array(
			array('allow',
				'actions' => $adminAllowedActions,
				'roles'   => array('admin'),
			),
			array('allow',
				'actions' => array('adWordsConversions'),
				'roles'     => array('account_manager_admin'),
			),
			array('allow',
				'actions' => $adminAllowedActions,
				'ips'     => array(Yii::app()->params['serverIP']),
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

	public function handleErrors ( $code, $message, $file, $line )
	{
		Yii::log($code.' '.$message, 'error', 'system.model.api.apiUpdate');
	}

	public function actionIndex()
	{
		set_error_handler( array( $this, 'handleErrors' ) );

		// $this->actionEroAdvertising();
		// $this->actionBuzzCity();
		// $this->actionLeadBolt();
		// $this->actionBingAds();
		// $this->actionSmaato();
		// $this->actionAdultmoda();
		// $this->actionMobads();
		// $this->actionPlugRush();
		// $this->actionJampp();

		$this->actionAjillion();
		
		// $this->actionAdWords();

		// $this->actionInMobi();
		// $this->actionReporo();
		// $this->actionStartApp();

		// check if apply
		// $this->actionAjillionExchange();
		// $this->actionSmaatoExchange();

		// $this->actionAffiliates();
		// $this->actionCpmCampaigns();

		// actionImpLog runs independently after etl
		//$this->actionImpLog();

		// $this->actionAdWordsConversions();

		// $this->actionAirpush();
		// $this->actionMobusiCPC();

		Yii::app()->cache->flush();
	}


	public function actionAjillionCompare ($hash=null){
		try {
			$ajillion = new Ajillion;
			$return = $ajillion->compareTotals(7);
			if(isset($hash) && $hash=='echo')
				echo $return;
		} catch (Exception $e) {
			Yii::log($e->getCode()." ".$e->getMessage(), 'error', 'system.model.api.apiUpdate');			
		}		
	}

	public function actionBingCode(){
		echo $_REQUEST['code'];
	}

	public function actionAdWords($hash=null)
	{
		try {
			$adWords = new AdWords;
			$return = $adWords->downloadInfo(7);
			if(isset($hash) && $hash=='echo')
				echo $return;
		} catch (Exception $e) {
			Yii::log($e->getCode()." ".$e->getMessage(), 'error', 'system.model.api.apiUpdate');
		}
	}

	public function actionAdWordsConversions($hash=null)
	{
		try {
			$adWords = new AdWords;
			$return = $adWords->uploadConversions();
			if(isset($hash) && $hash=='echo')
				echo $return;
		} catch (Exception $e) {
			Yii::log($e->getCode()." ".$e->getMessage(), 'error', 'system.model.api.apiUpdate');
		}
	}


	public function actionAdWordsByCsv($hash=null)
	{
		if(isset($_POST['AdWords'])){
			$post = $_POST['AdWords'];

			try {
				$adWords = new AdWords;
				$return = $adWords->loadCsv($post['csv']);
				if(isset($hash) && $hash=='echo')
					echo $return;
			} catch (Exception $e) {
				Yii::log($e->getCode()." ".$e->getMessage(), 'error', 'system.model.api.apiUpdate');
			}

		}

		echo '
		<form method="POST">
			<textarea name="AdWords[csv]" ></textarea>
			<input type="submit" />
		</form>
		';
	}

	public function actionAirpush($hash=null)
	{
		try {
			$airpush = new Airpush;
			$return = $airpush->downloadInfo(7);
			if(isset($hash) && $hash=='echo')
				echo $return;
		} catch (Exception $e) {
			Yii::log($e->getCode()." ".$e->getMessage(), 'error', 'system.model.api.apiUpdate');
		}
	}

	public function actionStartApp($hash=null)
	{
		try {
			$startApp = new StartApp;
			$return = $startApp->downloadInfo(7);
			if(isset($hash) && $hash=='echo')
				echo $return;
		} catch (Exception $e) {
			Yii::log($e->getCode()." ".$e->getMessage(), 'error', 'system.model.api.apiUpdate');
		}
	}

	public function actionAjillion($hash=null)
	{
		try {
			$ajillion = new Ajillion;
			$return = $ajillion->downloadInfo(7);
			if(isset($hash) && $hash=='echo')
				echo $return;
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

	public function actionReporo($hash=null)
	{
		try {
			$reporo = new Reporo;
			$return = $reporo->downloadInfo(7);
			if(isset($hash) && $hash=='echo')
				echo $return;
		} catch (Exception $e) {
			Yii::log($e->getCode()." ".$e->getMessage(), 'error', 'system.model.api.apiUpdate');
		}
	}

	public function actionMobusiCPC($hash=null)
	{
		try {
			$mobusi = new MobusiCPC;
			$return = $mobusi->downloadInfo(7);
			if(isset($hash) && $hash=='echo')
				echo $return;
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

	public function actionInMobi($hash=null)
	{
		try {
			$inMobi = new InMobi;
			$return = $inMobi->downloadInfo(7);
			if(isset($hash) && $hash=='echo')
				echo $return;
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

	public function actionAjillionExchange($hash=null)
	{
		try {
			$ajillion = new AjillionExchange;
			$return = $ajillion->downloadInfo(7);
			if(isset($hash) && $hash=='echo')
				echo $return;
		} catch (Exception $e) {
			Yii::log($e->getCode()." ".$e->getMessage(), 'error', 'system.model.api.apiUpdate.publisher');			
		}
	}

	public function actionSmaatoExchange($hash=null)
	{
		try {
			$smaato = new SmaatoExchange;
			$return = $smaato->downloadInfo(7);
			if(isset($hash) && $hash=='echo')
				echo $return;
		} catch (Exception $e) {
			Yii::log($e->getCode()." ".$e->getMessage(), 'error', 'system.model.api.apiUpdate.publisher');			
		}
	}

	public function actionInmobiExchange($hash=null)
	{
		try {
			$inmobi = new InmobiExchange;
			$return = $inmobi->downloadInfo();
			if(isset($hash) && $hash=='echo')
				echo $return;
		} catch (Exception $e) {
			Yii::log($e->getCode()." ".$e->getMessage(), 'error', 'system.model.api.apiUpdate.publisher');			
		}
	}

	public function actionAffiliates($hash=null)
	{
		try {
			$affiliates = new AffiliatesAPI;
			$return = $affiliates->downloadInfo(7);
			if(isset($hash) && $hash=='echo')
				echo $return;
		} catch (Exception $e) {
			Yii::log($e->getCode()." ".$e->getMessage(), 'error', 'system.model.api.apiUpdate.affiliates');			
		}
	}

	public function actionCpmCampaigns($hash=null)
	{
		try {
			$cpm = new CPMCampaignsAPI;
			$return = $cpm->downloadInfo(7);
			if(isset($hash) && $hash=='echo')
				echo $return;
		} catch (Exception $e) {
			Yii::log($e->getCode()." ".$e->getMessage(), 'error', 'system.model.api.apiUpdate.cpmCampaigns');			
		}
	}

	public function actionImpLog($hash=null)
	{
		try {
			$model = new ImpLogAPI;
			$return = $model->downloadInfo(7);
			if(isset($hash) && $hash=='echo')
				echo $return;
		} catch (Exception $e) {
			Yii::log($e->getCode()." ".$e->getMessage(), 'error', 'system.model.api.apiUpdate.impLog');			
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
		// echo "OAuth Autentication Data:<hr/>";
		if(isset($_REQUEST)) echo json_encode($_REQUEST);
	}
}
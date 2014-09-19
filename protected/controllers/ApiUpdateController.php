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
		return array(
			array('allow',
				'actions'=>array('index', 'log'),
				'roles'=>array('admin', 'media_manager'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
			array('allow',
				'actions'=>array('index', 'log'),
				'roles'=>array('*'),
			),
		);
	}

	public function actionIndex()
	{
		$this->actionAirpush();
		$this->actionAjillion();
		$this->actionBuzzCity();
		$this->actionLeadBolt();
		$this->actionReporo();
		$this->actionAdWords();
		$this->actionVServ();
		// $this->actionMobfox();
		$this->actionEroAdvertising();
	}

	public function actionAdWords()
	{
		$adWords = new AdWords;
		$adWords->downloadInfo();
	}

	public function actionAirpush()
	{
		$airpush = new Airpush;
		$airpush->downloadInfo();
	}

	public function actionAjillion()
	{
		$ajillion = new Ajillion;
		$ajillion->downloadInfo();
	}

	public function actionBuzzCity()
	{
		$buzzCity = new BuzzCity;
		$buzzCity->downloadInfo();
	}

	public function actionLeadBolt()
	{
		$leadBolt = new LeadBolt;
		$leadBolt->downloadInfo();
	}

	public function actionReporo()
	{
		$reporo = new Reporo;
		$reporo->downloadInfo();
	}

	public function actionVServ()
	{
		$vServ = new VServ;
		$vServ->downloadInfo();
	}

	public function actionMobfox()
	{
		$mobfox = new Mobfox;
		$mobfox->downloadInfo();
	}

	public function actionEroAdvertising()
	{
		$vServ = new EroAdvertising;
		$vServ->downloadInfo();
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
}
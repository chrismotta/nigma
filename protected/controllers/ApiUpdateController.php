<?php

class ApiUpdateController extends Controller
{

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
		$logs = Log::model()->findAll(array('order'=>'logtime DESC', 'condition'=>'category LIKE "system.model.api%"'));
		foreach ($logs as $log) {
			echo '<strong>date: </strong>' . date('d-m-Y', $log->logtime) . ' - <strong>level: </strong>' . $log->level . ' - <strong>category: </strong>' . $log->category . ' - <strong>message: </strong>' . $log->message . '<hr>';
		}

		echo "Log SUCCESS <hr>";
	}
}
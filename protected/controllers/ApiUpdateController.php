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

}
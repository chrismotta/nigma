<?php

class ClicksLogController extends Controller
{
	public function actionIndex()
	{

		// Get Request
		if( isset( $_GET['cid'] ) && isset( $_GET['nid'] ) ){
			$cid = $_GET['cid'];
			$nid = $_GET['nid'];
			//print "cid: ".$cid." - nid: ".$nid."<hr/>";
		}else{
			//print "cid: null || nid: null<hr/>";
			Yii::app()->end();
		}

		// Get Campaign
		
		if($campaign = Campaigns::model()->findByPk($cid)){
			$redirectURL = $campaign->url;
			$s2s = $campaign->opportunities->server_to_server;
		}else{
			//print "campaign: null<hr/>";
			Yii::app()->end();
		}

		//print_r($campaign);
		//print "url: ".$redirectURL."<hr/>";

		// Write down a log

		$model = new ClicksLog();
		//$model->id = 2;
		$model->campaigns_id = (int)$cid;
		$model->networks_id = (int)$nid;
		//$model->date = 0;

		// Get visitor parameters
		
		$model->server_ip    = isset($_SERVER["REMOTE_ADDR"]) ? $_SERVER["REMOTE_ADDR"] : null;
		$model->ip_forwarded = isset($_SERVER["HTTP_X_FORWARDED_FOR"]) ? $_SERVER["HTTP_X_FORWARDED_FOR"] : null;
		$model->server_name  = isset($_SERVER["SERVER_NAME"]) ? $_SERVER["SERVER_NAME"] : null;
		$model->user_agent   = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : null;
		$model->languaje     = isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? $_SERVER['HTTP_ACCEPT_LANGUAGE'] : null;
		$model->referer      = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null;

		//var_dump($model);
		//print "<hr/>";

		// Save active record and redirect
		
		if($model->save()){

			$ktoken = md5($model->id);
			//print "guardado - tid: ".$ktoken;
			//print "<hr/>";
			$model->tid = $ktoken;
			$model->save();

			// Guardo los datos en cookies (Expira en 1 hora)
			setcookie('sma_tid', $ktoken, time() + 1 * 1 * 60 * 60, '/');

			$redirectURL.= "&".$s2s."=".$ktoken;
			// print $redirectURL;
			// redirect to campaign url
			$this->redirect($redirectURL);
		}else{
			//print "no guardado";
		}

	}


	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
}
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
			$redirectURL          = $campaign->url;
			$s2s                  = $campaign->opportunities->server_to_server;
			if(!isset($s2s)) $s2s = "ktoken";
		}else{
			//print "campaign: null<hr/>";
			Yii::app()->end();
		}

		//print_r($campaign);
		//print "url: ".$redirectURL."<hr/>";

		// Write down a log

		$model = new ClicksLog();
		//$model->id         = 2;
		$model->campaigns_id = (int)$cid;
		$model->networks_id  = (int)$nid;
		//$model->date       = 0;

		// Get visitor parameters
		
		$model->server_ip    = isset($_SERVER["REMOTE_ADDR"]) ? $_SERVER["REMOTE_ADDR"] : null;
		$model->ip_forwarded = isset($_SERVER["HTTP_X_FORWARDED_FOR"]) ? $_SERVER["HTTP_X_FORWARDED_FOR"] : null;
		$model->user_agent   = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : null;
		$model->languaje     = isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? $_SERVER['HTTP_ACCEPT_LANGUAGE'] : null;
		$model->referer      = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null;
		$model->app          = isset($_SERVER['HTTP_X_REQUESTED_WITH']) ? $_SERVER['HTTP_X_REQUESTED_WITH'] : null;

		// Get ip data

		$ip = isset($model->ip_forwarded) ? $model->ip_forwarded : $model->server_ip;
		if(isset($ip)){
			$binPath        = YiiBase::getPathOfAlias('application') . "/data/ip2location.BIN";
			$location       = new IP2Location($binPath, IP2Location::FILE_IO);
			$ipData         = $location->lookup($ip, IP2Location::ALL);
			$model->country = $ipData->countryName;
			$model->city    = $ipData->cityName;
			$model->carrier = $ipData->mobileCarrierName;
		}

		// Get userAgent data
		// .example:
		// Mozilla/5.0 (Linux; Android 4.4.2; GT-I9500 Build/KOT49H) 
		// AppleWebKit/537.36 (KHTML, like Gecko)
		// Chrome/36.0.1985.131 Mobile Safari/537.36
		// .example:
		// Mozilla/5.0 (Linux; U; Android 4.1.1; es-ar; HTC One X Build/JRO03C) 
		// AppleWebKit/534.30 (KHTML, like Gecko) 
		// Version/4.0 Mobile Safari/534.30

		if(isset($model->user_agent)){

			$wurfl = WurflManager::loadWurfl();
			$device = $wurfl->getDeviceForUserAgent($model->user_agent);
			
			$model->device = $device->getCapability('brand_name') . " " . $device->getCapability('marketing_name');
			$model->os     = $device->getCapability('device_os') . " " . $device->getCapability('device_os_version');
		}

		//var_dump($model);
		//print "<hr/>";

		//Yii::app()->end();

		// Save active record and redirect
		
		if($model->save()){

			$ktoken = md5($model->id);

			/*
			// descomentar para habilitar log

			$headers = var_export($_SERVER, true);
			$headers.= var_export($_COOKIE, true);
			$headers.= var_export(apache_response_headers(), true);
			$headers.= var_export(apache_request_headers(), true);
			// genero un log de headers para identificar
			// el origen del click
			$gc_log = fopen( "log/clicks.log", "a");
			fwrite($gc_log, "---------------------------"."\n\r");
			fwrite($gc_log, $ktoken."\n\r");
			fwrite($gc_log, "---------------------------"."\n\r");
			fwrite($gc_log, $headers."\n\r") ? null : fwrite($gc_log, "error"."\n\r");
			fwrite($gc_log, "---------------------------"."\n\r");
			fclose($gc_log);

			 */
			//die($headers);


			//print "guardado - tid: ".$ktoken;
			//print "<hr/>";
			$model->tid = $ktoken;
			$model->save();

			// Guardo los datos en cookies (Expira en 1 hora)
			setcookie('ktoken', $ktoken, time() + 1 * 1 * 60 * 60, '/');

			if( strpos($redirectURL, "?") ){
				$redirectURL.= "&";
			} else {
				$redirectURL.= "?";
			}
			$redirectURL.= $s2s."=".$ktoken;

			//parametros para playtown
			
			if($campaign->post_data){
				$redirectURL.= "&os=".$model->os;
				$redirectURL.= "&device=".$model->device;
				$redirectURL.= "&country=".$model->country;
				$redirectURL.= "&carrier=".$model->carrier;
				$redirectURL.= "&referer=".$model->referer;
				$redirectURL.= "&app=".$model->app;
			}

			echo $redirectURL;
			Yii::app()->end();

			// redirect to campaign url
			$this->redirect($redirectURL);
		}else{
			print "no guardado";
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
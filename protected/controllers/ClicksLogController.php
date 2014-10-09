<?php

class ClicksLogController extends Controller
{

	/**
	 * Record a click stamp and redirect
	 * to the appropriate landing
	 * @return [type] [description]
	 */
	public function actionIndex($id=null)
	{
			
		isset( $_GET['ts'] ) ? $test = true : $test = false;

		$ts['request'] = $_SERVER['REQUEST_TIME'];
		$ts['start'] = microtime(true);

		if(isset($id)){
			$cid = $id;
			$nid = NULL;
		}else{
			// Get Request
			if( isset( $_GET['cid'] ) ){
				$cid = $_GET['cid'];
				//print "cid: ".$cid." - nid: ".$nid."<hr/>";
			}else{
				//print "cid: null || nid: null<hr/>";
				//Yii::app()->end();
				$cid = NULL;
			}
			if( isset( $_GET['nid'] ) ){
				$nid = $_GET['nid'];
				//print "cid: ".$cid." - nid: ".$nid."<hr/>";
			}else{
				$nid = NULL;
			}
		}

		// Get Campaign
		if($cid){
			if($campaign = Campaigns::model()->findByPk($cid)){
				$redirectURL          = $campaign->url;
				if($nid==NULL){
					$nid              = $campaign->networks_id;
				}
				$nid = 4;
				$ts['campaign']       = microtime(true);
				
				$s2s                  = $campaign->opportunities->server_to_server;
				if(!isset($s2s)) $s2s = "ktoken";
				$ts['s2s']            = microtime(true);
			}else{
				//print "campaign: null<hr/>";
				//Yii::app()->end();
				$cid = NULL;
				$nid = NULL;
			}
		}else{

		}

		//print_r($campaign);
		//print "url: ".$redirectURL."<hr/>";

		// Write down a log

		$model = new ClicksLog();
		//$model->id         = 2;
		$model->campaigns_id = $cid;
		$model->networks_id  = $nid;
		//$model->date       = 0;

		// Get visitor parameters
		
		$model->server_ip    = isset($_SERVER["REMOTE_ADDR"]) ? $_SERVER["REMOTE_ADDR"] : null;
		$model->ip_forwarded = isset($_SERVER["HTTP_X_FORWARDED_FOR"]) ? $_SERVER["HTTP_X_FORWARDED_FOR"] : null;
		$model->user_agent   = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : null;
		$model->languaje     = isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? $_SERVER['HTTP_ACCEPT_LANGUAGE'] : null;
		$model->referer      = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null;
		$model->app          = isset($_SERVER['HTTP_X_REQUESTED_WITH']) ? $_SERVER['HTTP_X_REQUESTED_WITH'] : null;
		$model->redirect_url = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : null;

		$ts['model']         = microtime(true);
		
		
		if($test){
		
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

			$ts['ip2location']  = microtime(true);

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

			$ts['wurfl'] = microtime(true);

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
			//setcookie('ktoken', $ktoken, time() + 1 * 1 * 60 * 60, '/');

			if($cid){
				if( strpos($redirectURL, "?") ){
					$redirectURL.= "&";
				} else {
					$redirectURL.= "?";
				}
				$redirectURL.= $s2s."=".$ktoken;
			}

			//parametros para oneclick
/*
			if($campaign->post_data){
				$redirectURL.= "&os=".$model->os;
				$redirectURL.= "&device=".$model->device;
				$redirectURL.= "&country=".$model->country;
				$redirectURL.= "&carrier=".$model->carrier;
				$redirectURL.= "&referer=".$model->referer;
				$redirectURL.= "&app=".$model->app;
			}
*/
			
			
			// testing
			/*
			echo $redirectURL;
			echo "<hr/>";
			echo "time: ". (microtime(true) - $timestampStart);
			//var_dump($_SERVER);
			Yii::app()->end();
			*/
			
			if($cid){
				$ts['redirect'] = microtime(true);

				// redirect to campaign url
				if($test){
					echo json_encode($ts);
				}else{
					//$this->redirect($redirectURL);
					header("Location: ".$redirectURL);
					die();
				}
			}else{
				echo "no redirect";
			}
				
				
		}else{
			print "no guardado";
		}

	}

	public function actionUpdateClicksData() 
	{
		date_default_timezone_set('UTC');
		set_time_limit(1000000);

		$date = date('Y-m-d', strtotime('today'));
		if (isset($_GET['date']))
			$date = $_GET['date'];

		$hourTo = date('H:i', strtotime('now'));
		if (isset($_GET['hourFrom']) && isset($_GET['hourTo'])) {
			$hourFrom = $_GET['hourFrom'];
			$hourTo   = $_GET['hourTo'];
		}

		$tmp           = new DateTime($date . ' ' . $hourTo . ':00');
		$timestampTo   = clone $tmp;
		if ( isset($hourFrom) )
			$timestampFrom = new DateTime($date . ' ' . $hourFrom . ':00');
		else
			$timestampFrom = $tmp->sub(new DateInterval('PT1H' . $timestampTo->format('i') . 'M'));

		$clicks = ClicksLog::model()->findAll( 'date>=:dateFrom AND date<=:dateTo', array(':dateFrom' => $timestampFrom->format('Y-m-d H:i:s'), ':dateTo' => $timestampTo->format('Y-m-d H:i:s')) );

		// initializing tools 
		$wurfl    = WurflManager::loadWurfl();
		$binPath  = YiiBase::getPathOfAlias('application') . "/data/ip2location.BIN";
		$location = new IP2Location($binPath, IP2Location::FILE_IO);
		
		echo 'total: '.count($clicks).'<hr/>';
		$countClicks = 0;
		foreach ($clicks as $click) {

			$countClicks++;
			if ( 
				$click->country         !== NULL && 
				$click->city            !== NULL && 
				$click->carrier         !== NULL && 
				$click->browser         !== NULL && 
				$click->browser_version !== NULL && 
				$click->device_type     !== NULL && 
				$click->os              !== NULL && 
				$click->os_version      !== NULL && 
				$click->device          !== NULL &&
				$click->device_model    !== NULL 
				)
			{
				echo $countClicks . " - " . $click->date . " - " . $click->id . "<br/>";
				continue;
			}

			$ip                     = $click->ip_forwarded != NULL ? $click->ip_forwarded : $click->server_ip;
			$ipData                 = $location->lookup($ip, IP2Location::ALL);
			$click->country         = $ipData->countryCode;
			$click->city            = $ipData->cityName;
			$click->carrier         = $ipData->mobileCarrierName;
			
			$device                 = $wurfl->getDeviceForUserAgent($click->user_agent);

			$click->device          = $device->getCapability('brand_name');
			$click->device_model    = $device->getCapability('marketing_name');
			$click->os              = $device->getCapability('device_os');
			$click->os_version      = $device->getCapability('device_os_version');
			$click->browser         = $device->getVirtualCapability('advertised_browser');
			$click->browser_version = $device->getVirtualCapability('advertised_browser_version');
			
			$click->device          === NULL ? $click->device = "" : null;
			$click->device_model    === NULL ? $click->device_model = "" : null;
			$click->os              === NULL ? $click->os = "" : null;
			$click->os_version      === NULL ? $click->os_version = "" : null;
			$click->browser         === NULL ? $click->browser = "" : null;
			$click->browser_version === NULL ? $click->browser_version = "" : null;

			if ($device->getCapability('is_tablet') == 'true')
				$click->device_type = 'Tablet';
			else if ($device->getCapability('is_wireless_device') == 'true')
				$click->device_type = 'Mobile';
			else
				$click->device_type = 'Desktop';

			$click->save();
			echo $countClicks . " - " . $click->date . " - " . $click->id . " - updated<br/>";
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
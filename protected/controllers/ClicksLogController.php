<?php

class ClicksLogController extends Controller
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
				'actions'=>array('index', 'tracking', 'vector'),
				'users'=>array('*'),
			),
			array('allow',
				'actions'=>array('updateClicksData', 'updateQuery', 'storage'),
				'ips'=>array('54.88.85.63'),
			),
			array('allow', 
				'actions'=>array('updateClicksData', 'updateQuery', 'storage', 'test'),
				'roles'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Record a click stamp and redirect
	 * to the appropriate landing
	 * @return [type] [description]
	 */
	public function actionTracking($id=null)
	{
		$this->actionIndex($id);
	}

	public function actionTest()
	{

		if (isset($_GET["user_agent"])) {
			$user_agent = $_GET["user_agent"];
			echo $user_agent  . "<hr/>";
		} else {
			die ("user_agent missing");
		}

		$wurfl  = WurflManager::loadWurfl();
		$device = $wurfl->getDeviceForUserAgent($user_agent);
		echo "brand_name = " . $device->getCapability('brand_name') . "<br/>";
		echo "marketing_name = " . $device->getCapability('marketing_name') . "<br/>";
		echo "device_os = " . $device->getCapability('device_os') . "<br/>";
		echo "device_os_version = " . $device->getCapability('device_os_version') . "<br/>";
		echo "advertised_browser = " . $device->getVirtualCapability('advertised_browser') . "<br/>";
		echo "advertised_browser_version =" . $device->getVirtualCapability('advertised_browser_version') . "<br/>";
	}
	
	public function actionIndex($id=null)
	{
			
		isset( $_GET['ts'] ) ? $test = true : $test = false;

		$ts['request'] = $_SERVER['REQUEST_TIME'];
		$ts['start'] = microtime(true);

		// if is new format
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

		// levanto ntoken
		$ntoken = isset($_GET['ntoken']) ? $_GET['ntoken'] : null;

		// Get Campaign
		if($cid){
			if($campaign = Campaigns::model()->findByPk($cid)){
				$redirectURL          = $campaign->url;
				if($nid==NULL){
					$nid              = $campaign->providers_id;
				}
				$ts['campaign']       = microtime(true);
				
				$s2s = $campaign->opportunities->server_to_server ? $campaign->opportunities->server_to_server : NULL;
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
		$model->providers_id = $nid;
		//$model->date       = 0;

		// Get custom parameters
		
		if ( Providers::model()->findByPk($nid)->has_s2s ) {
			foreach ($_GET as $key => $value) {
				$ignore_params = array('g_net', 'g_key', 'g_cre', 'g_pla', 'g_mty', 'ntoken', 'nid', 'cid', 'ts', 'id');
				if ( !in_array($key, $ignore_params) ) {
					$model->custom_params != NULL ? $model->custom_params .= '&' : NULL ;
					$model->custom_params .= $key . '=' . $value;
				}
			}
		}

		// Get visitor parameters
		
		$model->server_ip    = isset($_SERVER["REMOTE_ADDR"]) ? $_SERVER["REMOTE_ADDR"] : null;
		$model->ip_forwarded = isset($_SERVER["HTTP_X_FORWARDED_FOR"]) ? $_SERVER["HTTP_X_FORWARDED_FOR"] : null;
		$model->user_agent   = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : null;
		$model->languaje     = isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? $_SERVER['HTTP_ACCEPT_LANGUAGE'] : null;
		$model->referer      = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null;
		$model->app          = isset($_SERVER['HTTP_X_REQUESTED_WITH']) ? $_SERVER['HTTP_X_REQUESTED_WITH'] : null;
		$model->redirect_url = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : null;

		// get macros if exists
 
		$model->network_type = isset($_GET["g_net"]) ? $_GET["g_net"] : null;
		$model->keyword      = isset($_GET["g_key"]) ? $_GET["g_key"] : null;
		$model->creative     = isset($_GET["g_cre"]) ? $_GET["g_cre"] : null;
		$model->placement    = isset($_GET["g_pla"]) ? $_GET["g_pla"] : null;
		$model->match_type   = isset($_GET["g_mty"]) ? $_GET["g_mty"] : null;

		// get query if exists

		$tmp = array();
		if (preg_match('/q=[^\&]*/', $model->referer, $tmp)) {
			$model->query = urldecode(substr($tmp[0], 2));
		}


		$ts['model']         = microtime(true);
		
		
		// if($test || $campaign->post_data == '1'){
		
			// Get ip data

			$ip = isset($model->ip_forwarded) ? $model->ip_forwarded : $model->server_ip;
			if(isset($ip)){
				$binPath        = YiiBase::getPathOfAlias('application') . "/data/ip2location.BIN";
				$location       = new IP2Location($binPath, IP2Location::FILE_IO);
				$ipData         = $location->lookup($ip, IP2Location::ALL);
				//$model->country = $ipData->countryName;
				$model->country = $ipData->countryCode;
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
				$model->device          = $device->getCapability('brand_name');
				$model->device_model    = $device->getCapability('marketing_name');
				$model->os              = $device->getCapability('device_os');
				$model->os_version      = $device->getCapability('device_os_version');
				$model->browser         = $device->getVirtualCapability('advertised_browser');
				$model->browser_version = $device->getVirtualCapability('advertised_browser_version');

			}

			$ts['wurfl'] = microtime(true);

		// }
		

		//var_dump($model);
		//print "<hr/>";

		//Yii::app()->end();

		// Save active record and redirect
		
		if($model->save()){

			if($ntoken){
				$tmltoken = $ntoken;
			}else{
				$',
 = md5($model->id);
			}
				

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
			fwrite($gc_log, $',
."\n\r");
			fwrite($gc_log, "---------------------------"."\n\r");
			fwrite($gc_log, $headers."\n\r") ? null : fwrite($gc_log, "error"."\n\r");
			fwrite($gc_log, "---------------------------"."\n\r");
			fclose($gc_log);

			 */
			//die($headers);


			//print "guardado - tid: ".$tmltoken;
			//print "<hr/>";
			$model->tid = $tmltoken;
			$model->save();

			// Guardo los datos en cookies (Expira en 1 hora)
			//setcookie('tmltoken', $tmltoken, time() + 1 * 1 * 60 * 60, '/');

			if($cid){
				if($s2s){
					if( strpos($redirectURL, "?") ){
						$redirectURL.= "&";
					} else {
						$redirectURL.= "?";
					}
					$redirectURL.= $s2s."=".$tmltoken;
				}
			}

			//enviar macros
			if($model->haveMacro($redirectURL))
				$redirectURL = $model->replaceMacro($redirectURL);
			
			if($campaign->post_data == '1'){

				$dataQuery['os']      = $model->os."-".$model->os_version;
				$dataQuery['device']  = $model->device."-".$model->device_model;
				$dataQuery['country'] = $model->country;
				$dataQuery['carrier'] = $model->carrier;
				$dataQuery['referer'] = $model->referer;
				$dataQuery['app']     = $model->app;
				$dataQuery['keyword'] = $model->keyword;

				$redirectURL.= '&'.http_build_query($dataQuery, null, '&', PHP_QUERY_RFC3986);
			}
			
			
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
					die($redirectURL);
				}else{
					//$this->redirect($redirectURL);
					header("Location: ".$redirectURL);
				}
			}else{
				logError("no redirect");
			}
				
				
		}else{
			logError("no guardado");
		}

	}


	public function actionVector($id=null)
	{
		$vhc    = VectorsHasCampaigns::model()->findAll('vectors_id=:vid', array(':vid'=>$id));
		$count  = count($vhc);
		$random = mt_rand(0, $count - 1);
		$this->actionIndex($vhc[$random]->campaigns_id);
	}


	/**
	 * Update columns for every register in clicks_log from date = $_GET['hourFrom'],
	 * between $_GET['hourFrom'] and $_GET['hourTo'].
	 * 
	 * If $_GET['hourFrom'] and $_GET['hourTo'] are not provided update last from last hour o'clock
	 * to current time.
	 */
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

		$criteria=new CDbCriteria;
		$criteria->compare('date', '>=' . $timestampFrom->format('Y-m-d H:i:s'));
		$criteria->compare('date', '<=' . $timestampTo->format('Y-m-d H:i:s'));
		$dataProvider = new CActiveDataProvider("ClicksLog", array(
			'criteria' => $criteria,
			'pagination' => array(
                'pageSize' => 100,
            ),
		));
		$iterator = new CDataProviderIterator($dataProvider);

		// initializing tools 
		$wurfl    = WurflManager::loadWurfl();
		$binPath  = YiiBase::getPathOfAlias('application') . "/data/ip2location.BIN";
		$location = new IP2Location($binPath, IP2Location::FILE_IO);
		
		echo 'total: '.count($iterator).'<hr/>';
		$timeBegin = time();
		$countClicks = 0;
		foreach ($iterator as $click) {

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

			$tmp = array();
			if (preg_match('/q=[^\&]*/', $click->referer, $tmp)) {
				$click->query = urldecode(substr($tmp[0], 2));
			}
			
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
		echo "Execution time: " . (time() - $timeBegin) . " seg <br>";

	}

	public function actionUpdateQuery() 
	{
		set_time_limit(1000000);

		if (isset($_GET['useUTC'])) {
			date_default_timezone_set('UTC');
		}

		if (isset($_GET['date']) && isset($_GET['hourFrom']) && isset($_GET['hourTo'])) {
			$date     = $_GET['date'];
			$hourFrom = $_GET['hourFrom'];
			$hourTo   = $_GET['hourTo'];
		} else {
			echo "Missing parameters: date, hourFrom, hourTo";
			return;
		}

		$timestampFrom = new DateTime($date . ' ' . $hourFrom . ':00');
		$timestampTo   = new DateTime($date . ' ' . $hourTo . ':00');

		$criteria=new CDbCriteria;
		$criteria->compare('date', '>=' . $timestampFrom->format('Y-m-d H:i:s'));
		$criteria->compare('date', '<=' . $timestampTo->format('Y-m-d H:i:s'));
		$criteria->compare('providers_id', '4');
		// $criteria->addCondition('query IS NULL');
		$dataProvider = new CActiveDataProvider("ClicksLog", array(
			'criteria'   => $criteria,
			'pagination' => array(
                'pageSize' => 100,
            ),
		));
		$iterator = new CDataProviderIterator($dataProvider);

		echo 'total: '.count($iterator).'<hr/>';
		$timeBegin = time();
		$countClicks = 0;
		foreach ($iterator as $click) {
			$countClicks++;

			if ( $click->query !== NULL ) {
				echo $countClicks . " - " . $click->date . " - " . $click->id . " - " . $click->query . "<br/>";
				continue;
			}

			$tmp = array();
			if (preg_match('/q=[^\&]*/', $click->referer, $tmp)) {
				$click->query = urldecode(substr($tmp[0], 2));
			}
			
			$click->save();
			echo $countClicks . " - " . $click->date . " - " . $click->id . " - " . $click->query . " - updated<br/>";
		}
		echo "Execution time: " . (time() - $timeBegin) . " seg <br>";

	}

	/**
	 * Store clicks_log data from 1 month ago in clicks_log_storage_2
	 * @return string Transaction status
	 */
	public function actionStorage()
	{
		$copyRows     = 'INSERT INTO clicks_log_storage_2 
						SELECT * FROM clicks_log WHERE id < (
							SELECT id FROM clicks_log 
							WHERE DATE(date) = DATE(DATE_SUB(NOW(), INTERVAL 1 MONTH)) 
							ORDER BY id ASC 
							LIMIT 0,1
							) 
						ORDER BY id 
						ASC LIMIT 0,300000';

		$deleteRows	 = 'DELETE FROM clicks_log 
						WHERE id <= (
							SELECT id FROM clicks_log_storage_2 
							ORDER BY id DESC 
							LIMIT 0,1
							) 
						ORDER BY id ASC';
		
		$result['action'] = 'ClicksLogStorage';
		$command = Yii::app()->db->createCommand($copyRows);
		$result['inserted'] = $command->execute();
		$command = Yii::app()->db->createCommand($deleteRows);
		$result['deleted'] = $command->execute();
		echo json_encode($result);
	}

	public function logError($msg){
		
		Yii::log( $msg . "<hr/>\n ERROR: " . json_encode($model->getErrors()), 'error', 'system.model.clicksLog');

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
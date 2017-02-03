<?php

class TagController extends Controller
{
	public function actionTestIP($hash=null){
		$ip    = isset($hash) ? $hash : $_SERVER["REMOTE_ADDR"];
		echo 'IP: '.$ip;
		echo '<hr/>';

		$binPath      = Yii::app()->params['ipDbFile'];
		$location     = new IP2Location($binPath, IP2Location::FILE_IO);
		$ipData       = $location->lookup($ip, IP2Location::ALL);
		echo json_encode($ipData, JSON_PRETTY_PRINT);
		echo '<hr/>';
	}

	public function actionView($id){
		// Yii::log("impresion: " . var_export($imp->getErrors(), true));

		if(!$tag = Tags::model()->findByPk($id))
			die("Tag ID does't exists");
		if(!isset($_GET['pid']))
			die("Placement ID does't exists");

		
		// log impression
		
		$imp = new ImpLog();
		$imp->tags_id = $tag->id;
		$imp->placements_id = $_GET['pid'];
		$imp->date = new CDbExpression('NOW()');


		// pubid
		
		$imp->pubid = isset($_GET['pubid']) ? $_GET['pubid'] : null;

		
		// Get visitor parameters
		
		$imp->server_ip    = isset($_SERVER["REMOTE_ADDR"]) ? $_SERVER["REMOTE_ADDR"] : null;
		$imp->ip_forwarded = isset($_SERVER["HTTP_X_FORWARDED_FOR"]) ? $_SERVER["HTTP_X_FORWARDED_FOR"] : null;
		$imp->user_agent   = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : null;
		// $imp->languaje     = isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? $_SERVER['HTTP_ACCEPT_LANGUAGE'] : null;
		// $imp->referer      = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null;
		// $imp->app          = isset($_SERVER['HTTP_X_REQUESTED_WITH']) ? $_SERVER['HTTP_X_REQUESTED_WITH'] : null;


		// Get userAgent data
		
		/* performance upgrade
		if(isset($imp->user_agent)){

			$wurfl = WurflManager::loadWurfl();
			$device = $wurfl->getDeviceForUserAgent($imp->user_agent);
			$imp->device          = $device->getCapability('brand_name');
			$imp->device_model    = $device->getCapability('marketing_name');
			$imp->os              = $device->getCapability('device_os');
			$imp->os_version      = $device->getCapability('device_os_version');
			$imp->browser         = $device->getVirtualCapability('advertised_browser');
			$imp->browser_version = $device->getVirtualCapability('advertised_browser_version');
			
			if ($device->getCapability('is_tablet') == 'true')
				$imp->device_type = 'Tablet';
			else if ($device->getCapability('is_wireless_device') == 'true')
				$imp->device_type = 'Mobile';
			else
				$imp->device_type = 'Desktop';

		}
		*/

		// Get ip data

		/* performance upgrade
		$ip = isset($imp->ip_forwarded) ? $imp->ip_forwarded : $imp->server_ip;
		if(isset($ip)){
			// $binPath      = YiiBase::getPathOfAlias('application') . "/data/ip2location.BIN";
			$binPath      = Yii::app()->params['ipDbFile'];
			$location     = new IP2Location($binPath, IP2Location::FILE_IO);
			$ipData       = $location->lookup($ip, IP2Location::ALL);
			$imp->country = $ipData->countryCode;
			$imp->city    = $ipData->cityName;
			$imp->carrier = $ipData->mobileCarrierName;
		}
		*/

		/*
		// revenue and cost
		
		// targeting
		$match_country = isset($imp->tags->country) ? $imp->country == $imp->tags->country : true;
		$conn_type = $imp->carrier=='-' || $imp->carrier=='' || $imp->carrier=='Invalid IPv4 address.' || $imp->carrier=='Invalid IPv6 address.' ? 'WIFI' : '3G';
		$match_connection = isset($imp->tags->connection_type) ? $conn_type == $imp->tags->connection_type : true;
		$match_device = isset($imp->tags->device_type) ? $imp->device_type == $imp->tags->device_type || ($imp->device_type != 'Desktop' && $imp->tags->device_type == 'Mobile+Tablet') : true;
		$match_os = isset($imp->tags->os) ? $imp->os == $imp->tags->os : true;
		$match_version = isset($imp->tags->os_version) ? $imp->os_version >= $imp->tags->os_version : true;

		if( $match_country && $match_connection && $match_device && $match_os && $match_version ){
			
			// frequency
			$frequency = $imp->getFrequency();
				
			if($frequency < $imp->tags->freq_cap || $imp->tags->freq_cap == null){
				$imp->revenue = $imp->tags->campaigns->opportunities->rate > 0 ? $imp->tags->campaigns->opportunities->rate /1000 : 0;
				$imp->cost    = $imp->placements->rate > 0 ? $imp->placements->rate /1000 : 0;
			}

		}
		*/

		// log impression

		if(!$imp->save())
			Yii::log("impression error: " . json_encode($imp->getErrors(), true), 'error', 'system.model.impLog');
		// enviar macros

		$newCode = $imp->replaceMacro($tag->code);

		//print tag

		$this->renderPartial('view',array(
			'code'=>$newCode,
			'tag'=>$tag,
			'imp'=>$imp,
			));

	}

	public static function isSecure(){
		if(isset($_SERVER['HTTPS']))
			$sec = true;
		else
			$sec = false;
		return $sec;
	}

	public static function protocol(){
		if (self::isSecure()) 
			$prot = 'https';
		else 
			$prot = 'http';
		return $prot;
	}

	public function actionJs($id){

		if (self::isSecure()) 
			$prot = 'https';
		else 
			$prot = 'http';
		
		$pid    = isset($_GET['pid']) ? $_GET['pid'] : null;
		$width  = isset($_GET['width']) ? $_GET['width'] : null;
		$height = isset($_GET['height']) ? $_GET['height'] : null;
		$pubid  = isset($_GET['pubid']) ? $_GET['pubid'] : '';

		if(isset($pid) && isset($width) && isset($height)){

			echo 'document.write(\'<iframe src="'.self::protocol().'://bidbox.co/tag/'.$id.'?pid='.$pid.'&pubid='.$pubid.'" width="'.$width.'" height="'.$height.'" frameborder="0" scrolling="no" ></iframe>\');';

		}else{

			echo 'document.write(\'ERROR: Ad not setted properly\');';

		}

	}

	public function actionJsp($id){

		$pid    = isset($_GET['pid']) ? $_GET['pid'] : null;
		$width  = isset($_GET['width']) ? $_GET['width'] : null;
		$height = isset($_GET['height']) ? $_GET['height'] : null;
		$pubid  = isset($_GET['pubid']) ? $_GET['pubid'] : '';

		if(isset($pid) && isset($width) && isset($height)){

			echo 'document.write(\'<img src="http://'.self::protocol().'.co/creatives/'.$width.'x'.$height.'.png" width="'.$width.'" height="'.$height.'" frameborder="0" scrolling="no" ></img><script>window.open("http://bidbox.co/tag/url/'.$id.'?pid='.$pid.'&pubid='.$pubid.'", "ad", "status=0,toolbar=0,menubar=0,directories=0");</script>\');';

		}else{

			echo 'document.write(\'ERROR: Ad not setted properly\');';

		}

	}

	public function actionJsi($id){
		
		$pid    = isset($_GET['pid']) ? $_GET['pid'] : null;
		$width  = isset($_GET['width']) ? $_GET['width'] : null;
		$height = isset($_GET['height']) ? $_GET['height'] : null;
		$pubid  = isset($_GET['pubid']) ? $_GET['pubid'] : '';

		if(isset($pid)){

			echo 'document.write(\'<script>window.location="'.self::protocol().'://bidbox.co/tag/url/'.$id.'?pid='.$pid.'&pubid='.$pubid.'";</script>\');';

		}else{

			echo 'document.write(\'ERROR: Ad not setted properly\');';

		}

	}


	public function actionUrlt ( $id )
	{
		$this->actionUrl($id, true);
	}


	public function actionUrlp ( $id )
	{
		$this->actionUrl($id, false, 'resize');
	}


	public function actionRenderurl ( $id )
	{
		$this->actionUrl($id, false, 'iframe');
	}
	
	public function actionRendersandbox ( $id )
	{
		$this->actionUrl($id, false, 'sandbox');
	}

	public function actionRenderjs ( $id )
	{
		$this->actionUrl($id, false, 'js');
	}	

	public function actionSandbox ()
	{
		$pixel = new SandboxStatus();
		$pixel->status = 'server_render';
		$pixel->save();		

		$pixel->request_hash = md5($pixel->id);
		$pixel->save();		

		$this->renderPartial('sandbox', array('pixel_id'=>$pixel->request_hash) );
	}

	public function actionPixel ($hash){
		$path = './themes/tml/img/pixel.gif';
		$content = file_get_contents($path);

		$i = array();
		$i = getimagesize($path, $i);
		header('Content-Type: '.$i['mime']);

		if ( !$hash )			
		{
			echo $content;
			return false;
		} 

		$pixel = new SandboxStatus();
		
		if ( $hash )
			$pixel->request_hash = $hash;

		if(isset( $_GET['status'] ))
			$pixel->status = $_GET['status'];

		if(isset( $_GET['description'] ))
			$pixel->description = $_GET['description'];		

		$pixel->save();

		if ( !$hash )
		{
			$pixel->request_hash = md5($pixel->id);
			$pixel->save();	
		}

		echo $content;
	}

	public function actionUrl($id, $urlTest = false, $render = ''){
		// $start = microtime();

		// detecting if is postback click
		if(isset($_GET['tmltoken'])){
			$tmltoken = $_GET['tmltoken'];
			$imp = ImpLog::model()->findByAttributes(array('tid'=>$tmltoken));
			// var_dump($imp);
			//die('<hr>End');
		}

		if(!$tag = Tags::model()->findByPk($id))
			die("Tag ID does't exists");

		if(!isset($imp)){

			// if(!isset($_GET['pid']))
			// 	die("Placement ID does't exists");
		
			// log impression
			$imp = new ImpLog();
			$imp->tags_id = $tag->id;
			$imp->placements_id = isset($_GET['pid']) ? $_GET['pid'] : null;
		
			// pubid
			$imp->pubid = isset($_GET['pubid']) ? $_GET['pubid'] : null;
			
			// Get visitor parameters
			
			$imp->server_ip    = isset($_SERVER["REMOTE_ADDR"]) ? $_SERVER["REMOTE_ADDR"] : null;
			$imp->ip_forwarded = isset($_SERVER["HTTP_X_FORWARDED_FOR"]) ? $_SERVER["HTTP_X_FORWARDED_FOR"] : null;
			$imp->user_agent   = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : null;
		}

		$imp->date = new CDbExpression('NOW()');


		// log impression
		if(!$imp->save())
			Yii::log("impression error: " . json_encode($imp->getErrors(), true), 'error', 'system.model.impLog');

		// if is new

		if(!isset($imp->tid)){
			// write transaction id
			$imp->tid = md5($imp->id);
			if(!$imp->save())
				Yii::log("impression error: " . json_encode($imp->getErrors(), true), 'error', 'system.model.impLog');
		}

		
		// $end = microtime();
		// $elapsed = $end - $start;
		// echo 'Elapsed time: '.$elapsed.' sec.';

		// send macros
		$newUrl = $imp->replaceMacro( $tag->url );
		
		if ( $urlTest )
		{	
			$handler = curl_init( $newUrl );
			curl_setopt ( $handler, CURLOPT_URL, $newUrl );
			curl_setopt ( $handler, CURLOPT_RETURNTRANSFER, true );
			curl_setopt ( $handler, CURLOPT_VERBOSE, 1 );
			curl_setopt ( $handler, CURLOPT_HEADER, 1 );
			$response = curl_exec ( $handler );

			$status = curl_getinfo($handler, CURLINFO_HTTP_CODE);		
			$msg = curl_error($handler);
			curl_close( $handler );


			$ref = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';

			$query = "INSERT INTO passback_status( url, code, ref, msg ) VALUES ( '".$newUrl."', ".$status.", '".$ref."', '".$msg."' );";

			$result = Yii::app()->db->createCommand($query)->execute();
		}
		// die($newUrl);
		
		switch ($render) {
			case 'resize':
				$this->renderPartial('pop',array(
					'url'=>$newUrl,
				));
				break;

			case 'iframe':
				$this->renderPartial('frame',array(
					'url'=>$newUrl,
				));
				break;

			case 'sandbox':
				$this->renderPartial('frame',array(
					'url'=>'http://localhost/nigma/tag/sandbox',
				));
				break;

			case 'js':
				$pixel = new SandboxStatus();
				$pixel->status = 'server_render';
				$pixel->save();		

				$pixel->request_hash = md5($pixel->id);
				$pixel->save();		

				$this->renderPartial('js',array(
					'url'				=> $newUrl,
					'status_pixel_url'	=> 'http://localhost/nigma/tag/pixel/'.$pixel->request_hash
				));
				break;				
			
			default:
				// redirect to tag url
				header("Location: ".$newUrl);
				break;
		}
	}


	/* DEPRECATED

	public static function getUnfilledImp(){

		return ImpLog::model()->findByAttributes(array(
			'country'=>null,
			'os'=>null
			));
		
	}

	public function actionFillData(){

		while($imp = self::getUnfilledImp()){


		// Get userAgent data
		
		if(isset($imp->user_agent)){

			$wurfl = WurflManager::loadWurfl();
			$device = $wurfl->getDeviceForUserAgent($imp->user_agent);
			$imp->device          = $device->getCapability('brand_name');
			$imp->device_model    = $device->getCapability('marketing_name');
			$imp->os              = $device->getCapability('device_os');
			$imp->os_version      = $device->getCapability('device_os_version');
			$imp->browser         = $device->getVirtualCapability('advertised_browser');
			$imp->browser_version = $device->getVirtualCapability('advertised_browser_version');
			
			if ($device->getCapability('is_tablet') == 'true')
				$imp->device_type = 'Tablet';
			else if ($device->getCapability('is_wireless_device') == 'true')
				$imp->device_type = 'Mobile';
			else
				$imp->device_type = 'Desktop';

		}

		// Get ip data

		$ip = isset($imp->ip_forwarded) ? $imp->ip_forwarded : $imp->server_ip;
		if(isset($ip)){
			// $binPath      = YiiBase::getPathOfAlias('application') . "/data/ip2location.BIN";
			$binPath      = Yii::app()->params['ipDbFile'];
			$location     = new IP2Location($binPath, IP2Location::FILE_IO);
			$ipData       = $location->lookup($ip, IP2Location::ALL);
			$imp->country = $ipData->countryCode;
			$imp->city    = $ipData->cityName;
			$imp->carrier = $ipData->mobileCarrierName;
		}

		$saved = $imp->save();

		echo $imp->id;
		if($saved) echo ' saved!';
		echo '<br/>';

		}
	}
	*/
}

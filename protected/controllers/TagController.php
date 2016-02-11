<?php

class TagController extends Controller
{
	public function actionView($id){

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
		$imp->languaje     = isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? $_SERVER['HTTP_ACCEPT_LANGUAGE'] : null;
		$imp->referer      = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null;
		$imp->app          = isset($_SERVER['HTTP_X_REQUESTED_WITH']) ? $_SERVER['HTTP_X_REQUESTED_WITH'] : null;


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

		}

		// Get ip data

		$ip = isset($imp->ip_forwarded) ? $imp->ip_forwarded : $imp->server_ip;
		if(isset($ip)){
			$binPath        = YiiBase::getPathOfAlias('application') . "/data/ip2location.BIN";
			$location       = new IP2Location($binPath, IP2Location::FILE_IO);
			$ipData         = $location->lookup($ip, IP2Location::ALL);
			$imp->country = $ipData->countryCode;
			$imp->city    = $ipData->cityName;
			$imp->carrier = $ipData->mobileCarrierName;
		}




		$imp->save();

		//print tag
		$this->renderPartial('view',array(
			'model'=>$tag,
			'pid'=>$imp->placements_id,
			'pubid'=>$imp->pubid,
			));

	}
}

<?php

class InMobi
{ 

	private $provider_id = 295;
	private $apiLog;

	public function downloadInfo($offset)
	{

		date_default_timezone_set('UTC');
		$return = '';

		if ( isset( $_GET['date']) ) {
			
			// specific date
			
			$date = $_GET['date'];
			$this->apiLog = ApiLog::initLog($date, $this->provider_id, null);
			$return.= $this->downloadDateInfo($date);
		
		} else {

			if(date('G')<=$offset){
				$return.= '<hr/>yesterday<hr/>';
				$date = date('Y-m-d', strtotime('yesterday'));
				$this->apiLog = ApiLog::initLog($date, $this->provider_id, null);
				$return.= $this->downloadDateInfo($date);
			}

			//default
			$return.= '<hr/>today<hr/>';
			$date = date('Y-m-d', strtotime('today'));
			$this->apiLog = ApiLog::initLog($date, $this->provider_id, null);
			$return.= $this->downloadDateInfo($date);
		
		}
		

		return $return;
	}

	public function downloadDateInfo($date)
	{

		$return = "";

		// validate if info have't been dowloaded already.
		if ( DailyReport::model()->exists("providers_id=:provider AND DATE(date)=:date", array(":provider"=>$this->provider_id, ":date"=>$date)) ) {
			Yii::log("Information already downloaded.", 'warning', 'system.model.api.inmobi');
			return 2;
		}

		// Get json from InMobi API.
		$network = Providers::model()->findbyPk($this->provider_id);
		$user    = $network->token1;
		$pass    = $network->token2;
		$apikey  = $network->token3;
		$apiurl  = $network->url;

		// Create Session
		$ch = curl_init() or die(Yii::log("Fallo cURL session init: ".curl_error(), 'error', 'system.model.api.inmobi')); 
		curl_setopt($ch, CURLOPT_URL,"https://api.inmobi.com/v1.0/generatesession/generate");
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER,array ('secretKey:'.$apikey,'userName:'.$user,'password:'.$pass));
		$response = curl_exec($ch) or die(Yii::log("Fallo cURL session init: ".curl_error(), 'error', 'system.model.api.inmobi'));

		// Json to array
		$newresponse = json_decode($response); 
		$sessionId   = $newresponse->respList[0]->sessionId; // guardo  el id de la session
		$accountId   = $newresponse->respList[0]->accountId; // guardo  el id de la cuenta

		curl_close($ch);

		// get data Json
		$getReportJson = '{"reportRequest": 
								{
									"timeFrame":"'.$date.':'.$date.'",
									"groupBy":["campaign"],
								}

							 }'; // fields filter
		$ch = curl_init() or die(Yii::log("Fallo cURL session init: ".curl_error(), 'error', 'system.model.api.inmobi')); 
		curl_setopt($ch, CURLOPT_URL,$apiurl);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		//curl_setopt($ch, CURLOPT_TIMEOUT, 300);
		curl_setopt($ch, CURLOPT_HTTPHEADER, Array("accountId:$accountId","secretKey:$apikey","sessionId:$sessionId","Content-Type:application/json"));
		curl_setopt($ch, CURLOPT_POST,true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $getReportJson);
		$response = curl_exec($ch) or die(Yii::log("Fallo cURL session init: ".curl_error(), 'error', 'system.model.api.inmobi'));
		$newresponse = json_decode($response);
		if (!$newresponse) {
			Yii::log("InMobi: ERROR - decoding json. ".curl_error(), 'error', 'system.model.api.inmobi');
			return 1;
		}
		curl_close($ch);

		if($newresponse->error=='true')
		{
			if($newresponse->errorList[0]->code==5001){
				Yii::log("Empty daily report ",'info', 'system.model.api.inmobi');
				return 'ERROR 5001';
			}
			else {
				Yii::log($newresponse->errorList[0]->message,'error', 'system.model.api.inmobi');
				return 'ERROR';
			}
		}

		if ( !isset($newresponse->respList) ) { // validation add after initial implementation
			Yii::log("Empty daily report ",'info', 'system.model.api.inmobi');
			return 'ERROR: Empty daily report';
		}

		// Save campaigns information 
		foreach ($newresponse->respList as $campaign) {

			if ( $campaign->impressions == 0 && $campaign->clicks == 0) { // if no impressions dismiss campaign
				continue;
			}

			// get campaign ID used in Server, from the campaign name use in the external provider
			$campaigns_id = Utilities::parseCampaignID($campaign->campaignName);
			
			// if exists overwrite, else create a new
			$dailyReport = DailyReport::model()->find(
				"providers_id=:providers AND DATE(date)=:date AND campaigns_id=:cid", 
				array(
					":providers"=>$this->provider_id, 
					":date"=>$date, 
					":cid"=>$campaigns_id,
					)
				);
			if(!$dailyReport){
				$dailyReport = new DailyReport();
				$return.= "<hr/>New record: ";
			}else{
				$return.= "<hr/>Update record: ".$dailyReport->id;
			}
			
			$dailyReport->campaigns_id = $campaigns_id;
			

			if ( !$dailyReport->campaigns_id ) {
				Yii::log("InMobi: invalid external campaign name: '" . $campaign->campaignName, 'warning', 'system.model.api.inmobi');
				$return.= "InMobi: invalid external campaign name";
				continue;
			}

			$dailyReport->date        = $date;
			$dailyReport->providers_id = $this->provider_id;
			$dailyReport->imp         = $campaign->impressions;
			$dailyReport->clics       = $campaign->clicks;
			$dailyReport->conv_api    = ConvLog::model()->count("campaigns_id=:campaignid AND DATE(date)=:date", array(":campaignid"=>$dailyReport->campaigns_id, ":date"=>$date));
			//$dailyReport->conv_adv  = 0;
			$dailyReport->spend       = $campaign->adSpend;
			$dailyReport->updateRevenue();
			$dailyReport->setNewFields();

			if ( !$dailyReport->save() ) {
				print json_encode($dailyReport->getErrors()) . "<br>";
				Yii::log("InMobi: ERROR - saving campaign: " . $campaign->campaignName, 'error', 'system.model.api.inmobi');
				$return.= "InMobi: ERROR - saving campaign: ";
				
				continue;
			}

			$return.= " => saved ok";
		}
		Yii::log("InMobi: SUCCESS - Daily info downloaded. " . date('d-m-Y', strtotime($date)), 'info', 'system.model.api.inmobi');
		return $return;
	}

}
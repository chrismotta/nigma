<?php

class InMobi
{ 

	private $network_id = 12;

	public function downloadInfo()
	{
		if ( isset( $_GET['date']) ) {
			$date = $_GET['date'];
		} else {
			$date = date('Y-m-d', strtotime('yesterday'));
		}

		// validate if info have't been dowloaded already.
		if ( DailyReport::model()->exists("networks_id=:network AND DATE(date)=:date", array(":network"=>$this->network_id, ":date"=>$date)) ) {
			print "InMobi: WARNING - Information already downloaded. <br>";
			return 2;
		}

		// Get json from InMobi API.
		$network = Networks::model()->findbyPk($this->network_id);
		$apikey = $network->token3;
		$user = $network->token1;
		$pass = $network->token2;
		$apiurl = $network->url;
		




	// Create Session
	   $ch = curl_init() or die(Yii::log("Fallo cURL session init: ".curl_error(), 'error', 'system.model.api.inmobi')); 
	   curl_setopt($ch, CURLOPT_URL,"https://api.inmobi.com/v1.0/generatesession/generate");
	   curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	   curl_setopt($ch, CURLOPT_HTTPHEADER,array ('secretKey:'.$apikey,'userName:'.$user,'password:'.$pass));
	   $response = curl_exec($ch) or die(Yii::log("Fallo cURL session init: ".curl_error(), 'error', 'system.model.api.inmobi'));

	// Json to array
	   $newresponse = json_decode($response); 
	   $sessionId = $newresponse->respList[0]->sessionId; // guardo  el id de la session
	   $accountId = $newresponse->respList[0]->accountId; // guardo  el id de la cuenta
	   
	   curl_close($ch);
	   
	// get data Json
	   $getReportJson = '{"reportRequest": 
	   						{
	   							"timeFrame":"'.$date.':'.$date.'",
	   							"groupBy":["campaign","date"],
	   							"orderBy":["date"]
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
				Yii::log("Empty daily report ",'error', 'system.model.api.inmobi');
				return 0;
			}
			else {
				Yii::log($newresponse->errorList[0]->message,'error', 'system.model.api.inmobi');
				return 0;
			}
		}

		if ( !isset($newresponse->respList) ) { // validation add after initial implementation
			Yii::log("Empty daily report ",'error', 'system.model.api.inmobi');
			return 0;
		}
		
		// Save campaigns information 
		foreach ($newresponse->respList as $campaign) {

			if ( $campaign->impressions == 0 && $campaign->clicks == 0) { // if no impressions dismiss campaign
				continue;
			}

			$dailyReport = new DailyReport();
			
			// get campaign ID used in KickAds Server, from the campaign name use in the external network
			$dailyReport->campaigns_id = Utilities::parseCampaignID($campaign->campaignName);

			if ( !$dailyReport->campaigns_id ) {
				Yii::log("InMobi: ERROR - invalid external campaign name: '" . $campaign->campaignName, 'error', 'system.model.api.inmobi');
				continue;
			}

			$dailyReport->date = $date;
			$dailyReport->networks_id = $this->network_id;
			$dailyReport->imp = $campaign->impressions;
			$dailyReport->clics = $campaign->clicks;
			$dailyReport->conv_api = ConvLog::model()->count("campaign_id=:campaignid AND DATE(date)=:date", array(":campaignid"=>$dailyReport->campaigns_id, ":date"=>$date));
			//$dailyReport->conv_adv = 0;
			$dailyReport->spend = $campaign->adSpend;
			$dailyReport->updateRevenue();
			$dailyReport->setNewFields();
			if ( !$dailyReport->save() ) {
				print json_encode($dailyReport->getErrors()) . "<br>";
				Yii::log("InMobi: ERROR - saving campaign: " . $campaign->campaignName, 'error', 'system.model.api.inmobi');
				continue;
			}
		}
		Yii::log("InMobi: SUCCESS - Daily info downloaded. " . date('d-m-Y', strtotime($date)), 'info', 'system.model.api.inmobi');
		return 0;
	}

}
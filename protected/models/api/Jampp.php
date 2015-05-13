<?php

class Jampp
{ 

	private $provider_id = 14;

	public function downloadInfo()
	{
		if ( isset( $_GET['date']) ) {
			$date = $_GET['date'];
		} else {
			$date = date('Y-m-d', strtotime('yesterday'));
		}

		// validate if info have't been dowloaded already.
		if ( DailyReport::model()->exists("providers_id=:providers AND DATE(date)=:date", array(":providers"=>$this->provider_id, ":date"=>$date)) ) {
			Yii::log("Information already downloaded.", 'warning', 'system.model.api.jampp');
			return 2;
		}

		// Get json from Jampp API.
		$network = Networks::model()->findbyPk($this->provider_id);
		$apikey  = $network->token1;
		$apiurl  = $network->url;
		$url     = $apiurl . "/advertisers.json?from=" . $date . "&to=" . $date;
		$params  = array('api_key' => $apikey); 
		 
		$curl = curl_init(); 
		curl_setopt($curl, CURLOPT_URL, $url); 
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); 
		curl_setopt($curl, CURLOPT_POSTFIELDS, $params); 
		$result = curl_exec($curl); 
		curl_close($curl); 
		$result = json_decode($result, true);

		echo json_encode($result) . "<br/><br/><hr/>";

		if ( !isset($result->campaigns) ) {
			Yii::log("ERROR - decoding json", 'error', 'system.model.api.jampp');
			return 1;
		}

		foreach ($result->campaigns as $campaign) {

			$url = $apiurl . "/advertisers/details/" . $campaign->id . ".json?from=" . $date . "&to=" . $date;

			$curl = curl_init(); 
			curl_setopt($curl, CURLOPT_URL, $url); 
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); 
			curl_setopt($curl, CURLOPT_POSTFIELDS, $params); 
			$data = curl_exec($curl); 
			curl_close($curl); 
			$data = json_decode($result, true);
			echo json_encode($data) . "<hr/>"; continue;

			if ( $data->impressions == 0 && $data->clicks == 0) { // if no impressions dismiss campaign
				continue;
			}

			$dailyReport = new DailyReport();
			
			// get campaign ID used in Server, from the campaign name use in the external provider
			$dailyReport->campaigns_id = Utilities::parseCampaignID($data->name);

			if ( !$dailyReport->campaigns_id ) {
				Yii::log("Invalid external campaign name: '" . $data->name, 'warning', 'system.model.api.jampp');
				continue;
			}

			$dailyReport->date         = $date;
			$dailyReport->providers_id = $this->provider_id;
			$dailyReport->imp          = $data->impressions;
			$dailyReport->clics        = $data->clicks;
			$dailyReport->conv_api     = ConvLog::model()->count("campaign_id=:campaignid AND DATE(date)=:date", array(":campaignid"=>$dailyReport->campaigns_id, ":date"=>$date));
			//$dailyReport->conv_adv = 0;
			$dailyReport->spend = $data->cost;
			$dailyReport->updateRevenue();
			$dailyReport->setNewFields();
			if ( !$dailyReport->save() ) {
				Yii::log("Can't save campaign: '" . $data->name . "message error: " . json_encode($dailyReport->getErrors()), 'error', 'system.model.api.jampp');
				continue;
			}

		}

		Yii::log("SUCCESS - Daily info downloaded", 'info', 'system.model.api.jampp');
		return 0;
	}
}
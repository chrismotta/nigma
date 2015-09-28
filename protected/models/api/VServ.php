<?php

class VServ
{ 

	private $provider_id = 11;

	public function downloadInfo()
	{

		if ( isset( $_GET['date']) ) {
			$date = $_GET['date'];
		} else {
			$date = date('Y-m-d', strtotime('yesterday'));
		}

		// validate if info have't been dowloaded already.
		if ( DailyReport::model()->exists("providers_id=:provider AND DATE(date)=:date", array(":provider"=>$this->provider_id, ":date"=>$date)) ) {
			Yii::log("Information already downloaded.", 'warning', 'system.model.api.vServ');
			return 2;
		}

		// Get json from VServ API.
		$network = Networks::model()->findbyPk($this->provider_id);
		$username = $network->token1;
		$password = $network->token2;
		$apiurl = $network->url;
		$url = $apiurl . "?username=" . $username . "&password=" . $password . "&format=xml" ."&from=" . $date . "&until=" . $date;

		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HEADER, false);
		$result = curl_exec($curl);
		$result = Utilities::xml2array($result);
		if (!$result) {
			Yii::log("Empty daily report.", 'info', 'system.model.api.vServ');
			return 1;
		}
		curl_close($curl);

		if ( isset($result['campaignDetails']['campaign']['attr']) ) {
			$this->saveDailyReport($result['campaignDetails']['campaign'], $date);
		} else {
			// Save campaigns information 
			foreach ($result['campaignDetails']['campaign'] as $campaign) {
				$this->saveDailyReport($campaign, $date);
			}
		}

		Yii::log("SUCCESS - Daily info downloaded", 'info', 'system.model.api.vServ');
		return 0;
	}


	private function saveDailyReport($campaign, $date) {
		$dailyReport = new DailyReport();

		// get campaign ID used in Server, from the campaign name use in the external provider
		$dailyReport->campaigns_id = Utilities::parseCampaignID($campaign['attr']['name']);

		if ( !$dailyReport->campaigns_id ) {
			Yii::log("Invalid external campaign name: '" . $campaign['attr']['name'], 'warning', 'system.model.api.vServ');
			continue;
		}

		// sum the total of impression and clicks from all ads
		$impressions = 0;
		$clicks = 0;
		$spend = 0;
		
		if ( isset($campaign['ad']['attr']) ) {
			$impressions = $campaign['ad']['entry']['impressions']['value'];
			$clicks = $campaign['ad']['entry']['clicks']['value'];
			$spend = $campaign['ad']['entry']['spend']['value'];
		} else {
			foreach ($campaign['ad'] as $ad) {
				$impressions += $ad['entry']['impressions']['value'];
				$clicks += $ad['entry']['clicks']['value'];
				$spend += $ad['entry']['spend']['value'];
			}
		}

		if ( $impressions == 0 && $clicks == 0 ) { // if no impressions dismiss campaign
			continue;
		}

		$dailyReport->date = $date;
		$dailyReport->providers_id = $this->provider_id;
		$dailyReport->imp = $impressions;
		$dailyReport->clics = $clicks;
		$dailyReport->conv_api = ConvLog::model()->count("campaigns_id=:campaignid AND DATE(date)=:date", array(":campaignid"=>$dailyReport->campaigns_id, ":date"=>$date));
		//$dailyReport->conv_adv = 0;
		$dailyReport->spend = $spend;
		$dailyReport->updateRevenue();
		$dailyReport->setNewFields();
		if ( !$dailyReport->save() ) {
			Yii::log("Can't save campaign: '" . $campaign['attr']['name'] . "message error: " . json_encode($dailyReport->getErrors()), 'error', 'system.model.api.vServ');
			continue;
		}
	}
}
?>
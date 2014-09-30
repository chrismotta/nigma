<?php

class Mobfox
{ 

	private $network_id = 22;

	public function downloadInfo()
	{
		if ( isset( $_GET['date']) ) {
			$date = $_GET['date'];
		} else {
			$date = date('Y-m-d', strtotime('yesterday'));
		}

		// validate if info have't been dowloaded already.
		if ( DailyReport::model()->exists("networks_id=:network AND DATE(date)=:date", array(":network"=>$this->network_id, ":date"=>$date)) ) {
			Yii::log("WARNING - Information already downloaded.", "warning", "system.model.api.mobfox");
			return 2;
		}

		// Get json from Mobfox API.
		$network = Networks::model()->findbyPk($this->network_id);
		$apiuser = $network->token1;
		$apikey = $network->token2;
		$apiurl = $network->url;
		$url = $apiurl . "/" . $apiuser . "/" . $apikey . "/listCampaigns";
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		$result = curl_exec($curl);
		$result = Utilities::xml2array($result);
		if (!$result) {
			Yii::log("ERROR - Decoding response.", "error", "system.model.api.mobfox");
			return 1;
		}
		curl_close($curl);

		if ( $result['response']['attr']['status'] == 'error' ) {
			Yii::log("ERROR - Getting campaigns. " . $result['response']['error']['value'], "error", "system.model.api.mobfox");
			return 1;
		}

		foreach ($result['response']['campaigns']['campaign'] as $campaign) {

			if ( !isset($campaign['reporting']['impressions']['value']) && !isset($campaign['reporting']['impressions']['value']) ) { // if no traffic dismiss campaign
				continue;
			}

			// Get campaign info for date specified
			$url = $apiurl . "/" . $apiuser . "/" . $apikey . "/generateAdvertiserReport&start_date=" . $date . "&end_date=" . $date . "&type=campaign&id=" . $campaign["campaign_id"]['value'];
			$curl = curl_init($url);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			$campaignInfo = curl_exec($curl);
			$campaignInfo = Utilities::xml2array($campaignInfo);
			if (!$campaignInfo) {
				Yii::log("ERROR - Decoding campaign info: " . $campaignInfo['response']['name']['value'], "error", "system.model.api.mobfox");
				continue;
			}
			curl_close($curl);


			// Validate error in request
			if ($campaignInfo['response']['attr']['status'] == 'error') {
				Yii::log("ERROR - Getting campaign info: " . $campaignInfo['response']['name']['value'], "error", "system.model.api.mobfox");
				continue;
			}

			$dailyReport = new DailyReport();			
			// get campaign ID used in KickAds Server, from the campaign name use in the external network
			$dailyReport->campaigns_id = Utilities::parseCampaignID($campaignInfo['response']['report']['name']['value']);

			if ( !$dailyReport->campaigns_id ) {
				Yii::log("ERROR - invalid external campaign name: '" . $campaignInfo['response']['report']['name']['value'] . "'", "error", "system.model.api.mobfox");
				continue;
			}
			
			// Validate empty info for date specified
			if ( !isset($campaignInfo['response']['report']['statistics']['impressions']['value']) && !isset($campaignInfo['response']['report']['statistics']['clicks']['value']) )  {
					Yii::log("Empty daily report for campaign: " . $campaignInfo['response']['report']['name']['value'], "info", "system.model.api.mobfox");
				continue;
			}

			$dailyReport->networks_id = $this->network_id;
			$dailyReport->imp = $campaignInfo['response']['report']['statistics']['impressions']['value'];
			$dailyReport->clics = $campaignInfo['response']['report']['statistics']['clicks']['value'];
			$dailyReport->conv_api = ConvLog::model()->count("campaign_id=:campaignid AND DATE(date)=:date", array(":campaignid"=>$dailyReport->campaigns_id, ":date"=>$date));
			$dailyReport->conv_adv = 0;
			$dailyReport->spend = $campaignInfo['response']['report']['statistics']['total_cost']['amount']['value'];
			$dailyReport->updateRevenue();
			$dailyReport->setNewFields();
			$dailyReport->date = $date;
			if ( !$dailyReport->save() ) {
				Yii::log("ERROR - saving campaign: '" . $campaignInfo['response']['report']['name']['value'] . "'. Error message: " . json_encode($dailyReport->getErrors()), "error", "system.model.api.mobfox");
				continue;
			}
		}
		Yii::log("SUCCESS - Daily info downloaded. " . date('d-m-Y', strtotime($date)), "error", "system.model.api.mobfox");
		return 0;
	}

}
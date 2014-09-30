<?php

class EroAdvertising
{ 

	private $network_id = 26;

	public function saveCampaign($campaign, $date)
	{
		if(!isset($campaign->clicks->value))continue;
		if ( $campaign->views->value == 0 && $campaign->clicks->value == 0) { // if no impressions dismiss campaign
			continue;
		}
		$dailyReport = new DailyReport();
		// get campaign ID used in KickAds Server, from the campaign name use in the external network
		// 
		$dailyReport->campaigns_id = Utilities::parseCampaignID($campaign->title->value);

		if ( !$dailyReport->campaigns_id ) {
			Yii::log("EroAdvertising: ERROR - invalid external campaign name: ".$campaign->title->value, 'error', 'system.model.api.eroadvertising');
			continue;
		}				
		$dailyReport->networks_id = $this->network_id;
		$dailyReport->imp = $campaign->views->value;
		$dailyReport->clics = $campaign->clicks->value;
		$dailyReport->conv_api = ConvLog::model()->count("campaign_id=:campaignid AND DATE(date)=:date", array(":campaignid"=>$dailyReport->campaigns_id, ":date"=>$date));
		$dailyReport->conv_adv = 0;
		$dailyReport->spend = str_replace(',', '.', $campaign->paid->value);
		$dailyReport->updateRevenue();
		$dailyReport->setNewFields();
		$dailyReport->date = $date;

		if ( !$dailyReport->save() ) {
			print json_encode($dailyReport->getErrors()) . "<br>";
			Yii::log("EroAdvertising: ERROR - saving campaign ".$campaign->title->value, 'error', 'system.model.api.eroadvertising');
			continue;
		}
	}

	public function downloadInfo()
	{
		if ( isset( $_GET['date']) ) {
			$date = $_GET['date'];
		} else {
			$date = date('Y-m-d', strtotime('yesterday'));
		}

		// validate if info have't been dowloaded already.
		if ( DailyReport::model()->exists("networks_id=:network AND DATE(date)=:date", array(":network"=>$this->network_id, ":date"=>$date)) ) {
			Yii::log("EroAdvertising: WARNING - Information already downloaded.", 'warning', 'system.model.api.eroadvertising');
			return 2;
		}

		// Get json from EroAdvertising API.
		$network = Networks::model()->findbyPk($this->network_id);
		$apikey = $network->token1;
		$apiurl = $network->url;
		$url = $apiurl . "?token=" . $apikey . "&periodstart=" . $date . "&periodend=" . $date;
		//https://userpanel.ero-advertising.com/exportstats/advertiser/stats/?token=166dcc6c27d05e39024f034db0d7fd03&periodstart=yyyy-mm-dd&periodend=yyyy-mm-dd
		
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		$result = curl_exec($curl);
		$result = Utilities::xml2array($result);
		$result = json_encode($result);
		// echo $result;
		// return;
		$result = json_decode($result);
		if (!$result) {
			Yii::log("EroAdvertising: WARNING - ERROR - decoding json.", 'error', 'system.model.api.eroadvertising');
			return 1;
		}
		curl_close($curl);	
		if(is_array($result->data->period->stats)){
			foreach ($result->data->period->stats as $stats) {
				if(!is_array($stats->campaign))
				{
					self::saveCampaign($stats->campaign,$date);
				}
				else {
					foreach ($stats->campaign as $campaign) {
						self::saveCampaign($campaign,$date);
					}

				}
			}
		}

		else {

				if(!is_array($result->data->period->stats->campaign))
				{
					self::saveCampaign($result->data->period->stats->campaign,$date);
				}
				else {
					foreach ($result->data->period->stats->campaign as $campaign) {
						self::saveCampaign($campaign,$date);
					}

				}
			
		}
		Yii::log("EroAdvertising: SUCCESS - Daily info downloaded. ".date('d-m-Y', strtotime($date)), 'info', 'system.model.api.eroadvertising');
		return 0;
	}

}
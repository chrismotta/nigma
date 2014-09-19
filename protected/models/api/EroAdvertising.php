<?php

class EroAdvertising
{ 

	private $network_id = 26;

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
		$result = json_decode($result);
		if (!$result) {
			Yii::log("EroAdvertising: WARNING - ERROR - decoding json.", 'error', 'system.model.api.eroadvertising');
			return 1;
		}
		curl_close($curl);		
		//echo json_encode($result->data->period->stats)."<hr>";
		foreach ($result->data->period->stats as $stats) {
			if(!is_array($stats->campaign))
			{
				if(!isset($stats->campaign->clicks->value))continue;
				if ( $stats->campaign->views->value == 0 && $stats->campaign->clicks->value == 0) { // if no impressions dismiss campaign
					continue;
				}
				$dailyReport = new DailyReport();
				// get campaign ID used in KickAds Server, from the campaign name use in the external network
				$dailyReport->campaigns_id = Utilities::parseCampaignID($stats->campaign->title->value);
				if ( !$dailyReport->campaigns_id ) {
					Yii::log("EroAdvertising: ERROR - invalid external campaign name: ".$stats->campaign->title->value, 'error', 'system.model.api.eroadvertising');
					continue;
				}				
				$dailyReport->networks_id = $this->network_id;
				$dailyReport->imp = $stats->campaign->views->value;
				$dailyReport->clics = $stats->campaign->clicks->value;
				$dailyReport->conv_api = ConvLog::model()->count("campaign_id=:campaignid AND DATE(date)=:date", array(":campaignid"=>$dailyReport->campaigns_id, ":date"=>$date));
				$dailyReport->conv_adv = 0;
				$dailyReport->spend = str_replace(',', '.', $stats->campaign->paid->value);
				$dailyReport->updateRevenue();
				$dailyReport->date = $date;

				if ( !$dailyReport->save() ) {
					print json_encode($dailyReport->getErrors()) . "<br>";
					Yii::log("EroAdvertising: ERROR - saving campaign: ".$campaign->title->value, 'error', 'system.model.api.eroadvertising');
					continue;
				}
			}
			else {
				foreach ($stats->campaign as $campaign) {
					if(!isset($campaign->clicks))continue;
					if ( $campaign->views->value == 0 && $campaign->clicks->value == 0) { // if no impressions dismiss campaign
						continue;
					}
					$dailyReport = new DailyReport();
					// get campaign ID used in KickAds Server, from the campaign name use in the external network
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
					$dailyReport->date = $date;

					if ( !$dailyReport->save() ) {
						print json_encode($dailyReport->getErrors()) . "<br>";
						Yii::log("EroAdvertising: ERROR - saving campaign: ".$campaign->title->value, 'error', 'system.model.api.eroadvertising');
						continue;
					}
				}

			}
			
		}
		Yii::log("EroAdvertising: SUCCESS - Daily info downloaded. ".date('d-m-Y', strtotime($date)), 'info', 'system.model.api.eroadvertising');
		return 0;
	}

}
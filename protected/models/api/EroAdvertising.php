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
			print "EroAdvertising: WARNING - Information already downloaded. <br>";
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
			print "EroAdvertising: ERROR - decoding json. <br>";
			return 1;
		}
		curl_close($curl);		
		foreach ($result->data->period->stats as $stats) {
			//print_r(json_encode($stats));
			//print_r(json_encode($stats));		
			//
			//print_r($stats->campaign[0]->title->value);
			foreach ($stats->campaign as $campaign) {
				
				if ( $campaign->views->value == 0 && $campaign->clicks->value == 0) { // if no impressions dismiss campaign
					continue;
				}
				$dailyReport = new DailyReport();
				//echo $campaign->title->value;
				//echo  Utilities::parseCampaignID($campaign->title->value);
				//return;
				// get campaign ID used in KickAds Server, from the campaign name use in the external network
				$dailyReport->campaigns_id = Utilities::parseCampaignID($campaign->title->value);
				if ( !$dailyReport->campaigns_id ) {
					print "EroAdvertising: ERROR - invalid external campaign name: '" . $campaign->title->value . "' <br>";
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
					print "EroAdvertising: ERROR - saving campaign: " . $campaign->title->value . ". <br>";
					continue;
				}
			}
		}
		print "EroAdvertising: SUCCESS - Daily info downloaded. " . date('d-m-Y', strtotime($date)) . ".<br>";
		return 0;
	}

}
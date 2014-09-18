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
			print "Mobfox: WARNING - Information already downloaded. <br>";
			return 2;
		}

		// Get json from Mobfox API.
		$network = Networks::model()->findbyPk($this->network_id);
		$apiuser = $network->token1;
		$apikey = $network->token2;
		$apiurl = $network->url;
		$url = $apiurl . "?apikey=" . $apikey . "&from=" . $date . "&to=" . $date . '&group=campaign_id&totals=total_impressions,total_clicks,total_conversions,total_cost';
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		$result = curl_exec($curl);
		$result = json_decode($result);
		if (!$result) {
			print "Mobfox: ERROR - decoding json. <br>";
			return 1;
		}
		curl_close($curl);

		if ( isset($result->error) ) {
			print json_encode($result->error);
			print "Mobfox: ERROR - Getting campaigns. <br>";
			return 1;
		}

		foreach ($result->results as $campaign) {

			if ( $campaign[2] == 0 && $campaign[3] == 0) { // if no impressions dismiss campaign
				continue;
			}

			$dailyReport = new DailyReport();
			
			// get campaign ID used in KickAds Server, from the campaign name use in the external network
			$dailyReport->campaigns_id = Utilities::parseCampaignID($campaign[1]);

			if ( !$dailyReport->campaigns_id ) {
				print "Mobfox: ERROR - invalid external campaign name: '" . $campaign[1] . "' <br>";
				continue;
			}

			$dailyReport->networks_id = $this->network_id;
			$dailyReport->imp = $campaign[2];
			$dailyReport->clics = $campaign[3];
			$dailyReport->conv_api = ConvLog::model()->count("campaign_id=:campaignid AND DATE(date)=:date", array(":campaignid"=>$dailyReport->campaigns_id, ":date"=>$date));
			$dailyReport->conv_adv = 0;
			$dailyReport->spend = $campaign[5];
			$dailyReport->updateRevenue();
			$dailyReport->date = $date;
			if ( !$dailyReport->save() ) {
				print json_encode($dailyReport->getErrors()) . "<br>";
				print "Mobfox: ERROR - saving campaign: " . $campaign[1] . ". <br>";
				continue;
			}
		}
		print "Mobfox: SUCCESS - Daily info downloaded. " . date('d-m-Y', strtotime($date)) . ".<br>";
		return 0;
	}

}
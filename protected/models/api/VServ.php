<?php

class VServ
{

	private $network_id = 11;

	public function downloadInfo()
	{

		if ( isset( $_GET['date']) ) {
			$date = $_GET['date'];
		} else {
			$date = date('Y-m-d', strtotime('yesterday'));
		}

		// validate if info have't been dowloaded already.
		if ( DailyReport::model()->exists("networks_id=:network AND DATE(date)=:date", array(":network"=>$this->network_id, ":date"=>$date)) ) {
			print "VServ: WARNING - Information already downloaded. <br>";
			return 2;
		}

		// Get json from VServ API.
		$network = Networks::model()->findbyPk($this->network_id);
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
			print "VServ: INFO - '" . $network->name . "' empty daily report. <br>";
			return 1;
		}
		curl_close($curl);

		// Save campaigns information 
		foreach ($result['campaignDetails']['campaign'] as $campaign) {
			$dailyReport = new DailyReport();

			// get campaign ID used in KickAds Server, from the campaign name use in the external network
			$dailyReport->campaigns_id = Utilities::parseCampaignID($campaign['attr']['name']);

			if ( !$dailyReport->campaigns_id ) {
				print "VServ: ERROR - invalid external campaign name: '" . $campaign['attr']['name'] . "' <br>";
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

			if ( $impressions == 0) { // if no impressions dismiss campaign
				continue;
			}

			$dailyReport->networks_id = $this->network_id;
			$dailyReport->imp = $impressions;
			$dailyReport->clics = $clicks;
			$dailyReport->conv_api = ConvLog::model()->count("campaign_id=:campaignid AND DATE(date)=:date", array(":campaignid"=>$dailyReport->campaigns_id, ":date"=>$date));
			$dailyReport->conv_adv = 0;
			$dailyReport->spend = $spend;
			$dailyReport->updateRevenue();
			$dailyReport->date = $date;
			if ( !$dailyReport->save() ) {
				print "VServ: ERROR - saving campaign: " . $campaign['attr']['name'] . "<br>";
				continue;
			}
		}

		print "VServ: SUCCESS - Daily info download. " . date('d-m-Y', strtotime($date)) . ". <br>";
		return 0;
	}
}
?>
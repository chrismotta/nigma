<?php

class LeadBolt
{

	private $network_id = 6;

	public function downloadInfo()
	{
		if ( isset( $_GET['date']) ) {
			$date = $_GET['date'];
		} else {
			$date = date('Y-m-d', strtotime('yesterday'));
		}

		// validate if info have't been dowloaded already.
		if ( DailyReport::model()->exists("networks_id=:network AND DATE(date)=:date", array(":network"=>$this->network_id, ":date"=>$date)) ) {
			print "LeadBolt: WARNING - Information already downloaded. <br>";
			return 2;
		}

		$date = str_replace('-', '', $date); // Leadbolt api use YYYYMMDD date format
		
		// Get json from Loadbolt API.
		$network = Networks::model()->findbyPk($this->network_id);
		$advertiserid = $network->token1;
		$secretkey	= $network->token2;
		$apiurl = $network->url;
		$url = $apiurl . "?advertiser_id=" . $advertiserid . "&secret_key=" . $secretkey . "&format=json" . "&date_from=" . $date . "&date_to=" . $date;

		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		$result = curl_exec($curl);
		$result = json_decode($result);
		if (!$result) {
			print "LeadBolt: ERROR - decoding json. <br>";
			return 1;
		}
		curl_close($curl);

		foreach ($result[0]->data as $campaign) {

			if ( $campaign->impressions == 0 && $campaign->clicks == 0) { // if no impressions dismiss campaign
				continue;
			}
			
			$dailyReport = new DailyReport();
			
			// get campaign ID used in KickAds Server, from the campaign name use in the external network
			$dailyReport->campaigns_id = Utilities::parseCampaignID($campaign->campaign_name);

			if ( !$dailyReport->campaigns_id ) {
				print "LeadBolt: ERROR - invalid external campaign name: '" . $campaign->campaign_name . "' <br>";
				continue;
			}

			$dailyReport->networks_id = $this->network_id;
			$dailyReport->imp = $campaign->impressions;
			$dailyReport->clics = $campaign->clicks;
			$dailyReport->conv_api = ConvLog::model()->count("campaign_id=:campaignid AND DATE(date)=:date", array(":campaignid"=>$dailyReport->campaigns_id, ":date"=>$date));
			$dailyReport->conv_adv = 0;
			$dailyReport->spend = $campaign->spend;
			$dailyReport->updateRevenue();
			$dailyReport->date = $date;
			if ( !$dailyReport->save() ) {
				print "LeadBolt: ERROR - saving campaign: " . $campaign->campaign_name . "<br>";
				continue;
			}
		}
		print "LeadBolt: SUCCESS - Daily info download.  " . date('d-m-Y', strtotime($date)) . ".<br>";
		return 0;
	}

}
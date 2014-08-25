<?php

class LeadBoltController extends Controller
{

	private $network_id = 6;

	public function actionIndex()
	{
		if ( isset( $_GET['date']) ) {
			$date = $_GET['date'];
			$date = str_replace('-', '', $date); // Leadbolt api use YYYYMMDD date format
		} else {
			// print "Parameter 'date' missing.";
			Yii::app()->end(1);
		}

		// validate if info have't been dowloaded already.
		if ( DailyReport::model()->exists("networks_id=:network AND DATE(date)=:date", array(":network"=>$this->network_id, ":date"=>$date)) ) {
			// print "Information already downloaded.";
			Yii::app()->end(2);
		}

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
			// print "ERROR decoding Leadbolt json";
			Yii::app()->end(1);
		}
		curl_close($curl);

		foreach ($result[0]->data as $campaign) {
			$dailyReport = new DailyReport();
			
			// get campaign ID used in KickAds Server, from the campaign name use in the external network
			$dailyReport->campaigns_id = Utilities::parseCampaignID($campaign->campaign_name);

			$dailyReport->networks_id = $this->network_id;
			$dailyReport->imp = $campaign->impressions;
			$dailyReport->clics = $campaign->clicks;
			$dailyReport->conv_api = ConvLog::model()->count("campaign_id=:campaignid AND DATE(date)=:date", array(":campaignid"=>$dailyReport->campaigns_id, ":date"=>$date));
			$dailyReport->conv_adv = 0;
			$dailyReport->spend = $campaign->spend;
			$dailyReport->updateRevenue();
			$dailyReport->date = $date;
			if ( !$dailyReport->save() ) {
				// print "ERROR - saving campaign: " . $campaign->campaign_name . "<br>";
				continue;
			}
		}
		Yii::app()->end();
	}

}
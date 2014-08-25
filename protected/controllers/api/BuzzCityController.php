<?php

class BuzzCityController extends Controller
{

	private $networks_ids = array(7, 8, 9, 10);

	public function actionIndex()
	{
		if ( isset( $_GET['date']) ) {
			$date = $_GET['date'];
		} else {
			// print "Parameter 'date' missing.";
			Yii::app()->end(1);
		}

		foreach ($this->networks_ids as $network_id) {

			// validate if info have't been dowloaded already.
			if ( DailyReport::model()->exists("networks_id=:network AND DATE(date)=:date", array(":network"=>$network_id, ":date"=>$date)) ) {
				// print "Information already downloaded.";
				// Yii::app()->end(2);
				continue;
			}

			// Get json from BuzzCity API.
			$network = Networks::model()->findbyPk($network_id);
			$partnerid = $network->token1;
			$hash = $network->token2;
			$apiurl = $network->url;
			$url = $apiurl . "?partnerid=" . $partnerid . "&hash=" . $hash . "&reporttype=campaign&datefrom=" . $date . "&dateto=" . $date . "&fmt=json&consolidated=1";

			$curl = curl_init($url);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			$result = curl_exec($curl);
			$result = json_decode($result);
			if (!$result) {
				// print "ERROR decoding BuzzCity json";
				continue;
			}
			curl_close($curl);

			if (empty($result->data)) {
				print "ERROR empty result <br>";
				continue;	
			}
			
			// Save campaigns information 
			foreach ($result->data as $campaign) {
				$dailyReport = new DailyReport();
				
				// get campaign ID used in KickAds Server, from the campaign name use in the external network
				$dailyReport->campaigns_id = Utilities::parseCampaignID($campaign->title);

				$dailyReport->networks_id = $network_id;
				$dailyReport->imp = $campaign->exposures;
				$dailyReport->clics = $campaign->clicks;
				$dailyReport->conv_api = ConvLog::model()->count("campaign_id=:campaignid AND DATE(date)=:date", array(":campaignid"=>$dailyReport->campaigns_id, ":date"=>$date));
				$dailyReport->conv_adv = 0;
				$dailyReport->spend = $campaign->spending;
				$dailyReport->updateRevenue();
				$dailyReport->date = $date;
				if ( !$dailyReport->save() ) {
					// print "ERROR - saving campaign: " . $campaign->title . "<br>";
					continue;
				}
			}

			Yii::app()->end();
		}
	}

}
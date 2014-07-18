<?php

class AirpushController extends Controller
{

	private $network_id = 1;

	public function actionIndex()
	{
		if ( isset( $_GET['date']) ) {
			$date = $_GET['date'];
		} else {
			// print "Parameter 'date' missing.";
			Yii::app()->end(1);
		}

		// validate if info have't been dowloaded already.
		if ( DailyReport::model()->exists("networks_id=:network AND DATE(date)=:date", array(":network"=>$this->network_id, ":date"=>$date)) ) {
			print "Information already downloaded.";
			Yii::app()->end(2);
		}

		// Get json from Airpush API.
		$network = Networks::model()->findbyPk($this->network_id);
		$apikey = $network->token1;
		$apiurl = $network->url;
		$url = $apiurl . "?apikey=" . $apikey . "&startDate=" . $date . "&endDate=" . $date;

		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		$result = curl_exec($curl);
		$result = json_decode($result);
		if (!$result) {
			// print "ERROR decoding Airpush json";
			Yii::app()->end(1);
		}
		curl_close($curl);
		
		// Save campaigns information 
		foreach ($result->advertiser_data as $campaign) {
			$dailyReport = new DailyReport();
			
			// get campaign ID used in KickAds Server, from the campaign name use in the external network
			$id_begin = strpos($campaign->campaignname, "*") + 1;
			$id_end = strpos($campaign->campaignname, "*", $id_begin) - 1;
			$dailyReport->campaigns_id = substr($campaign->campaignname, $id_begin,  $id_end - $id_begin + 1);
			
			$dailyReport->networks_id = $this->network_id;
			$dailyReport->imp = $campaign->impression;
			$dailyReport->clics = $campaign->clicks;
			$dailyReport->conv = ConvLog::model()->count("campaign_id=:campaignid AND DATE(date)=:date", array(":campaignid"=>$dailyReport->campaigns_id, ":date"=>$date));
			$dailyReport->spend = $campaign->Spent;
			$dailyReport->model = 0;
			$dailyReport->value = 0;
			$dailyReport->date = $date;
			if ( !$dailyReport->save() ) {
				// print "ERROR - saving campaign: " . $campaign->campaignname . "<br>";
				continue;
			}
		}

		Yii::app()->end();
	}

}
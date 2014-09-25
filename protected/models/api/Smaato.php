<?php 

class Smaato
{ 

	private $network_id = 28;

	public function downloadInfo()
	{
		if ( isset( $_GET['date']) ) {
			$date = $_GET['date'];
		} else {
			$date = date('Y-m-d', strtotime('yesterday'));
		}

		// validate if info have't been dowloaded already.
		if ( DailyReport::model()->exists("networks_id=:network AND DATE(date)=:date", array(":network"=>$this->network_id, ":date"=>$date)) ) {
			Yii::log("Information already downloaded.", 'warning', 'system.model.api.smaato');
			return 2;
		}

		// Get json from Smaato API.
		$network = Networks::model()->findbyPk($this->network_id);
		$apiurl = $network->url;
		$username = $network->token1;
		$password = $network->token2;
		$url = $apiurl . "?startDate=" . $date . "&endDate=" . $date . "&version=1.2";

		// FIXME agregar login por HTTP Authentication

		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HEADER, false);
		$response = curl_exec($curl);
		$response = Utilities::xml2array($response);
		if (!$response) {
			Yii::log("Empty daily report.", 'info', 'system.model.api.smaato');
			return 1;
		}
		curl_close($curl);

		if ( $response->report->retrievals == 1 ) {
			// save only one data
		}

		// Save campaigns information 
		foreach ($response->result->campaign as $campaign) {
			
			// FIXME recorrer country

			// get campaign ID used in KickAds Server, from the campaign name use in the external network
			$dailyReport->campaigns_id = Utilities::parseCampaignID($campaign->attr->name);

			if ( !$dailyReport->campaigns_id ) {
				Yii::log("Invalid external campaign name: '" . $campaign->attr->name, 'info', 'system.model.api.smaato');
				continue;
			}

			if ( $data->impressions->value == 0 && $data->impressions->value == 0 ) { // if no impressions dismiss campaign
				continue;
			}

			$dailyReport->networks_id = $this->network_id;
			$dailyReport->clics       = $data->clicks->value;
			$dailyReport->imp         = $data->impressions->value;
			$dailyReport->conv_api    = ConvLog::model()->count("campaign_id=:campaignid AND DATE(date)=:date", array(":campaignid"=>$dailyReport->campaigns_id, ":date"=>$date));
			$dailyReport->conv_adv    = 0;
			$dailyReport->spend       = $data->spend->value;;
			$dailyReport->updateRevenue();
			$dailyReport->date        = $date;
			if ( !$dailyReport->save() ) {
				Yii::log("Can't save campaign: '" . $campaign->attr->name . "message error: " . json_encode($dailyReport->getErrors()), 'error', 'system.model.api.smaato');
				continue;
			}

		}
		Yii::log("SUCCESS - Daily info downloaded $date", 'info', 'system.model.api.smaato');
		return 0;
	}
}
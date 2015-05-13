<?php

class PlugRush
{ 

	private $provider_id = 58;

	public function downloadInfo()
	{
		if ( isset( $_GET['date']) ) {
			$date = $_GET['date'];
		} else {
			$date = date('Y-m-d', strtotime('yesterday'));
		}

		// validate if info have't been dowloaded already.
		if ( DailyReport::model()->exists("providers_id=:providers AND DATE(date)=:date", array(":providers"=>$this->provider_id, ":date"=>$date)) ) {
			Yii::log("Information already downloaded.", 'warning', 'system.model.api.plugRush');
			return 2;
		}

		// Get json from PlugRush API.
		$network = Networks::model()->findbyPk($this->provider_id);
		$user   = $network->token1;
		$apikey = $network->token2;
		$apiurl = $network->url;
		$url    = $apiurl . "?user=" . $user . "&api_key=" . $apikey . "&startDate=" . $date . "&endDate=" . $date . "&action=advertiser/stats&breakdown=campaigns&method=json";

		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		$result = curl_exec($curl);
		$result = json_decode($result);
		curl_close($curl);

		echo json_encode($result);

		if ( !isset($result->data) ) {
			Yii::log("ERROR - decoding json", 'error', 'system.model.api.plugRush');
			return 1;
		}

		foreach ($result->data as $campaign) {

			if ( $campaign->uniques == 0 && $campaign->raws == 0) { // if no impressions dismiss campaign
				continue;
			}

			$dailyReport = new DailyReport();
			
			// get campaign ID used in Server, from the campaign name use in the external provider
			$dailyReport->campaigns_id = Utilities::parseCampaignID($campaign->campaign);

			if ( !$dailyReport->campaigns_id ) {
				Yii::log("Invalid external campaign name: '" . $campaign->campaign, 'warning', 'system.model.api.plugRush');
				continue;
			}

			$dailyReport->date         = $date;
			$dailyReport->providers_id = $this->provider_id;
			$dailyReport->imp          = $campaign->uniques;
			$dailyReport->clics        = $campaign->raws;
			$dailyReport->conv_api     = ConvLog::model()->count("campaign_id=:campaignid AND DATE(date)=:date", array(":campaignid"=>$dailyReport->campaigns_id, ":date"=>$date));
			//$dailyReport->conv_adv = 0;
			$dailyReport->spend = $campaign->amount;
			$dailyReport->updateRevenue();
			$dailyReport->setNewFields();
			if ( !$dailyReport->save() ) {
				Yii::log("Can't save campaign: '" . $campaign->campaign . "message error: " . json_encode($dailyReport->getErrors()), 'error', 'system.model.api.plugRush');
				continue;
			}

		}

		Yii::log("SUCCESS - Daily info downloaded", 'info', 'system.model.api.plugRush');
		return 0;
	}
}
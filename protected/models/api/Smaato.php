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
		$url = "https://" . urlencode($username) . ":" . urlencode($password) . '@' . $apiurl . "?startDate=" . $date . "&endDate=" . $date . "&version=1.1";

		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HEADER, false);
		$response = curl_exec($curl);
		$response = Utilities::xml2array($response);

		if ( isset($response['html']['body']['h1']) ) {
			Yii::log("ERROR getting report: " . json_encode($response['html']['body']['h1']), 'info', 'system.model.api.smaato');
			return 1;
		}
		curl_close($curl);

		if ( $response['response']['report']['rows']['value'] == 0 ) { // empty daily report
			// save only one data
			Yii::log("Empty daily report.", 'info', 'system.model.api.smaato');
			return 1;
		}

		// Save campaigns information
		if ( $response['response']['report']['rows']['value'] == 1 ) {
			$this->saveDailyReport($response['response']['result']['campaign'], $date);
		} else {
			foreach ($response['response']['result']['campaign'] as $campaign) {
				$this->saveDailyReport($campaign, $date);
			}
		}

		Yii::log("SUCCESS - Daily info downloaded $date", 'info', 'system.model.api.smaato');
		return 0;
	}


	private function saveDailyReport($campaign, $date)
	{
		$dailyReport = new DailyReport;
		// get campaign ID used in KickAds Server, from the campaign name use in the external network
		$dailyReport->campaigns_id = Utilities::parseCampaignID($campaign['attr']['name']);

		if ( !$dailyReport->campaigns_id ) {
			Yii::log("Invalid external campaign name: '" . $campaign['attr']['name'], 'warning', 'system.model.api.smaato');
			continue;
		}
		$data = $campaign['country']['data'];
		if ( $data['impressions']['value'] == 0 && $data['clicks']['value'] == 0 ) { // if no impressions dismiss campaign
			continue;
		}

		$dailyReport->date        = $date;
		$dailyReport->networks_id = $this->network_id;
		$dailyReport->clics       = $data['clicks']['value'];
		$dailyReport->imp         = $data['impressions']['value'];
		$dailyReport->conv_api    = ConvLog::model()->count("campaign_id=:campaignid AND DATE(date)=:date", array(":campaignid"=>$dailyReport->campaigns_id, ":date"=>$date));
		//$dailyReport->conv_adv    = 0;
		$dailyReport->spend       = number_format($data['spendings']['value'], 2);
		$dailyReport->updateRevenue();
		$dailyReport->setNewFields();
		if ( !$dailyReport->save() ) {
			Yii::log("Can't save campaign: '" . $campaign['attr']['name'] . "message error: " . json_encode($dailyReport->getErrors()), 'error', 'system.model.api.smaato');
			continue;
		}
	}
}
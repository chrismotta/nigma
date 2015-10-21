<?php
/*
https://portal.startapp.com/adv/report/1.0?partner=SAP_77487&token=cc355fe8ddde23c6c01ecabb7334f01e&startDate=2015-1-1&endDate=2015-8-10

{"logs":[],"data":[{"impressions":17722,"clicks":280,"installs":6,"spent":2.8,"cr":0.02143,"ctr":0.0158,"campaignId":"SAP_152903","campaignName":"630-CMO-GT-MOV-M-ST-DISP-FONIK-2538"}]}
*/

class StartApp
{ 

	private $provider_id = 24;

	public function downloadInfo()
	{
		$return = '';

		if ( isset( $_GET['date']) ) {
			$date = $_GET['date'];
		} else {
			$date = date('Y-m-d', strtotime('yesterday'));
		}

		// validate if info have't been dowloaded already.
		if ( DailyReport::model()->exists("providers_id=:providers AND DATE(date)=:date", array(":providers"=>$this->provider_id, ":date"=>$date)) ) {
			Yii::log("Information already downloaded.", 'warning', 'system.model.api.airpush');
			return 2;
		}
		// Get json from Airpush API.
		$network = Providers::model()->findbyPk($this->provider_id);
		$partner = $network->token1;
		$token = $network->token2;
		$apiurl = $network->url;
		$url = $apiurl . "?partner=" . $partner . "&token=" . $token . "&startDate=" . $date . "&endDate=" . $date;

		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		$result = curl_exec($curl);
		$result = json_decode($result);
		if (!$result) {
			Yii::log("ERROR - decoding json", 'error', 'system.model.api.airpush');
			return 1;
		}
		curl_close($curl);
		
		// Save campaigns information 
		foreach ($result->data as $campaign) {

			if ( $campaign->impressions == 0 && $campaign->clicks == 0) { // if no impressions dismiss campaign
				continue;
			}

			$dailyReport = new DailyReport();
			
			// get campaign ID used in Server, from the campaign name use in the external provider
			$dailyReport->campaigns_id = Utilities::parseCampaignID($campaign->campaignName);

			if ( !$dailyReport->campaigns_id ) {
				Yii::log("Invalid external campaign name: '" . $campaign->campaignName, 'warning', 'system.model.api.airpush');
				continue;
			}

			$dailyReport->date = $date;
			$dailyReport->providers_id = $this->provider_id;
			$dailyReport->imp = $campaign->impressions;
			$dailyReport->clics = $campaign->clicks;
			$dailyReport->conv_api = ConvLog::model()->count("campaigns_id=:campaignid AND DATE(date)=:date", array(":campaignid"=>$dailyReport->campaigns_id, ":date"=>$date));
			//$dailyReport->conv_adv = 0;
			$dailyReport->spend = $campaign->spent;
			$dailyReport->updateRevenue();
			$dailyReport->setNewFields();
			if ( !$dailyReport->save() ) {
				Yii::log("Can't save campaign: '" . $campaign->campaignname . "message error: " . json_encode($dailyReport->getErrors()), 'error', 'system.model.api.airpush');
				continue;
			}

			$return .= json_encode($campaign);
			$return .= '<br/>';
			continue;
		}
		Yii::log("SUCCESS - Daily info downloaded", 'info', 'system.model.api.airpush');
		return $return;
	}

}
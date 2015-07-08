<?php

class Reporo
{ 

	private $provider_id = 2;

	public function downloadInfo()
	{
		$return = '';

		if ( isset( $_GET['date']) ) {
			$date = $_GET['date'];
		} else {
			$date = date('Y-m-d', strtotime('yesterday'));
		}

		// validate if info have't been dowloaded already.
		if ( DailyReport::model()->exists("providers_id=:provider AND DATE(date)=:date", array(":provider"=>$this->provider_id, ":date"=>$date)) ) {
			Yii::log("Information already downloaded.", 'warning', 'system.model.api.reporo');
			return 2;
		}
		$network = Networks::model()->findbyPk($this->provider_id);

		// --- setting actions for requests
		$actions = array(
			"adv"            => "?action=inventory/advertiser",
			"group"          => "?action=inventory/advertiser/campaign_group/",
			"group_stats"    => "?action=statistics/advertiser/campaign_group/",
			"campaign"       => "?action=inventory/advertiser/campaign/",
			"campaign_stats" => "?action=statistics/advertiser/campaign/"
			);

		$params = '&from=' . $date .'&to=' . $date;
		// --- end base configurations for requests
		
		// --- getting compaign_groups ids
		$groups = $this->getResponse($actions["adv"]);

		if (!$groups) { 
			Yii::log("Getting advertisers inventory.", 'error', 'system.model.api.reporo');
			return 1;
		}

		foreach ($groups->campaign_groups as $campaign_group) {
			$group_id = $campaign_group->campaign_group;	// rename variable only to make code easy to read.

			// check if campaign group has activities for the date specified
			if ( ! $this->getResponse($actions["group_stats"] . $group_id . $params) ) {
				// print "Reporo: INFO - Campaign group: $group_id without activity in the date specified. <br>";
				continue;
			}

			// --- getting compaigns ids from campaign_group id
			$campaigns = $this->getResponse($actions["group"] . $group_id);

			// var_dump($campaigns);
			// die();

			if (! $campaigns) { continue; }

			// Saving campaigns information
			foreach ($campaigns->campaigns as $campaign) {
				$campaign_stats = $this->getResponse($actions["campaign_stats"] . $campaign->campaign . $params);
				if ( ! $campaign_stats ) {
					// print "Campaign:" . $campaign->campaign . "without activity in the date specified. <br>";
					continue; 
				}

				$return.= json_encode($campaign_stats) . '<hr/>';

				// Save campaign information
				$dailyReport = new DailyReport();
				
				$campaign_info = $this->getResponse($actions["campaign"] . $campaign->campaign . $params);
				// get campaign ID used in Server, from the campaign name use in the external provider
				$dailyReport->campaigns_id = Utilities::parseCampaignID($campaign_info->campaign_name, $network->use_alternative_convention_name);

				if ( !$dailyReport->campaigns_id ) {
					Yii::log("Invalid external campaign name: '" . $campaign_info->campaign_name, 'warning', 'system.model.api.reporo');
					continue;
				}

				$dailyReport->date = $date;
				$dailyReport->providers_id = $this->provider_id;
				$dailyReport->imp = $campaign_stats[0]->impressions;
				$dailyReport->clics = $campaign_stats[0]->clicks;
				$dailyReport->conv_api = ConvLog::model()->count("campaign_id=:campaignid AND DATE(date)=:date", array(":campaignid"=>$dailyReport->campaigns_id, ":date"=>$date));
				//$dailyReport->conv_adv = 0;
				$dailyReport->spend = $campaign_stats[0]->spend;
				$dailyReport->updateRevenue();
				$dailyReport->setNewFields();
				if ( !$dailyReport->save() ) {
					Yii::log("Can't save campaign: '" . $campaign->campaign_name . "message error: " . json_encode($dailyReport->getErrors()), 'error', 'system.model.api.reporo');
					continue;
				}
			}
			// --- end getting compaigns ids from campaign_group id
		}
		// --- end getting compaign_groups ids
		Yii::log("SUCCESS - Daily info downloaded", 'info', 'system.model.api.reporo');
		return $return;
	}

	/**
	 * Make the request throw Reporo API with the @param and @return the
	 * object response.
	 *
	 * @param params: parameters after de url link (must include ?)
	 * @return the object created from de json's response. Or NULL if error.
	 */
	private function getResponse($params) {
		$network = Networks::model()->findbyPk($this->provider_id);
		$reporoApiKey = $network->token1;
		$reporoSecretKey = $network->token2;
		$reporoGateway = $network->url;
		
		$reporoReqEpoch = time();
		$reporoReqHash = hash("sha256", $reporoReqEpoch . $reporoSecretKey);
		
		$options = array(
		    'http' => array(
		        'method' => 'GET',
		        'header' =>
		        "accept: application/xml" . "\r\n" .
		        "x-reporo-key: $reporoApiKey" . "\r\n" .
		        "x-reporo-epoch: $reporoReqEpoch" . "\r\n" .
		        "x-reporo-mash: $reporoReqHash" . "\r\n"
			    )
		);
		$context = stream_context_create($options);
		$response = file_get_contents($reporoGateway . $params, false, $context);
// var_dump($options);
// die($reporoGateway.$params);
		$obj = json_decode($response);
		
		if ( empty($obj) ) {
			Yii::log("ERROR - json is empty", 'info', 'system.model.api.reporo');
			return NULL;
		}

		if ( ! $obj ) {
			Yii::log("ERROR - decoding json", 'info', 'system.model.api.reporo');
			return NULL;
		}
		
		return $obj;
	}

}
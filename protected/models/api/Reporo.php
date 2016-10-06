<?php

class Reporo
{ 

	private $provider_id = 2;
	private $apiLog;

	public function downloadInfo($offset)
	{

		date_default_timezone_set('UTC');
		$return = '';

		if ( isset( $_GET['date']) ) {
			
			// specific date
			
			$date = $_GET['date'];
			$this->apiLog = ApiLog::initLog($date, $this->provider_id, null);
			$return.= $this->downloadDateInfo($date);
		
		} else {

			if(date('G')<=$offset){
				$return.= '<hr/>yesterday<hr/>';
				$date = date('Y-m-d', strtotime('yesterday'));
				$this->apiLog = ApiLog::initLog($date, $this->provider_id, null);
				$return.= $this->downloadDateInfo($date);
			}

			//default
			$return.= '<hr/>today<hr/>';
			$date = date('Y-m-d', strtotime('today'));
			$this->apiLog = ApiLog::initLog($date, $this->provider_id, null);
			$return.= $this->downloadDateInfo($date);
		
		}
		

		return $return;
	}

	public function downloadDateInfo($date)
	{

		$return = "";
		$network = Providers::model()->findbyPk($this->provider_id);

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

				// $return.= json_encode($campaign_stats) . '<hr/>';


				// get campaign ID used in Server, from the campaign name use in the external provider
				$campaign_info = $this->getResponse($actions["campaign"] . $campaign->campaign . $params);

				$return.= $campaign_info->campaign_name;
				$return.= '<br>';
				
				// if is vector
				if(substr($campaign_info->campaign_name, 0, 1)=='v'){

					$vid = Utilities::parseVectorID($campaign_info->campaign_name);
					$vectorModel = Vectors::model()->findByPk($vid);

					$ret = $vectorModel->explodeVector(array('spend'=>$campaign_info->campaign_name,'date'=>$date));
					$return .= json_encode($ret);
					$return.= '<br>';
					continue;

				}

				
				$campaigns_id = Utilities::parseCampaignID($campaign_info->campaign_name);

				// if exists overwrite, else create a new
				$dailyReport = DailyReport::model()->find(
					"providers_id=:providers AND DATE(date)=:date AND campaigns_id=:cid", 
					array(
						":providers"=>$this->provider_id, 
						":date"=>$date, 
						":cid"=>$campaigns_id,
						)
					);
				if(!$dailyReport){
					$dailyReport = new DailyReport();
					$return.= "<hr/>New record: ";
				}else{
					$return.= "<hr/>Update record: ".$dailyReport->id;
				}


				
				// get campaign ID used in Server, from the campaign name use in the external provider
				$dailyReport->campaigns_id = $campaigns_id;

				if ( !$dailyReport->campaigns_id ) {
					Yii::log("Invalid external campaign name: '" . $campaign_info->campaign_name, 'warning', 'system.model.api.reporo');
					continue;
				}

				$dailyReport->date = $date;
				$dailyReport->providers_id = $this->provider_id;
				$dailyReport->imp = $campaign_stats[0]->impressions;
				$dailyReport->clics = $campaign_stats[0]->clicks;
				$dailyReport->conv_api = ConvLog::model()->count("campaigns_id=:campaignid AND DATE(date)=:date", array(":campaignid"=>$dailyReport->campaigns_id, ":date"=>$date));
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
		$network = Providers::model()->findbyPk($this->provider_id);
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
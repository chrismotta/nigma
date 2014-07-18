<?php

class ReporoController extends Controller
{

	private $network_id = 2;

	public function actionIndex()
	{
		print "Reporo Controller <br>";

		if ( isset( $_GET['date']) ) {
			$date = $_GET['date'];
		} else {
			print "Parameter 'date' missing.";
			Yii::app()->end(1);
		}


		// validate if info have't been dowloaded already.
		if ( DailyReport::model()->exists("networks_id=:network AND DATE(date)=:date", array(":network"=>$this->network_id, ":date"=>$date)) ) {
			// print "Information already downloaded.";
			Yii::app()->end(2);
		}

		// --- setting actions for requests
		$actions = array(
			"adv"		=> "?action=inventory/advertiser",
			"group"		=> "?action=inventory/advertiser/campaign_group/",
			"group_stats"	=> "?action=statistics/advertiser/campaign_group/",
			"campaign"		=> "?action=inventory/advertiser/campaign/",
			"campaign_stats"=> "?action=statistics/advertiser/campaign/"
			);

		$params = '&from=' . $date .'&to=' . $date;
		// --- end base configurations for requests
		
		// --- getting compaign_groups ids
		$groups = $this->getResponse($actions["adv"]);

		if (!$groups) { Yii::app()->end(1); }

		foreach ($groups->campaign_groups as $campaign_group) {
			$group_id = $campaign_group->campaign_group;	// rename variable only to make code easy to read.

			// check if campaign group has activities for the date specified
			if ( ! $this->getResponse($actions["group_stats"] . $group_id . $params) ) {
				// print "Campaign group: $group_id without activity in the date specified. <br>";
				continue;
			}

			// --- getting compaigns ids from campaign_group id
			$campaigns = $this->getResponse($actions["group"] . $group_id);

			if (! $campaigns) { continue; }

			// Saving campaigns information
			foreach ($campaigns->campaigns as $campaign) {
				$campaign_stats = $this->getResponse($actions["campaign_stats"] . $campaign->campaign . $params);
				if ( ! $campaign_stats ) {
					print "Campaign:" . $campaign->campaign . "without activity in the date specified. <br>";
					continue; 
				}

				// Save campaign information
				$dailyReport = new DailyReport();
				
				$campaign_info = $this->getResponse($actions["campaign"] . $campaign->campaign . $params);
				// get campaign ID used in KickAds Server, from the campaign name use in the external network
				$id_begin = strpos($campaign_info->campaign_name, "*") + 1;
				$id_end = strpos($campaign_info->campaign_name, "*", $id_begin) - 1;
				$dailyReport->campaigns_id = substr($campaign_info->campaign_name, $id_begin,  $id_end - $id_begin + 1);

				$dailyReport->networks_id = $this->network_id;
				$dailyReport->imp = $campaign_stats[0]->impressions;
				$dailyReport->clics = $campaign_stats[0]->clicks;
				$dailyReport->conv = ConvLog::model()->count("campaign_id=:campaignid AND DATE(date)=:date", array(":campaignid"=>$dailyReport->campaigns_id, ":date"=>$date));
				$dailyReport->spend = $campaign_stats[0]->spend;
				$dailyReport->model = 0;
				$dailyReport->value = 0;
				$dailyReport->date = $date;
				if ( !$dailyReport->save() ) {
					// print "ERROR - saving campaign: " . $campaign->campaignname . "<br>";
					continue;
				}
			}
			// --- end getting compaigns ids from campaign_group id
		}
		// --- end getting compaign_groups ids
		Yii::app()->end();
	}

	/**
	 * Make the request throw Reporo API with the @param and @return the
	 * object response.
	 *
	 * @param params: parameters after de url link (must include ?)
	 * @return the object created from de json's response. Or NULL if error.
	 */
	private function getResponse($params) {
		$network = Networks::model()->findbyPk($this->network_id);
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

		$obj = json_decode($response);
		
		if ( empty($obj) ) {
			// print "ERROR Airpush json is empty <br>";
			return NULL;
		}

		if ( ! $obj ) {
			// print "ERROR decoding Airpush json <br>";
			return NULL;
		}
		
		return $obj;
	}

}
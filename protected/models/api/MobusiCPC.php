<?php

class MobusiCPC
{ 

	private $provider_id = 302;
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
				
				$return.= $this->downloadDateInfo($date);
				$this->apiLog = ApiLog::initLog($date, $this->provider_id, null);
			}

			//default
			$return.= '<hr/>today<hr/>';
			$date = date('Y-m-d', strtotime('today'));


			$return.= $this->downloadDateInfo($date);
			$this->apiLog = ApiLog::initLog($date, $this->provider_id, null);
		}


		return $return;
	}

	public function downloadDateInfo($date)
	{

		$return = "";
		$network = Providers::model()->findbyPk($this->provider_id);

		// --- setting actions for requests
		$response = $this->getResponse( array(
			'type' => 'stats', 
			'group' => array( "offer_name" ), 
			'columns' => array( "imp","leads","money" ),
			'orders' => array("imp"),
			'start_date' => $date,
			'end_date' => $date 
		));


		if (!$response) { 
			Yii::log("Getting advertisers inventory.", 'error', 'system.model.api.reporo');
			return 1;
		}

		if ( !isset($response['answer']) )
			return 1;

		foreach ( $response['answer'] as $campaign_name => $campaign_stats )
		{
			// if is vector
			if(substr($campaign_name, 0, 1)=='v'){

				$vid = Utilities::parseVectorID($campaign_name);
				$vectorModel = Vectors::model()->findByPk($vid);

				$ret = $vectorModel->explodeVector(array('spend'=>$campaign_stats['money']->spend,'date'=>$date));
				$return .= json_encode($ret);
				$return.= '<br>';
				continue;
			}

			$campaigns_id = Utilities::parseCampaignID($campaign_name);

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
				Yii::log("Invalid external campaign name: '" . $campaign_name, 'warning', 'system.model.api.reporo');
				continue;
			}

			$dailyReport->date = $date;
			$dailyReport->providers_id = $this->provider_id;
			$dailyReport->imp = $campaign_stats['imp'];
			$dailyReport->clics = $campaign_stats['leads'];
			$dailyReport->conv_api = ConvLog::model()->count("campaigns_id=:campaignid AND DATE(date)=:date", array(":campaignid"=>$dailyReport->campaigns_id, ":date"=>$date));
			//$dailyReport->conv_adv = 0;
			$dailyReport->spend = $campaign_stats['money'];
			$dailyReport->updateRevenue();
			$dailyReport->setNewFields();
			if ( !$dailyReport->save() ) {
				Yii::log("Can't save campaign: '" . $campaign_name . "message error: " . json_encode($dailyReport->getErrors()), 'error', 'system.model.api.reporo');
				continue;
			}			
		}
		/*
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

					$ret = $vectorModel->explodeVector(array('spend'=>$campaign_stats[0]->spend,'date'=>$date));
					$return .= json_encode($ret);
					$return.= '<br>';
					continue;

				}

				

			}
			// --- end getting compaigns ids from campaign_group id
		}
		*/
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
		$mobusiUserId = 14016; //$network->token1;
		$mobusiApiKey = '429fb7c0af8af1d0db33d0107b79e06b';//$network->token2;
		$mobusiGateway = 'http://api.leadzu.com/report';//$network->url;
		
		$options =array(
		   	'api_key' => $mobusiApiKey,
		   	'user_id' => $mobusiUserId,
		   	'report' => $params
		);

		$handler = curl_init( $mobusiGateway );
		curl_setopt ( $handler, CURLOPT_URL, $mobusiGateway );
		curl_setopt ( $handler, CURLOPT_RETURNTRANSFER, true );
		curl_setopt ( $handler, CURLOPT_VERBOSE, 1 );
		curl_setopt ( $handler, CURLOPT_HEADER, 1 );
		curl_setopt ( $handler, CURLOPT_POST, true );		
		curl_setopt ( $handler, CURLOPT_POSTFIELDS, json_encode($options) );	
		curl_setopt( $handler, CURLOPT_HTTPHEADER, array( 'Content-Type: application/json' ) );
		$response = curl_exec ( $handler );
		curl_close( $handler );
		var_export($response);die();

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
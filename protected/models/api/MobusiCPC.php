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
		if ( $network->currency!='USD')
			$not_usd = true;
		else
			$not_usd = false;

		// --- setting actions for requests
		$response = $this->getResponse( array(
			'type' => 'stats', 
			'group' => array( "id_offer" ), 
			'columns' => array( "imp","leads","money" ),
			'orders' => array("imp"),
			'start_date' => $date,
			'end_date' => $date 
		));

		// print Mobusi response and die
		var_export($response);

		if (!$response || !is_array($response) ) { 
			Yii::log("Getting advertisers inventory.", 'error', 'system.model.api.reporo');
			return 1;
		}

		if ( 
			!isset($response['type']) 
			|| $response['type']!='ok' 
			|| !isset($response['answer']) 
			|| isset($response['answer']['items'])
		)
			return 1;

		foreach ( $response['answer'] as $ext_cid => $campaign )
		{
			$p = strrpos( $campaign['offer_name'], ' ' );
			$l = strlen($campaign['offer_name']);

			if ( $p == $l-1 )
			{
				$tmp = substr( $campaign['offer_name'], 0, $p-1 );
				$name = substr( $tmp, $p+1 );
				$name .= ' ';
			}
			else
				$name = substr( $campaign['offer_name'],  $p+1 );
							
			// if is vector
			if(substr($name, 0, 1)=='v'){

				$vid = Utilities::parseVectorID($name);
				$vectorModel = Vectors::model()->findByPk($vid);
				//var_export($campaign);
				$ret = $vectorModel->explodeVector(array(
					'spend'=>$campaign['money'],
					'date'=>$date,
					'not_usd'=> $not_usd,
				));
				$return .= json_encode($ret);
				$return.= '<br>';
				continue;
			}

			$campaigns_id = Utilities::parseCampaignID($name);
			//var_export( $campaigns_id . ': ' . $campaign['leads'] . '<br>');
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
				Yii::log("Invalid external campaign name: '" . $name, 'warning', 'system.model.api.reporo');
				continue;
			}

			$dailyReport->date = $date;
			$dailyReport->providers_id = $this->provider_id;
			$dailyReport->imp = $campaign['imp'];
			$dailyReport->clics = $campaign['leads'];
			$dailyReport->conv_api = ConvLog::model()->count("campaigns_id=:campaignid AND DATE(date)=:date", array(":campaignid"=>$dailyReport->campaigns_id, ":date"=>$date));
			//$dailyReport->conv_adv = 0;
			$dailyReport->spend = $campaign['money'];
			$dailyReport->spend = $dailyReport->getSpendUSD();
			$dailyReport->updateRevenue();
			$dailyReport->setNewFields();
			if ( !$dailyReport->save() ) {
				Yii::log("Can't save campaign: '" . $name . "message error: " . json_encode($dailyReport->getErrors()), 'error', 'system.model.api.reporo');
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
		curl_setopt ( $handler, CURLOPT_HEADER, false );
		curl_setopt ( $handler, CURLOPT_POST, true );		
		curl_setopt ( $handler, CURLOPT_POSTFIELDS, json_encode($options) );	
		curl_setopt( $handler, CURLOPT_HTTPHEADER, array( 'Content-Type: application/json' ) );
		$response = curl_exec ( $handler );
		curl_close( $handler );

		$obj = json_decode($response, true);
		
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
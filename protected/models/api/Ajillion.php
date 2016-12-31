<?php

class Ajillion
{ 

	private $provider_id = 3;
	private $apiLog;

	public function downloadInfo($offset)
	{

		date_default_timezone_set('UTC');
		$return = '';

		if ( isset( $_GET['date']) ) {
		
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

		// ajillion IDs - separated with '-'
		$fixed_adv = isset($_GET['adv']) ? $_GET['adv'] : null;

		if ( isset( $_GET['cid']) ) {
			$cid = $_GET['cid'];
			if ( !Campaigns::model()->exists( "id=:id", array(":id" => $cid)) ) {
				Yii::log("campaigns_id: $cid doesn't exists.", 'warning', 'system.model.api.ajillion');
				return 2;
			}
		}

		// validate if info have't been dowloaded already.
		/*
		if ( isset($cid) )
			$alreadyExist = DailyReport::model()->exists("providers_id=:providers AND campaigns_id=:campaigns_id AND DATE(date)=:date", array(":providers"=>$this->provider_id, ":date"=>$date, ":campaigns_id" => $cid));
		else 
			$alreadyExist = DailyReport::model()->exists("providers_id=:providers AND DATE(date)=:date", array(":providers"=>$this->provider_id, ":date"=>$date));
		if ( $alreadyExist ) {
			Yii::log("Information already downloaded.", 'warning', 'system.model.api.ajillion');
			return 2;
		}
		*/

		$date = date_format( new DateTime($date), "m/d/Y" ); // Ajillion api use mm/dd/YYYY date format
		
		$this->apiLog->updateLog('Processing', 'Getting advertisers list');

		// if adv parameter is setted
		if (isset($fixed_adv)){ 

			$adv_ids = explode('-', $fixed_adv);
		
		}else{
		
			// get all advertisers
			$advertisers = $this->getResponse("advertiser.get");
			if ( !$advertisers ) {
				Yii::log("Can't get advertisers", 'error', 'system.model.api.ajillion');
				return 1;
			}

			$adv_ids = array();
			foreach ($advertisers as $adv) {
				$adv_ids[] = $adv->id;
			}
		
		}	

		// get all campaigns from all advertisers
		$params = array(
				"columns"=>array("campaign"),
				"sums"=>array("hits", "cost", "impressions", "conversions"),
				"campaign_uids"=>array(),
				"advertiser_ids"=>$adv_ids,
				"start_date"=>$date,
				"end_date"=>$date,
			);

		$this->apiLog->updateLog('Processing', 'Getting traffic data');

		$campaigns = $this->getResponse("report.advertiser.performance.get", $params);

		if ( !$campaigns ) {
			Yii::log("Can't get campaigns", 'error', 'system.model.api.ajillion');
			return 1;
		}

		$this->apiLog->updateLog('Processing', 'Writing traffic data');

		$updated = 0;
		$prevSavedCmps = [];
		foreach ($campaigns as $campaign) {

			if ( $campaign->impressions == 0) { // if no impressions dismiss campaign
				continue;
			}

			// get campaign ID used in Server, from the campaign name use in the external provider
			$campaigns_id = Utilities::parseCampaignID($campaign->campaign);

			if ( !$campaigns_id ) {
				Yii::log("invalid external campaign name: '" . $campaign->campaign, 'warning', 'system.model.api.ajillion');
				continue;
			}

			$formated_date = date_format( new DateTime($date), "Y-m-d" );

			// if exists overwrite, else create a new
			$dailyReport = DailyReport::model()->find(
				"providers_id=:providers AND DATE(date)=:date AND campaigns_id=:cid", 
				array(
					":providers"=>$this->provider_id, 
					":date"=>$formated_date, 
					":cid"=>$campaigns_id,
					)
				);

			if(!$dailyReport){
				$dailyReport = new DailyReport();
				$return.= "<hr/>New record: ";
			}else{
				$return.= "<hr/>Update record: ".$dailyReport->id;
			}
			
			$dailyReport->campaigns_id = $campaigns_id;

			// only when $_GET['cid'] is setted // not verified
			$returnAfterSave = false;
			if ( isset($cid) ) {
				if ( $cid == $dailyReport->campaigns_id )
					$returnAfterSave = true;
				else
					continue;
			}

			$dailyReport->date = $formated_date;
			$dailyReport->providers_id = $this->provider_id;

			// echo '<hr>'.json_encode($prevSavedCmps).' - '.$campaigns_id.'<hr>';

			$sumRevenue = false;
			if ( array_search($campaigns_id, $prevSavedCmps) ) 
			{ 
				$dailyReport->imp = $dailyReport->imp + $campaign->impressions;
				$dailyReport->clics = $campaign->hits + $campaign->hits;
				$dailyReport->conv_api = $dailyReport->conv_api + ConvLog::model()->count("campaigns_id=:campaignid AND DATE(date)=:date", array(":campaignid"=>$dailyReport->campaigns_id, ":date"=>date('Y-m-d', strtotime($date))));
				//$dailyReport->conv_adv = 0;
				$dailyReport->spend = number_format($dailyReport->spend+$campaign->cost, 2, '.', '');
				$return .= ' - (sum) ';				
			}
			else
			{
				$dailyReport->imp = $campaign->impressions;
				$dailyReport->clics = $campaign->hits;
				$dailyReport->conv_api = ConvLog::model()->count("campaigns_id=:campaignid AND DATE(date)=:date", array(":campaignid"=>$dailyReport->campaigns_id, ":date"=>date('Y-m-d', strtotime($date))));
				//$dailyReport->conv_adv = 0;
				$dailyReport->spend = number_format($campaign->cost, 2, '.', '');

			}

			$campaignModel = Campaigns::model()->findByPk($campaigns_id);
			$model_adv = $campaignModel->opportunities->model_adv;
			$return.= ' - '.$model_adv;



			if($model_adv != 'RS'){
					$dailyReport->updateRevenue();

				$dailyReport->setNewFields();
				$return.= ' -Revenue Share type- ';
			}else{
				$return.= ' -Not Revenue Share type- ';
			}

			$return.='<br/>';
			$return.='Campaign:'.$campaign->campaign.' - Impressions: '.$campaign->impressions.' - Hits:'.$campaign->hits.' - Cost:'.$campaign->cost;
			$return.='<br/>';
			$return.='Nigma write: - Impressions: '.$dailyReport->imp.' - Clicks:'.$dailyReport->clics.' - Spend:'.$dailyReport->spend;

			if ( !$dailyReport->save() ) {
				Yii::log("Can't save campaign: '" . $campaign->campaign . "message error: " . json_encode($dailyReport->getErrors()), 'error', 'system.model.api.ajillion');
			}else{
				$prevSavedCmps[] = intval($campaigns_id);
				$updated++;
				$return.='<br/>===> saved';
			}

			if ( $returnAfterSave ) // return if only has to update one campaign
				break;
		}

		$this->apiLog->updateLog('Completed', 'Procces completed: '.$updated.' campaigns updated');

		Yii::log("SUCCESS - Daily info downloaded", 'info', 'system.model.api.ajillion');
		return $return;
	}


	private function getResponse($method, $params = array() ) {

		// Get json from Ajillion API.
		$network = Providers::model()->findbyPk($this->provider_id);
		$apiurl = $network->url;
		$user = $network->token1;
		$pass = $network->token2;

		$this->apiLog->updateLog('Processing', 'Authenticating credentials');

		$curl = curl_init($apiurl);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-type: application/json-rpc"));
		curl_setopt($curl, CURLOPT_POST, true);

		// getting token request
		$data = array(
			"jsonrpc"=>"2.0",
			"id"=>123,
			"method"=>"login",
			"params"=>array(
				"username"=>$user,
				"password"=>$pass,
				)
			);

		curl_setopt( $curl, CURLOPT_POSTFIELDS, json_encode($data) );
		$json_response = curl_exec($curl);
		$login = json_decode($json_response);

		if ( !$login ) {
			Yii::log("Login error", 'error', 'system.model.api.ajillion');
			return NULL;
		}

		if ( isset($login->error) && $login->error !== NULL ) {
			Yii::log($login->error->message, 'error', 'system.model.api.ajillion');
			return NULL;	
		}

		$token = $login->result->token;

		// --- getting advertirsers IDs.
		$params = array("token"=>$token) + $params;

		$data = array(
		    "jsonrpc"=> "2.0",
		    "id"=>123,
		    "method"=>$method,
		    "params"=>$params
			);

		curl_setopt( $curl, CURLOPT_POSTFIELDS, json_encode($data) );
		$json_response = curl_exec($curl);
		$response = json_decode($json_response);

		if ( !$response ) {
			Yii::log("Error decoding json", 'error', 'system.model.api.ajillion');
			return NULL;
		}
		if (  isset($response->error) && $response->error !== NULL ) {
			Yii::log($response->error->message . " error", 'error', 'system.model.api.ajillion');
			return NULL;	
		}

		if ( empty($response->result) ) {
			Yii::log("Json is empty", 'error', 'system.model.api.ajillion');
			return NULL;
		}

		curl_close($curl);
		return $response->result;
	}
}
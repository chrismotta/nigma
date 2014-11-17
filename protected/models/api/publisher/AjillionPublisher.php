<?php

class AjillionPublisher
{ 

	private $network_id = 3;

	public function downloadInfo()
	{
		if ( isset( $_GET['date']) ) {
			$date = $_GET['date'];
		} else {
			$date = date('Y-m-d', strtotime('yesterday'));
		}


		// validate if info have't been dowloaded already.
		if ( DailyReport::model()->exists("networks_id=:network AND DATE(date)=:date", array(":network"=>$this->network_id, ":date"=>$date)) ) {
			Yii::log("Information already downloaded.", 'warning', 'system.model.api.ajillion');
			return 2;
		}

		$date = date_format( new DateTime($date), "m/d/Y" ); // Ajillion api use mm/dd/YYYY date format
		
		// get all publishers
		$publishers = $this->getResponse("publisher.get");
		if ( !$publishers ) {
			Yii::log("Can't get publishers", 'error', 'system.model.api.ajillion');
			return 1;
		}

		$pub_ids = array();
		foreach ($publishers as $pub) {
			$pub_ids[] = $pub->id;
		}
echo json_encode($pub_ids) . "<hr>";
		// get all campaigns from all advertisers
		$params = array(
				"columns"=>array("publisher", "placement"),
				"sums"=>array("impressions", "hits", "revenue", "ecpm", "profit"),
				"placement_ids"=>array(),
				"publisher_ids"=>$pub_ids,
				"start_date"=>$date,
				"end_date"=>$date,
			);

		$campaigns = $this->getResponse("report.publisher.get", $params);

		if ( !$campaigns ) {
			Yii::log("Can't get campaigns", 'error', 'system.model.api.ajillion');
			return 1;
		}
echo json_encode($campaigns);
return NULL;
		// foreach ($campaigns as $campaign) {

		// 	if ( $campaign->impressions == 0) { // if no impressions dismiss campaign
		// 		continue;
		// 	}

		// 	// Save campaign information
		// 	$dailyReport = new DailyReport();
			
		// 	// get campaign ID used in KickAds Server, from the campaign name use in the external network
		// 	$dailyReport->campaigns_id = Utilities::parseCampaignID($campaign->campaign);

		// 	if ( !$dailyReport->campaigns_id ) {
		// 		Yii::log("invalid external campaign name: '" . $campaign->campaign, 'warning', 'system.model.api.ajillion');
		// 		continue;
		// 	}

		// 	$returnAfterSave = false;
		// 	if ( isset($cid) ) {
		// 		if ( $cid == $dailyReport->campaigns_id )
		// 			$returnAfterSave = true;
		// 		else
		// 			continue;
		// 	}

		// 	$dailyReport->date = date_format( new DateTime($date), "Y-m-d" );
		// 	$dailyReport->networks_id = $this->network_id;
		// 	$dailyReport->imp = $campaign->impressions;
		// 	$dailyReport->clics = $campaign->hits;
		// 	$dailyReport->conv_api = ConvLog::model()->count("campaign_id=:campaignid AND DATE(date)=:date", array(":campaignid"=>$dailyReport->campaigns_id, ":date"=>date('Y-m-d', strtotime($date))));
		// 	//$dailyReport->conv_adv = 0;
		// 	$dailyReport->spend = number_format($campaign->cost, 2);
		// 	$dailyReport->updateRevenue();
		// 	$dailyReport->setNewFields();
		// 	if ( !$dailyReport->save() ) {
		// 		Yii::log("Can't save campaign: '" . $campaign->campaign . "message error: " . json_encode($dailyReport->getErrors()), 'error', 'system.model.api.ajillion');
		// 	}

		// 	if ( $returnAfterSave ) // return if only has to update one campaign
		// 		break;
		// }
		Yii::log("SUCCESS - Daily info downloaded", 'info', 'system.model.api.ajillion');
		return 0;
	}


	private function getResponse($method, $params = array() ) {

		// Get json from Ajillion API.
		$network = Networks::model()->findbyPk($this->network_id);
		$apiurl = $network->url;
		$user = $network->token1;
		$pass = $network->token2;

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
				"password"=>$pass
				)
			);

		curl_setopt( $curl, CURLOPT_POSTFIELDS, json_encode($data) );
		$json_response = curl_exec($curl);
		$login = json_decode($json_response);

		if ( !$login ) {
			Yii::log("Login error", 'error', 'system.model.api.ajillion');
			return NULL;
		}

		if ( $login->error !== NULL ) {
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

		if ( $response->error !== NULL ) {
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
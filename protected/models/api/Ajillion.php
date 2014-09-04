<?php

class Ajillion
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
			print "Ajillion: WARNING - Information already downloaded. <br>";
			return 2;
		}

		$date = date_format( new DateTime($date), "m/d/Y" ); // Ajillion api use mm/dd/YYYY date format
		
		// get all advertisers
		$advertisers = $this->getResponse("advertiser.get");
		if ( !$advertisers ) {
			print "Ajillion: ERROR - Getting advertisers. <br>";
			return 1;
		}

		$adv_ids = array();
		foreach ($advertisers as $adv) {
			$adv_ids[] = $adv->id;
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

		$campaigns = $this->getResponse("report.advertiser.performance.get", $params);

		if ( !$campaigns ) {
			print "Ajillion: ERROR - Getting campaigns. <br>";
			return 1;
		}

		foreach ($campaigns as $campaign) {

			if ( $campaign->impressions == 0) { // if no impressions dismiss campaign
				continue;
			}

			// Save campaign information
			$dailyReport = new DailyReport();
			
			// get campaign ID used in KickAds Server, from the campaign name use in the external network
			$dailyReport->campaigns_id = Utilities::parseCampaignID($campaign->campaign);

			if ( !$dailyReport->campaigns_id ) {
				print "Ajillion: ERROR - invalid external campaign name: '" . $campaign->campaign . "' <br>";
				continue;
			}

			$dailyReport->networks_id = $this->network_id;
			$dailyReport->imp = $campaign->impressions;
			$dailyReport->clics = $campaign->hits;
			$dailyReport->conv_api = ConvLog::model()->count("campaign_id=:campaignid AND DATE(date)=:date", array(":campaignid"=>$dailyReport->campaigns_id, ":date"=>$date));
			$dailyReport->conv_adv = 0;
			$dailyReport->spend = $campaign->cost;
			$dailyReport->updateRevenue();
			$dailyReport->date = date_format( new DateTime($date), "Y-m-d" );
			if ( !$dailyReport->save() ) {
				print "Ajillion: ERROR - Saving campaign: " . $campaign->campaign . ". <br>";
				continue;
			}
		}

		print "Ajillion: SUCCESS - Daily info downloaded. " . date('d-m-Y', strtotime($date)) . ". <br>";
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
			print "Ajillion: ERROR login <br>";
			return NULL;
		}

		if ( $login->error !== NULL ) {
			print "Ajillion: ERROR: - '". $login->error->message . "' <br>";
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
			print "Ajillion: ERROR - decoding json <br>";
			return NULL;
		}

		if ( $response->error !== NULL ) {
			print "Ajillion: ERROR - " . $response->error->message . "<br>";
			return NULL;	
		}

		if ( empty($response->result) ) {
			print "Ajillion: ERROR - json is empty <br>";
			return NULL;
		}

		curl_close($curl);
		return $response->result;
	}

}
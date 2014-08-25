<?php

class AjillionController extends Controller
{

	private $network_id = 3;

	public function actionIndex()
	{
		if ( isset( $_GET['date']) ) {
			$date = date_format( new DateTime($_GET['date']), "m/d/Y" );
		} else {
			// print "Parameter 'date' missing.";
			Yii::app()->end(1);
		}

		// validate if info have't been dowloaded already.
		if ( DailyReport::model()->exists("networks_id=:network AND DATE(date)=:date", array(":network"=>$this->network_id, ":date"=>$date)) ) {
			// print "Information already downloaded.";
			Yii::app()->end(2);
		}

		$advertisers = $this->getResponse("advertiser.get");
		if ( !$advertisers ) {
			// print "ERROR getting advertisers <br>";
			return;
		}

		// get campaigns from all advertisers
		foreach ($advertisers as $advertiser) {
			$params = array(
				"advertiser_ids"=>array($advertiser->id)
				);

			$campaigns = $this->getResponse("advertiser.campaign.get", $params);
			if ( !$campaigns ) {
				// print "ERROR getting campaigns <br>";
				continue;
			}

			foreach ($campaigns as $campaign) {
				$params = array(
						"columns"=>array("campaign"),
						"sums"=>array("hits", "revenue", "impressions", "conversions"),
						"campaign_ids"=>array($campaign->id), // "campaign_uids"=>array(25188),
						"advertiser_ids"=>array($advertiser->id),
						"start_date"=>$date,
						"end_date"=>$date
					);

				$campaign_info = $this->getResponse("advertiser_report", $params);
				if ( !$campaign_info ) {
					print "current campaign without info for the date specified <br>";
					continue;
				}

				// Save campaign information
				$dailyReport = new DailyReport();
				
				// get campaign ID used in KickAds Server, from the campaign name use in the external network
				$dailyReport->campaigns_id = Utilities::parseCampaignID($campaign->name);

				$dailyReport->networks_id = $this->network_id;
				$dailyReport->imp = $campaign_info[0]->impressions;
				$dailyReport->clics = $campaign_info[0]->hits;
				$dailyReport->conv_api = ConvLog::model()->count("campaign_id=:campaignid AND DATE(date)=:date", array(":campaignid"=>$dailyReport->campaigns_id, ":date"=>$date));
				$dailyReport->conv_adv = 0;
				$dailyReport->spend = $campaign_info[0]->revenue;
				$dailyReport->updateRevenue();
				$dailyReport->date = date_format( new DateTime($date), "Y-m-d" );
				if ( !$dailyReport->save() ) {
					// print "ERROR - saving campaign: " . $campaign->name . "<br>";
					continue;
				}
			} // --- end getting campaigns
		} // --- end getting advertirsers IDs.

		Yii::app()->end();
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
			// print "ERROR login Ajillion <br>";
			return NULL;
		}

		if ( $login->error !== NULL ) {
			// print "ERROR: '"; var_dump($login->error); print "' login Ajillion <br>";
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
			// print "ERROR decoding Ajillion json <br>";
			return NULL;
		}

		if ( $response->error !== NULL ) {
			// print "ERROR: '"; var_dump($response->error); print "' request Ajillion <br>";
			return NULL;	
		}

		if ( empty($response->result) ) {
			// print "ERROR Ajillion json is empty <br>";
			return NULL;
		}

		curl_close($curl);
		return $response->result;
	}

}
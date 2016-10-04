<?php

class Airpush
{ 

	private $provider_id = 294;
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

		$return = '';

		// Get json from Airpush API.
		$network = Providers::model()->findbyPk($this->provider_id);
		$apikey = $network->token1;
		$apiurl = $network->url;
		$url = $apiurl . "?apikey=" . $apikey . "&startDate=" . $date . "&endDate=" . $date;

		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		$result = curl_exec($curl);

		if (!isset($result)) {
			Yii::log("ERROR - decoding json", 'error', 'system.model.api.airpush');
			$return .= 'API ERROR';
			return $return;
		}

		$result = json_decode($result);

		if (!isset($result->advertiser_data)) {
			Yii::log("ERROR - decoding json", 'error', 'system.model.api.airpush');
			$return .= 'API ERROR: ';
			$return .= json_encode($result);
			return $return;
		}

		curl_close($curl);

		
		// Save campaigns information 
		foreach ($result->advertiser_data as $campaign) {

			if ( $campaign->impression == 0 && $campaign->clicks == 0) { // if no impressions dismiss campaign
				continue;
			}
		
			// if is vector
			if(substr($campaign->campaignname, 0, 1)=='v'){

				$vid = Utilities::parseVectorID($campaign->campaignname);
				$vectorModel = Vectors::model()->findByPk($vid);
				$return.= $campaign->campaignname;
				$return.= '<br>';
				$return.= $campaign->Spent;
				$return.= '<br>';

				$ret = $vectorModel->explodeVector(array('spend'=>$campaign->Spent,'date'=>$date));
				$return.= join($ret, ' - ');
				$return.= '<hr>';
				continue;
				$return .= json_encode($ret);

			}


			$campaigns_id = Utilities::parseCampaignID($campaign->campaignname);

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
				Yii::log("Invalid external campaign name: '" . $campaign->campaignname, 'warning', 'system.model.api.airpush');
				continue;
			}

			$dailyReport->date = $date;
			$dailyReport->providers_id = $this->provider_id;
			$dailyReport->imp = $campaign->impression;
			$dailyReport->clics = $campaign->clicks;
			$dailyReport->conv_api = ConvLog::model()->count("campaigns_id=:campaignid AND DATE(date)=:date", array(":campaignid"=>$dailyReport->campaigns_id, ":date"=>$date));
			//$dailyReport->conv_adv = 0;
			$dailyReport->spend = $campaign->Spent;
			$dailyReport->updateRevenue();
			$dailyReport->setNewFields();
			if ( !$dailyReport->save() ) {
				Yii::log("Can't save campaign: '" . $campaign->campaignname . "message error: " . json_encode($dailyReport->getErrors()), 'error', 'system.model.api.airpush');
				$return.= ' => ok';
				continue;
			}
		}
		
		Yii::log("SUCCESS - Daily info downloaded", 'info', 'system.model.api.airpush');
		return $return;
	}

}
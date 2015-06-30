 <?php

class AjillionPublisher
{ 

	private $network_id = 3;
	private $exchange_id = 1;

	public function downloadInfo()
	{
		$return = '';

		if ( isset( $_GET['date']) ) {
			$date = $_GET['date'];
		} else {
			$date = date('Y-m-d', strtotime('yesterday'));
		}

		// validate if info have't been dowloaded already.
		// if ( DailyReport::model()->exists("networks_id=:network AND DATE(date)=:date", array(":network"=>$this->network_id, ":date"=>$date)) ) {
		// 	Yii::log("Information already downloaded.", 'warning', 'system.model.api.ajillion');
		// 	return 2;
		// }

		$ajillionDate = date_format( new DateTime($date), "m/d/Y" ); // Ajillion api use mm/dd/YYYY date format
		
		// get all publishers
		$placements = $this->getResponse("publisher.placement.get");
		if ( !$placements ) {
			Yii::log("Can't get placements", 'error', 'system.model.api.ajillion');
			return 1;
		}

		$placements_ids = array();
		foreach ($placements as $placement) {
			$placements_ids[] = $placement->id;
		}
		// var_dump($placements_ids);
		// $return.=  "<hr/>";
		
		// generic //

		$params1 = array(
				"columns"       =>array("placement"),
				"sums"          =>array("impressions","profit","revenue","hits","conversions"),
				"publisher_ids" =>array(),
				"placement_ids" =>$placements_ids,
				"start_date"    =>$ajillionDate,
				"end_date"      =>$ajillionDate,
			);
		
		$data1 = $this->getResponse("report.publisher.get", $params1);
		
		foreach ($data1 as $line) {
			$placementID = substr($line->placement, 0, strpos($line->placement, '-'));
			if(is_numeric($placementID)){
				// $return.=  json_encode($line);
				// $return.=  "<br>";
				$placementsData[$placementID]['generic'] = $line;
			}
		}

		// by status //

		$params2 = array(
				"columns"       =>array("placement", "status"),
				"sums"          =>array("impressions"),
				"publisher_ids" =>array(),
				"placement_ids" =>$placements_ids,
				"start_date"    =>$ajillionDate,
				"end_date"      =>$ajillionDate,
			);
		$data2 = $this->getResponse("report.publisher.get", $params2);
		
		foreach ($data2 as $line) {
			$placementID = substr($line->placement, 0, strpos($line->placement, '-'));
			if(is_numeric($placementID)){
				// $return.=  json_encode($line);
				// $return.=  "<br>";
				$placementsData[$placementID][$line->status] = $line;
			}
		}

		// build data to dump //

		foreach ($placementsData as $key=>$value) {
			$return.=  $key."::";
			$return.=  json_encode($value);
			
			// validate placement
			$placementModel = Placements::model()->findByPk($key);
			if(!isset($placementModel)){
				$return.=  "<br/>===>PLACEMENT NOT FOUND!!<hr/>";
				continue;
			}

			// check for duplicates
			$dailyPublishers = DailyPublishers::model()->findByAttributes(array(
								'placements_id' => $key,
								'exchanges_id'  => $this->exchange_id,
								'date'          => $date
								));
			if(isset($dailyPublishers)){
				$return.=  "<br/>===>EXISTS!!<hr/>";
				continue;
			}

			$dailyPublishers = new DailyPublishers;

			$dailyPublishers->placements_id  = $key;
			$dailyPublishers->exchanges_id   = $this->exchange_id;
			$dailyPublishers->date           = $date;
			
			$dailyPublishers->ad_request     = $value['generic']->impressions;
			$dailyPublishers->revenue        = $value['generic']->revenue;

			if(isset($value['Delivered to exchange']))
				$dailyPublishers->imp_exchange   = $value['Delivered to exchange']->impressions;
			if(isset($value['Ad served successfully']))
				$dailyPublishers->imp_publishers = $value['Ad served successfully']->impressions;
			if(isset($value['Fallback tag displayed.']))
				$dailyPublishers->imp_passback   = $value['Fallback tag displayed.']->impressions;

			// var_dump($dailyPublishers);
			
			if($dailyPublishers->save())
				$return.=  "<br/>===>SAVED!!";
			else
				$return.=  "<br/>===>NOT SAVED: " . json_encode($dailyPublishers->getErrors());
			$return.=  "<hr/>";

		}
		
		$return.= "<hr/>ajillion publishers";
		Yii::log("SUCCESS - Daily info downloaded", 'info', 'system.model.api.ajillion');
		return $return;
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

		if ( isset($response->error) && $response->error !== NULL ) {
			Yii::log($response->error->message . " error", 'error', 'system.model.api.ajillion');
			return NULL;	
		}

		if ( empty($response->result) ) {
			Yii::log("Json is empty", 'error', 'system.model.api.ajillion');
			return NULL;
		}

		curl_close($curl);
		//die($json_response);
		return $response->result;
	}
}
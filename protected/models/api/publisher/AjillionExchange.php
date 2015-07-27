 <?php

class AjillionExchange
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
			return "Can't get placements";
		}

		$placements_ids = array();
		foreach ($placements as $placement) {
			// $return.=  json_encode($placement);
			// $return.=  "<br>";
			$placements_ids[] = $placement->id;
		}
		// var_dump($placements_ids);
		// $return.=  "<hr/>";
		
		// generic //

		$params1 = array(
				"columns"       => array("placement", "country"),
				"sums"          => array("impressions","profit","revenue","hits","conversions","ltv"),
				"publisher_ids" => array(),
				"placement_ids" => $placements_ids,
				"start_date"    => $ajillionDate,
				"end_date"      => $ajillionDate,
			);
		
		$data1 = $this->getResponse("report.publisher.get", $params1);
		
		foreach ($data1 as $line) {
			$placementID = substr($line->placement, 0, strpos($line->placement, '-'));
			if(is_numeric($placementID)){
				// $return.=  json_encode($line);
				// $return.=  "<br>";
				$placementsData[$placementID.'-'.$line->country]['generic'] = $line;
			}
		}
		
		// by status //

		$params2 = array(
				"columns"       => array("placement", "country", "status"),
				"sums"          => array("impressions"),
				"publisher_ids" => array(),
				"placement_ids" => $placements_ids,
				"start_date"    => $ajillionDate,
				"end_date"      => $ajillionDate,
			);
		$data2 = $this->getResponse("report.publisher.get", $params2);
		
		$unknownCountries = array();

		foreach ($data2 as $line) {
			$placementID = substr($line->placement, 0, strpos($line->placement, '-'));
			if(is_numeric($placementID)){
				// $return.=  json_encode($line);
				// $return.=  "<br>";
				$placementsData[$placementID.'-'.$line->country][$line->status] = $line;
				
				// list of country names
				if(!in_array($line->country, $unknownCountries))
	            	$unknownCountries[] = $line->country;		
			}
		}

		$countryCodeList = $this->getCountryCodes($unknownCountries);
		// $return.= "<hr/>Countries:<br/>".json_encode($countryCodeList)."<hr/>";

		// build data to dump //
		foreach ($placementsData as $key=>$value) {
			$return.=  $key."::";
			// $return.=  json_encode($value);

			$placementID  = substr($value['generic']->placement, 0, strpos($value['generic']->placement, '-'));
			$countryName  = $value['generic']->country;
			$return.= $countryName;
			$return.= '<br/>';
			
			if($countryName != ''){

				$return.= $countryCodeList[$countryName];
	            $countryModel = GeoLocation::model()->findByAttributes(array('ISO2'=>$countryCodeList[$countryName]));

	            if(isset($countryModel)){
	                $countryID = $countryModel->id_location;
					$return.= '<br/>==> '.$countryID.'<br/>';
	            }else{
	                $countryID = null;
					$return.= '<br/>==> not-match<br/>';
	            }

			}else{
                $countryID = null;
				$return.= '<br/>==> not-match<br/>';
            }

			// validate placement
			$placementModel = Placements::model()->findByPk($placementID);
			if(!isset($placementModel)){
				$return.=  "<br/>===>PLACEMENT NOT FOUND!!<hr/>";
				continue;
			}

			// check for duplicates
			$dailyPublishers = DailyPublishers::model()->findByAttributes(array(
								'placements_id' => $placementID,
								'exchanges_id'  => $this->exchange_id,
								'date'          => $date,
								'country_id'    => $countryID,
								));
			if(isset($dailyPublishers)){
				$return.=  "<br/>===>EXISTS!!<hr/>";
				continue;
			}

			$dailyPublishers = new DailyPublishers;

			$dailyPublishers->placements_id  = $placementID;
			$dailyPublishers->exchanges_id   = $this->exchange_id;
			$dailyPublishers->country_id     = $countryID;
			$dailyPublishers->date           = $date;
			
			$dailyPublishers->ad_request     = $value['generic']->impressions;
			$dailyPublishers->revenue        = $value['generic']->revenue;// + $value['generic']->profit;

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

	private function getCountryCodes($countryNames){
		$params = array(
			"search"=>$countryNames
			);
		$data = $this->getResponse("core.country.get", $params);
		foreach ($data as $key => $value) {
			$list[$value->name] = $value->country_code;
		}
		return $list;
	}


	private function getResponse($method, $params = array() ) {
		// Get json from Ajillion API.
		$network = Exchanges::model()->findbyPk($this->exchange_id);
		$apiurl = $network->api_url;
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
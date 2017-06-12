<?php

class Epom
{ 

	private $provider_id = 386;
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
				Yii::log("campaigns_id: $cid doesn't exists.", 'warning', 'system.model.api.epom');
				return 2;
			}
		}
		$formated_date = date_format( new DateTime($date), "Y-m-d" );
		// validate if info have't been dowloaded already.
		/*
		if ( isset($cid) )
			$alreadyExist = DailyReport::model()->exists("providers_id=:providers AND campaigns_id=:campaigns_id AND DATE(date)=:date", array(":providers"=>$this->provider_id, ":date"=>$date, ":campaigns_id" => $cid));
		else 
			$alreadyExist = DailyReport::model()->exists("providers_id=:providers AND DATE(date)=:date", array(":providers"=>$this->provider_id, ":date"=>$date));
		if ( $alreadyExist ) {
			Yii::log("Information already downloaded.", 'warning', 'system.model.api.epom');
			return 2;
		}
		*/
	
		/*
		$date = date_format( new DateTime($date), "m/d/Y" ); // Ajillion api use mm/dd/YYYY date format

		$this->apiLog->updateLog('Processing', 'Getting advertisers list');

		// if adv parameter is setted
		if (isset($fixed_adv)){ 

			$adv_ids = explode('-', $fixed_adv);
		
		}else{
		
			// get all advertisers
			$advertisers = $this->getResponse( );
			if ( !$advertisers ) {
				Yii::log("Can't get advertisers", 'error', 'system.model.api.epom');
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
		*/
		$this->apiLog->updateLog('Processing', 'Getting traffic data');

		$campaigns = $this->getResponse( [ 
			'groupBy' 	 => 'BANNER',
			'range'   	 => 'CUSTOM',
			'customFrom' => $formated_date,
			'customTo'	 => $formated_date
		]);

		//var_export($campaigns);
		if ( !$campaigns ) {
			Yii::log("Can't get campaigns", 'error', 'system.model.api.epom');
			return 1;
		}

		$this->apiLog->updateLog('Processing', 'Writing traffic data');

		$updated = 0;
		$prevSavedCmps = [];
		foreach ($campaigns as $campaign) {

			if ( $campaign->Impressions == 0 ) { // if no impressions dismiss campaign
				continue;
			}

			// get campaign ID used in Server, from the campaign name use in the external provider
			$campaigns_id = Utilities::parseCampaignID($campaign->Banner);

			if ( !$campaigns_id ) {
				Yii::log("invalid external campaign name: '" . $campaign->Banner, 'warning', 'system.model.api.epom');
				continue;
			}

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

			$sumRevenue = false;

			$dailyReport->imp = $campaign->Impressions;
			$dailyReport->clics = $campaign->Clicks;
			$dailyReport->conv_api = ConvLog::model()->count("campaigns_id=:campaignid AND DATE(date)=:date", array(":campaignid"=>$dailyReport->campaigns_id, ":date"=>date('Y-m-d', strtotime($date))));
			//$dailyReport->conv_adv = 0;
			$dailyReport->spend = number_format($campaign->Net, 2, '.', '');

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
			$return.='Campaign:'.$campaign->Banner.' - Impressions: '.$campaign->Impressions.' - Hits:'.$campaign->Clicks.' - Cost:'.$campaign->Net;
			$return.='<br/>';
			$return.='Nigma write: - Impressions: '.$dailyReport->imp.' - Clicks:'.$dailyReport->clics.' - Spend:'.$dailyReport->spend;

			if ( !$dailyReport->save() ) {
				Yii::log("Can't save campaign: '" . $campaign->Banner . "message error: " . json_encode($dailyReport->getErrors()), 'error', 'system.model.api.epom');
			}else{
				$prevSavedCmps[] = intval($campaigns_id);
				$updated++;
				$return.='<br/>===> saved';
			}

			if ( $returnAfterSave ) // return if only has to update one campaign
				break;
		}

		$this->apiLog->updateLog('Completed', 'Procces completed: '.$updated.' campaigns updated');

		Yii::log("SUCCESS - Daily info downloaded", 'info', 'system.model.api.epom');
		return $return;
	}

	public function compareTotals ( ) {
		date_default_timezone_set('UTC');
		$return = '';

		$date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d', strtotime('yesterday'));
		$this->apiLog = ApiLog::initLog($date, $this->provider_id, null);
		$return.= $this->downloadTotalsInfo($date);
		
		return $return;		
	}

	public function downloadTotalsInfo( $date )
	{
		$excludedAdvertisers = array( 12, 38, 30, 42, 43 );
		$return = "";
		$mailBody = "";
		$fixed_adv = isset($_GET['adv']) ? $_GET['adv'] : null;

		$formated_date = date_format( new DateTime($date), "m/d/Y" ); // Ajillion api use mm/dd/YYYY date format
		/*
		$this->apiLog->updateLog('Processing', 'Getting advertisers list');

		// get all advertisers
		$advertisers = $this->getResponse( [ 'groupBy' => 'ADVERTISER'] );

		if ( !$advertisers ) {
			Yii::log("Can't get advertisers", 'error', 'system.model.api.epom');
			return 1;
		}

		$adv_ids = array();
		foreach ($advertisers as $adv) {
			$adv_ids[] = $adv['Advertiser ID'];
		}

		// get totals from all advertisers
		$params = array(
				"columns"=>array("advertiser"),
				"sums"=>array("hits", "cost", "impressions", "conversions", "revenue"),
				"campaign_uids"=>array(),
				"advertiser_ids"=>$adv_ids,
				"start_date"=>$formated_date,
				"end_date"=>$formated_date,
			);

		$this->apiLog->updateLog('Processing', 'Getting traffic data');
		$return .= ' - Processing: Getting traffic data - <br><br><br>';
		*/
		$advReport = $this->getResponse( [ 'groupBy' => 'ADVERTISER'] );

		if ( !$advReport ) {
			Yii::log("Can't get advertisers", 'error', 'system.model.api.epom');
			return 1;
		}	

		foreach ( $advReport as $report ) {
			$report = (array)$report;

			$adv = Advertisers::model()->find(
					'ext_id LIKE :ext_id1 OR ext_id LIKE :ext_id1 OR ext_id LIKE :ext_id2',
					array(
						':ext_id0' => 'epo:'.$report['Advertiser ID'].'%',
						':ext_id1' => '%epo:'.$report['Advertiser ID'].'%',
						':ext_id2' => '%epo:'.$report['Advertiser ID'],
					)
				);

			$log = new AdvTotalsLog();
			$log->date = $date;			

			if ( !$adv ){

				$log->status = "notid";
				$log->message = "External ID not matched for advertiser: ".$report['Advertiser'];
				$return .= 'NOT FOUND - External ID not matched for advertiser:'.$report['Advertiser'].'<br>';

				if ( !in_array($report['Advertiser ID'], $excludedAdvertisers) ){
					$mailBody .= '
						<tr>
							<td>NOT FOUND IN NIGMA</td>
							<td>'.utf8_encode($report['Advertiser']).'</td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
						</tr>				
					';
				}
									
			}
			else
			{
				if ( in_array($adv->id, $excludedAdvertisers)  )
					continue;
				
				$log->advertiser = $adv->id;

				$totals = DailyReport::model()->advertiserSearchTotals( $adv->id, $date, $date, $this->provider_id );

				if ( $report['Net'] > $totals['spend'] )
				{
					$maxCost = $report['Net'];
					$minCost = $totals['spend'];
				}
				else
				{
					$maxCost = $totals['spend'];
					$minCost = $report['Net'];
				}

				if ( $report['Gross'] > $totals['revenue'] )
				{
					$maxRev = $report['Gross'];
					$minRev = $totals['revenue'];
				}
				else
				{

					$maxRev = $totals['revenue'];
					$minRev = $report['Gross'];
				}				

				if ( 
					$report['Impressions'] == $totals['imp'] 
					&& $maxCost-$minCost<1.00  
					&& $maxRev-$minRev<1.00  
				)
				{
					$log->status = "ok";
					$log->message = "Advertiser totals match.";
					$return .= 'OK - ' . $adv->name . '('.$adv->id.') - Nigma imps:'.$totals['imp'].'   Provider imps:'.$report['Impressions'].' - Nigma spend: '.$totals['spend'].'   Provider spend: '.$report['Net'].' - Nigma revenue: '.$totals['revenue'].'   Provider revenue: '.$report['Gross'].'<br>';
				}
				else
				{
					$log->status = "discrepancy";
					$log->message = "A discrepancy was found.";

					$return .= 'DISCREPANCY - ' . $adv->name . '('.$adv->id.') - Nigma imps:'.$totals['imp'].' / Provider imps:'.$report['Impressions'].' - Nigma spend: '.$totals['spend'].' / Provider spend: '.$report['Net'].' - Nigma revenue: '.$totals['revenue'].' / Provider revenue: '.$report['Gross'].'<br>';

					$mailBody .= '
						<tr>
							<td>DISCREPANCY</td>
							<td>'.utf8_encode($adv->name) . '('.utf8_encode($adv->id).')</td>
							<td>'.utf8_encode($totals['imp']).'</td>
							<td>'.utf8_encode($report['Impressions']).'</td>
							<td>'.utf8_encode($totals['revenue']).'</td>
							<td>'.utf8_encode($report['Gross']).'</td>
							<td>'.utf8_encode($totals['spend']).'</td>
							<td>'.utf8_encode($report['Net']).'</td>
						</tr>
					';
				}
			}

			$log->save();				


		}

		$this->apiLog->updateLog('Completed', 'Procces completed: advertisers totals compared.');
		$return .= '<br><br>Procces completed: advertisers totals succesfully compared.';

		if ( $mailBody!="" )
		{

			$d = date_format( new DateTime($date), "Y-m-d");
			$to = 'daniel@themedialab.co,chris@themedialab.co,matt@themedialab.co,pedro@themedialab.co,tom@themedialab.co,martin@themedialab.co';
			$from = 'Nigma<no-reply@tmlbox.co>';
			$subject = 'EPOM API Totals Compare from '.$d;
			$headers = 'From:'.$from.'\r\nMIME-Version: 1.0\r\nContent-Type: text/html; charset="UTF-8"\r\n';
			$mailBody = '
			<html>
				<head>
					<style>
						*{
							font-family:Arial;
							font-size:14px;
						}
						span{
							font-size:21px;
							color:black;							
						}
						table {
							border-collapse: collapse;							
						}
						table, td, th {
							border: 1px solid black;
						}
						th {
							font-weight:bolder;
						}
						td {
							padding:5px;
						}
						div{
							margin-top:25px;
							overflow-x:auto;
						}
					</style>
				</head>
				<body>
					<span>EPOM API TOTALS COMPARE FROM '.$d.'</span>
					<div>
						<table>
							<thead>
								<th>STATUS</th>
								<th>ADVERTISER</th>
								<th>NIGMA IMPS</th>
								<th>PROVIDER IMPS</th>
								<th>NIGMA REVENUE</th>
								<th>PROVIDER REVENUE</th>
								<th>NIGMA SPEND</th>
								<th>PROVIDER SPEND</th>
							</thead>
							<tbody>'.$mailBody.'</tbody>
						</table>
					</div>
				</body>
			</html>
			';

			$return .= '<br><br>mail notification status: ';
			if ( mail($to, $subject, $mailBody, $headers ) )
			{
				$return .= 'sent';
			}
			else
			{
				$data = 'To: '.$to.'\nSubject: '.$subject.'\nFrom:'.$from.'\n'.$mailBody;
				$command = 'echo -e "'.$data.'" | sendmail -bm -t -v';
				$command = '
					export MAILTO="'.$to.'"
					export FROM="'.$from.'"
					export SUBJECT="'.$subject.'"
					export BODY="'.$mailBody.'"
					(
					 echo "From: $FROM"
					 echo "To: $MAILTO"
					 echo "Subject: $SUBJECT"
					 echo "MIME-Version: 1.0"
					 echo "Content-Type: text/html; charset=UTF-8"
					 echo $BODY
					) | /usr/sbin/sendmail -F $MAILTO -t -v -bm
				';
				$r = shell_exec( $command );

				$return .= $r;				
			}	
		}

		return $return;		
	}


	private function getResponse( array $params = [] ) {

		// Get json from Epom API.
		$network 	= Providers::model()->findbyPk($this->provider_id);
		$timestamp  = round(microtime(true) * 1000);
		$hash 		= md5(md5($network->token2).$timestamp);

		$apiurl = preg_replace(
			[
				'/(\{format\})/',
				'/(\{login\})/',
				'/(\{hash\})/',
				'/(\{timestamp\})/'
			],
			[
				'JSON',
				$network->token1,
				$hash,
				$timestamp
			],
			$network->url
		);


		$data = array(
			"range"		 => "TODAY", 
			"groupBy"	 => "CAMPAIGN", 
			"displayIds" => true
		);

		$apiurl .= '?'.http_build_query(array_merge($data, $params) );

		$this->apiLog->updateLog('Processing', 'Authenticating credentials');

		$curl = curl_init($apiurl);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-type: application/json-rpc"));
		curl_setopt($curl, CURLOPT_POST, true);

		//var_export($apiurl);
		curl_setopt( $curl, CURLOPT_POSTFIELDS, http_build_query($data) );
		$r = curl_exec($curl);
		$response = json_decode($r);

		if ( !$response ) {
			Yii::log("Error decoding json", 'error', 'system.model.api.epom');
			return NULL;
		}
		if (  isset($response->rest_error) && $response->rest_error !== NULL ) {
			Yii::log($response->rest_error . " error", 'error', 'system.model.api.epom');
			return NULL;	
		}

		if ( empty($response) ) {
			Yii::log("Json is empty", 'error', 'system.model.api.epom');
			return NULL;
		}

		curl_close($curl);
		return $response;
	}
}
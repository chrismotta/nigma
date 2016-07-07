<?php

class AdWords
{  

	private $provider_id = 4;
	private $apiLog;
	private $adWords_version = 'v201605';

	public function downloadInfo($offset)
	{
		date_default_timezone_set('UTC');
		$return = '';

		if ( isset( $_GET['date']) ) {
			$return.= '<hr/>date '.$_GET['date'].'<hr/>';
			$date =  date('Ymd', strtotime($_GET['date']));
			$this->apiLog = ApiLog::initLog($date, $this->provider_id, null);
			$return.= $this->downloadInfoByAccount('auth.ini', $date);
		
		} else {

			if(date('G')<=$offset){
				$return.= '<hr/>yesterday<hr/>';
				$date = date('Ymd', strtotime('yesterday'));
				$this->apiLog = ApiLog::initLog($date, $this->provider_id, null);
				$return.= $this->downloadInfoByAccount('auth.ini', $date);
			}
			//default
			$return.= '<hr/>today<hr/>';
			$date = date('Ymd', strtotime('today'));
			$this->apiLog = ApiLog::initLog($date, $this->provider_id, null);
			$return.= $this->downloadInfoByAccount('auth.ini', $date);
		
		}

		return $return;
	}

	public function downloadInfoByAccount($authenticationIniPath, $date)
	{
		$return = '';
		$this->apiLog->updateLog('Processing', 'Getting MCC list');

		Yii::import('application.external.Google.Api.Ads.AdWords.Lib.AdWordsUser');
		$authPath = Yii::app()->basePath . '/external/Google/Api/Ads/AdWords/';
		$user = new AdWordsUser($authPath . $authenticationIniPath);
				
		/*
		$user->SetClientCustomerId('915-331-6649');

		// Get all client customers ids
		$customerService = $user->GetService('ManagedCustomerService', $this->adWords_version);

		$selector = new Selector;
		$selector->fields = array('CustomerId', 'Name');

		$advertisers = $customerService->get($selector);
		*/
		
		$this->apiLog->updateLog('Processing', 'Getting advertisers list');
		
		$accounts = Providers::model()->findAllByAttributes(array(
			'type' => 'Google AdWords',
			// 'name' => 'CL-CLARO',//harcode
			),
			'mcc_external_id IS NOT NULL'
			);

		$this->apiLog->updateLog('Processing', 'Getting traffic data');
		
		// process every advertiser
		foreach ($accounts as $advertiser) {

			// echo $advertiser->id . ': ' . $advertiser->name;
			// echo '<hr>';

			// Get campaigns info
			$user->SetClientCustomerId($advertiser->mcc_external_id);
			$user->LoadService('ReportDefinitionService', $this->adWords_version);

			// Url report query.
			$reportQuery = 'SELECT EffectiveTrackingUrlTemplate, Impressions, Clicks, Cost FROM FINAL_URL_REPORT WHERE Impressions > 0 DURING ' . sprintf('%d,%d', $date, $date);
			
			// Ad report query.
			// $reportQuery = 'SELECT CreativeTrackingUrlTemplate, Impressions, Clicks, Cost FROM AD_PERFORMANCE_REPORT WHERE Impressions > 0 DURING ' . sprintf('%d,%d', $date, $date);

			// Campaign report query.
			// $reportQuery = 'SELECT CampaignId, CampaignName, Impressions, Clicks, Cost FROM CAMPAIGN_PERFORMANCE_REPORT WHERE Impressions > 0 DURING ' . sprintf('%d,%d', $date, $date);

			$result = ReportUtils::DownloadReportWithAwql($reportQuery, NULL, $user, 'XML', array('version' => $this->adWords_version));

			$result = Utilities::xml2array($result);
			
			// echo json_encode($result);
			// echo '<hr>';

			// build campaigns table //
			
			if(isset($result['report']['table']['row']))
				$row = $result['report']['table']['row'];
			else
				continue;

			// awCampaign or list of awCampaign //
			
			if ( isset($row['attr']) ) {
				$awCampaigns[] = $row['attr'];
			}else{
				foreach ($row as $campaignRow) {
					$awCampaigns[] = $campaignRow['attr'];
				}
			}
			
			
			
			/*
			if ( !isset($result['report']['table']['row']) ) {
				Yii::log("Empty daily report, advertiser:  " . $advertiser->name, 'info', 'system.model.api.adWords');
				continue;
			}


			if ( isset($result['report']['table']['row']['attr']) ) {
				$this->inspectCampaign($result['report']['table']['row']['attr'], $date);
				continue;
			}

			// process every campaign
			foreach ($result['report']['table']['row'] as $campaign) {
				if ( $this->inspectCampaign($campaign['attr'], $date) === NULL )
					continue;
			}
			echo '<hr>';
			*/
		
		}

		// echo '<hr>';
		// echo json_encode($awCampaigns);
		// echo '<hr>';
		
		$return .= $this->parseResult($awCampaigns, $date);

		return $return;

	}


	// csv format: date,vectorURL,clicks,cost
	public function loadCsv($csv){

		$return = '';

		$return .= $csv;
		$return .=  '<hr>';

		$csv = explode("\r\n", $csv);

		foreach ($csv as $line) {
			$csvLine = str_getcsv($line);
			$csvArray[$csvLine[0]][] = array(
				'trackingTemplate' => $csvLine[1],
				'impressions' => 0,
				'clicks' => $csvLine[2],
				'cost' => $csvLine[3] * 1000000,
				);
		}

		$return .= json_encode($csvArray);
		$return .= '<hr>';

		foreach ($csvArray as $date => $awCampaigns) {
			
			$this->apiLog = ApiLog::initLog($date, $this->provider_id, null);

			$return .=  $date;
			$return .= '<br>';
			$return .= json_encode($awCampaigns);
			$return .= '<hr>';
			$return .= $this->parseResult($awCampaigns, $date);
			$return .= '<hr>';
		}

		return $return;
	}

	private function parseResult($awCampaigns, $date){

		$return = '';

		$this->apiLog->updateLog('Processing', 'Merginh data');
		
		// vector or campaign //
		
		if(isset($awCampaigns)){

			foreach ($awCampaigns as $campaignAttr) {
			
				if( $this->isVectorURL($campaignAttr['trackingTemplate']) ){

					$vid = Utilities::parseVectorUrlID($campaignAttr['trackingTemplate']);
					$costUS = number_format($campaignAttr['cost'] / 1000000, 2, '.', '');

					if(isset($vcTable[$vid])){

						$vcTable[$vid]['impressions'] += $campaignAttr['impressions'];
						$vcTable[$vid]['clicks']      += $campaignAttr['clicks'];
						$vcTable[$vid]['cost']        += $campaignAttr['cost'];
						$vcTable[$vid]['us']          += $costUS;
						$vcTable[$vid]['count']       ++;

					}else{

						$vcTable[$vid]['id']          = $vid;
						$vcTable[$vid]['impressions'] = $campaignAttr['impressions'];
						$vcTable[$vid]['clicks']      = $campaignAttr['clicks'];
						$vcTable[$vid]['cost']        = $campaignAttr['cost'];
						$vcTable[$vid]['us']          = $costUS;
						$vcTable[$vid]['count']       = 1;

					}

					$return .= $vid;
					$return .= ': ';
					$return .= json_encode($vcTable[$vid]);
					$return .= '<br>';
					
					// $vcTable[$campaignAttr['campaignID']]['campaign'] = $campaignAttr['campaign'];
					// $vcTable[$campaignAttr['campaignID']]['impressions'] = $campaignAttr['impressions'];
					// $vcTable[$campaignAttr['campaignID']]['clicks']      = $campaignAttr['clicks'];
					// $vcTable[$campaignAttr['campaignID']]['cost']        = $cost;

				}else{
					$campaignsTable[] = $campaignAttr;
				}

			}
		}

		// if(isset($campaignTable))
		// 	echo 'Campaigns Table<hr>'.json_encode($campaignsTable, JSON_PRETTY_PRINT);
			
		// if(isset($vcTable)){
		// 	$return .= '<hr>';
		// 	$return .= 'Vectors Table<br>';
		// 	$return .= json_encode($vcTable, JSON_PRETTY_PRINT);
		// 	$return .= '<hr>';
		// }

		$this->apiLog->updateLog('Processing', 'Assigning costs');
		
		// process for each vector //
		
		$cpTable = array();
		foreach ($vcTable as $vid => $vector) {

			$curCpTable = $this->explodeVector($vector, $date);
			// $return .= var_export($curCpTable, true);
			// $return .= '<hr>';
			// continue;

			if(isset($curCpTable)){

				// sum each campaign in the vector to the campaigns list //
				foreach ($curCpTable as $cid => $camp) {

					if(isset($cpTable[$cid])){
						$cpTable[$cid]['clicks'] += $camp['clicks'];
						$cpTable[$cid]['conv']   += $camp['conv'];
						$cpTable[$cid]['cost']   += $camp['cost'];
					}else{
						$cpTable[$cid]['clicks'] = $camp['clicks'];
						$cpTable[$cid]['conv']   = $camp['conv'];
						$cpTable[$cid]['cost']   = $camp['cost'];
						$cpTable[$cid]['id']     = $cid;
						
					}
				}
			}
		}

		// echo json_encode($cpTable, JSON_PRETTY_PRINT);
		// echo '<hr>';

		$this->apiLog->updateLog('Processing', 'Writing traffic data');

		// inserting values //
		foreach ($cpTable as $cid => $camp) {
			$return .= $this->createDaily($camp, $date);
			$return .= '<br/>';
		}

		
		$this->apiLog->updateLog('Completed', 'Procces completed: '.count($cpTable).' campaigns updated');

		Yii::log("SUCCESS - Daily info download", 'info', 'system.model.api.adWords');
		return $return;
	}

	private function isVector($campaignName){

		return preg_match('/^v|^V/', $campaignName);

	}

	private function isVectorURL($campaignURL){
		
		return preg_match('/clicklog\/v\//', $campaignURL);

	}

	private function explodeVector($vector, $date){

		$vhc = VectorsHasCampaigns::model()->findAll('vectors_id=:vid', 
			array(':vid'=>$vector['id']));

		$totalClicks = 0;
		$totalConv = 0;

		foreach ($vhc as $cmp) {

			$cid = $cmp->campaigns_id;
			// echo 'Campaign '.$cid.'<br>';

			$criteria = new CDbCriteria;
			$criteria->with = array('clicksLog', 'clicksLog.convLogs', 'clicksLog.campaigns.opportunities');
			$criteria->compare('t.vectors_id', $vector['id']);
			$criteria->compare('clicksLog.campaigns_id', $cid);
			$criteria->compare('opportunities.wifi', 'Specific Carrier');
			$criteria->addCondition('DATE(clicksLog.date) = "' . $date . '"');//check
			$criteria->select = array(
				'COUNT(t.id) AS clicks',
				'COUNT(convLogs.id) AS conv',
				);
			$model = VectorsLog::model()->find($criteria);

			// var_dump($model);
			// echo '<br>';
			
			if($model){

				$campaignsList[$cid]['clicks'] = $model->clicks;
				$campaignsList[$cid]['conv'] = $model->conv;
				$totalClicks += $campaignsList[$cid]['clicks'];
				$totalConv += $campaignsList[$cid]['conv'];

			}
		}

		if(isset($campaignsList)){

			/* deprecated 
			// cost related to conversions
			foreach ($campaignsList as $id => $cmp) {
				if($totalConv * $cmp['conv'] > 0)
					$campaignsList[$id]['cost'] = $vector['cost'] / $totalConv * $cmp['conv'];
				else
					$campaignsList[$id]['cost'] = 0;
			}
			*/

			// cost related to clicks
			foreach ($campaignsList as $id => $cmp) {
				if($totalClicks * $cmp['clicks'] > 0)
					$campaignsList[$id]['cost'] = $vector['cost'] / $totalClicks * $cmp['clicks'];
				else
					$campaignsList[$id]['cost'] = 0;
			}
			
			// echo json_encode($campaignsList);
			// echo '<br>';
			// echo 'TOTAL: '.$totalConv.' conv - '.$vector['cost'].' us micropound';
			// echo '<hr>';
			return $campaignsList;
		}

		return null;

	}

	private function createDaily($camp, $date)
	{
		// echo 'Creating daily - date: '.$date.' cid: '.$camp.'<br>';
		$return = '';
		$campModel = Campaigns::model()->findByPk($camp['id']);
		
		// if exists overwrite, else create a new
		$dailyReport = DailyReport::model()->find(
			"providers_id=:providers AND DATE(date)=:date AND campaigns_id=:cid", 
			array(
				":providers"=>$campModel->providers_id, 
				":cid"=>$camp['id'],
				":date"=>$date, 
				)
			);

		if(!$dailyReport){
			$dailyReport = new DailyReport();
			$dailyReport->date = $date;
			$dailyReport->campaigns_id = $camp['id'];
			$dailyReport->providers_id = $campModel->providers_id;
			$return.= "<hr/>New record: ";
		}else{
			$return.= "<hr/>Update record: ".$dailyReport->id;
		}
		
		
		if ( !$dailyReport->campaigns_id ) {
			Yii::log("Invalid external campaign name: '" . $campaign['campaign'], 'warning', 'system.model.api.adWords');
			return NULL;
		}

		$dailyReport->date = date( 'Y-m-d', strtotime($date) );
		$dailyReport->imp = 0;
		$dailyReport->clics = $camp['clicks'];
		$dailyReport->conv_api = $camp['conv'];
		
		// cost is return in micropound, why? google, why? 
		$dailyReport->spend = number_format($camp['cost'] / 1000000, 2, '.', '');

		$dailyReport->updateRevenue();
		$dailyReport->setNewFields();
		
		if ( !$dailyReport->save() ) {
			Yii::log("Can't save campaign: '" . $camp['id'] . "message error: " . json_encode($dailyReport->getErrors()), 'error', 'system.model.api.adWords');
			$return .= '<br>'.json_encode($dailyReport->getErrors());
		} else {
			$return .= '<br>=> ok';
		}

		
		return $return;
	}

	public function uploadConversions(){

		$return = '<hr>Uploading Conversions<hr>';

		Yii::import('application.external.Google.Api.Ads.AdWords.Lib.AdWordsUser');

		// Find all conversions from yesterday and today

		$criteria = new CDbCriteria;
		$criteria->compare('reported', 0);
		$criteria->compare('providers.type', 'Google AdWords');
		$criteria->addCondition('clicksLog.ext_tid IS NOT NULL');
		$criteria->addCondition('DATE(t.date) >= SUBDATE(CURDATE(),1)');

		$criteria->with = array('clicksLog', 'clicksLog.providers', 'campaign.opportunities');
		$criteria->select = array(
			// 't.date AS date',
			't.date AS conversion_time',
			'providers.conversion_profile AS conversion_name',
			'providers.mcc_external_id AS mcc_external_id',
			'opportunities.rate AS conversion_value',
			'clicksLog.ext_tid AS google_click_id',
			't.*',
			);

		$convList = ConvLog::model()->findAll($criteria);

		// var_dump($convList);die();

		// Get AdWordsUser from credentials in "../auth.ini"
		// relative to the AdWordsUser.php file's directory.
		$authPath = Yii::app()->basePath . '/external/Google/Api/Ads/AdWords/';
		$user = new AdWordsUser($authPath . 'auth.ini');

		foreach ($convList as $id => $conv) {

			try {

				$user->SetClientCustomerId($conv['mcc_external_id']);
				
				// Log every SOAP XML request and response.
				// $user->LogAll();
				
				// Run the method.
				// conversion_time string format: 'yyyyMMdd HHmmss tz'
				$return .= $this->UploadOfflineConversionsExample(
					$user, 
					$conv['conversion_name'], 
					$conv['google_click_id'], 
					date('Ymd His +0000',strtotime($conv['conversion_time'])), 
					$conv['conversion_value']
					);

				$conv->reported = 1;
				if(!$conv->save())
					$return .= '<br>'.json_encode($conv->getErrors());

				$return .= '<hr>';

			} catch (OAuth2Exception $e) {
				ExampleUtils::CheckForOAuth2Errors($e);
			} catch (ValidationException $e) {
				ExampleUtils::CheckForOAuth2Errors($e);
			} catch (Exception $e) {
				$return .= sprintf("An error has occurred: %s\n", $e->getMessage());
				$return .= '<hr>';
			}

		}


		return $return;
	}


	/**
	* Runs the example.
	* @param AdWordsUser $user the user to run the example with
	* @param string $campaignId the ID of the campaign to add the sitelinks to
	*/
	private function UploadOfflineConversionsExample(AdWordsUser $user, $conversionName, $gclid, $conversionTime, $conversionValue) {
		
		// Get the services, which loads the required classes.
		$offlineConversionService = $user->GetService('OfflineConversionFeedService', $this->adWords_version);
		
		// Associate offline conversions with the existing named conversion tracker.
		// If this tracker was newly created, it may be a few hours before it can
		// accept conversions.
		$feed = new OfflineConversionFeed();
		$feed->conversionName = $conversionName;
		$feed->conversionTime = $conversionTime;
		$feed->conversionValue = $conversionValue;
		$feed->googleClickId = $gclid;
		$offlineConversionOperation = new OfflineConversionFeedOperation();
		$offlineConversionOperation->operator = 'ADD';
		$offlineConversionOperation->operand = $feed;
		$offlineConversionOperations = array($offlineConversionOperation);
		$result = $offlineConversionService->mutate($offlineConversionOperations);
		$feed = $result->value[0];
		
		return sprintf('Uploaded offline conversion value of %d for Google Click ID = ' . "'%s' to '%s'.", $feed->conversionValue, $feed->googleClickId, $feed->conversionName);
	
	}

}
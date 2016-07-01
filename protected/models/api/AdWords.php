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
			$return.= '<hr/>'.$_GET['date'].'<hr/>';
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
			
			$this->apiLog->updateLog('Processing', 'Merginh data');
			
			// vector or campaign //
			
			if(isset($awCampaigns)){

				foreach ($awCampaigns as $campaignAttr) {
				
					if( $this->isVectorURL($campaignAttr['trackingTemplate']) ){

						$vid = Utilities::parseVectorUrlID($campaignAttr['trackingTemplate']);
						$cost = number_format($campaignAttr['cost'] / 1000000, 2, '.', '');

						if(isset($vcTable[$vid])){

							$vcTable[$vid]['impressions'] += $campaignAttr['impressions'];
							$vcTable[$vid]['clicks']      += $campaignAttr['clicks'];
							$vcTable[$vid]['cost']        += $campaignAttr['cost'];
							$vcTable[$vid]['count']       ++;

						}else{

							$vcTable[$vid]['id']          = $vid;
							$vcTable[$vid]['impressions'] = $campaignAttr['impressions'];
							$vcTable[$vid]['clicks']      = $campaignAttr['clicks'];
							$vcTable[$vid]['cost']        = $campaignAttr['cost'];
							$vcTable[$vid]['count']       = 1;

						}
						
						// $vcTable[$campaignAttr['campaignID']]['campaign'] = $campaignAttr['campaign'];
						// $vcTable[$campaignAttr['campaignID']]['impressions'] = $campaignAttr['impressions'];
						// $vcTable[$campaignAttr['campaignID']]['clicks']      = $campaignAttr['clicks'];
						// $vcTable[$campaignAttr['campaignID']]['cost']        = $cost;

					}else{
						$campaignsTable[] = $campaignAttr;
					}

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

		// if(isset($campaignTable))
		// 	echo 'Campaigns Table<hr>'.json_encode($campaignsTable, JSON_PRETTY_PRINT);
			
		// if(isset($vcTable)){
		// 	// echo 'Vectors Table<hr>';
		// 	echo json_encode($vcTable, JSON_PRETTY_PRINT);
		// 	echo '<hr>';
		// }

		$this->apiLog->updateLog('Processing', 'Assigning costs');
		
		// process for each vector //
		
		$cpTable = array();
		foreach ($vcTable as $vid => $vector) {

			$curCpTable = $this->explodeVector($vector, $date);
			// echo json_encode($cpTable, JSON_PRETTY_PRINT);
			// echo '<hr>';
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

		Yii::log("SUCCESS - Daily info download - ".$authenticationIniPath, 'info', 'system.model.api.adWords');
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

		// echo 'Vector '.$vector['id'].'<br>';
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
			$criteria->addCondition('DATE(clicksLog.date) = ' . $date);
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
		$return = '';
		
		// if exists overwrite, else create a new
		$dailyReport = DailyReport::model()->find(
			"providers_id=:providers AND DATE(date)=:date AND campaigns_id=:cid", 
			array(
				":providers"=>$this->provider_id, 
				":cid"=>$camp['id'],
				":date"=>$date, 
				)
			);

		if(!$dailyReport){
			$dailyReport = new DailyReport();
			$dailyReport->date = $date;
			$dailyReport->campaigns_id = $camp['id'];
			$dailyReport->providers_id = $this->provider_id;
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
}
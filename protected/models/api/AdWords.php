<?php

class AdWords
{  

	private $provider_id = 4;
	private $adWords_version = 'v201406';

	public function downloadInfo()
	{
		if ( isset( $_GET['date']) ) {
			$date =  date('Ymd', strtotime($_GET['date']));
		} else {
			$date = date('Ymd', strtotime('yesterday'));
		}

		// validate if info have't been dowloaded already.
		if ( DailyReport::model()->exists("providers_id=:providers AND DATE(date)=:date", array(":providers"=>$this->provider_id, ":date"=>$date)) ) {
			Yii::log("Information already downloaded.", 'warning', 'system.model.api.adWords');
			return 2;
		}

		$this->downloadInfoByAccount('auth_adwords1.ini', $date);
		$this->downloadInfoByAccount('auth_adwords2.ini', $date);

		return 0;
	}

	public function downloadInfoByAccount($authenticationIniPath, $date)
	{

		Yii::import('application.external.Google.Api.Ads.AdWords.Lib.AdWordsUser');
		$authPath = Yii::app()->basePath . '/external/Google/Api/Ads/AdWords/';
		$user = new AdWordsUser($authPath . $authenticationIniPath);

		// Get all client customers ids
		$customerService = $user->GetService('ManagedCustomerService', $this->adWords_version);

		$selector = new Selector;
		$selector->fields = array('CustomerId', 'Name');

		$advertisers = $customerService->get($selector);

		// process every advertiser
		foreach ($advertisers->entries as $advertiser) {

			// Get campaigns info
			$user->SetClientCustomerId($advertiser->customerId);
			$user->LoadService('ReportDefinitionService', $this->adWords_version);

			// Create report query.
			$reportQuery = 'SELECT CampaignName, Impressions, Clicks, Cost FROM CAMPAIGN_PERFORMANCE_REPORT WHERE Impressions > 0 DURING ' . sprintf('%d,%d', $date, $date);

			$result = ReportUtils::DownloadReportWithAwql($reportQuery, NULL, $user, 'XML', array('version' => $this->adWords_version));

			$result = Utilities::xml2array($result);

			if ( !isset($result['report']['table']['row']) ) {
				Yii::log("Empty daily report, advertiser:  " . $advertiser->name, 'info', 'system.model.api.adWords');
				continue;
			}

			if ( isset($result['report']['table']['row']['attr']) ) {
				$this->createDaily($result['report']['table']['row']['attr'], $date);
				continue;
			}

			// process every campaign
			foreach ($result['report']['table']['row'] as $campaign) {
				if ( $this->createDaily($campaign['attr'], $date) === NULL )
					continue;
			}
		}
		
		Yii::log("SUCCESS - Daily info download - ".$authenticationIniPath, 'info', 'system.model.api.adWords');
		return 0;
	}


	private function createDaily($campaign, $date)
	{
		$dailyReport = new DailyReport();
				
		// get campaign ID used in Server, from the campaign name use in the external provider
		$dailyReport->campaigns_id = Utilities::parseCampaignID($campaign['campaign']);

		if ( !$dailyReport->campaigns_id ) {
			Yii::log("Invalid external campaign name: '" . $campaign['campaign'], 'warning', 'system.model.api.adWords');
			return NULL;
		}

		$dailyReport->date = date( 'Y-m-d', strtotime($date) );
		$dailyReport->providers_id = $this->provider_id;
		$dailyReport->imp = $campaign['impressions'];
		$dailyReport->clics = $campaign['clicks'];
		$dailyReport->conv_api = ConvLog::model()->count("campaigns_id=:campaignid AND DATE(date)=:date", array(":campaignid"=>$dailyReport->campaigns_id, ":date"=>$date));
		//$dailyReport->conv_adv = 0;
		// cost is return in micropound, why? google, why? seguramente habia que comittear gil 
		$dailyReport->spend = number_format($campaign['cost'] / 1000000, 2, '.', ''); // ignore thousands separetor to save properly in db.
		$dailyReport->updateRevenue();
		$dailyReport->setNewFields();
		if ( !$dailyReport->save() ) {
			Yii::log("Can't save campaign: '" . $campaign['campaign'] . "message error: " . json_encode($dailyReport->getErrors()), 'error', 'system.model.api.adWords');
			return NULL;
		} 
		return $dailyReport;
	}
}
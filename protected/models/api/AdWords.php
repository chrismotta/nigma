<?php

class AdWords
{  

	private $network_id = 4;
	private $adWords_version = 'v201406';

	public function downloadInfo()
	{

		if ( isset( $_GET['date']) ) {
			$date =  date('Ymd', strtotime($_GET['date']));
		} else {
			$date = date('Ymd', strtotime('yesterday'));
		}

		// validate if info have't been dowloaded already.
		if ( DailyReport::model()->exists("networks_id=:network AND DATE(date)=:date", array(":network"=>$this->network_id, ":date"=>$date)) ) {
			print "AdWords: WARNING - Information already downloaded. <br>";
			return 2;
		}

		Yii::import('application.external.Google.Api.Ads.AdWords.Lib.AdWordsUser');
		$user = new AdWordsUser();

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
				print "AdWords: INFO - empty daily report, advertiser:  " . $advertiser->name . "<br>";
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
		
		print "AdWords: SUCCESS - Daily info download. " . date('d-m-Y', strtotime($date)) . ". <br>";
		return 0;
	}


	private function createDaily($campaign, $date)
	{
		$dailyReport = new DailyReport();
				
		// get campaign ID used in KickAds Server, from the campaign name use in the external network
		$dailyReport->campaigns_id = Utilities::parseCampaignID($campaign['campaign']);

		if ( !$dailyReport->campaigns_id ) {
			print "AdWords: ERROR - invalid external campaign name: '" . $campaign['campaign'] . "' <br>";
			return NULL;
		}

		$dailyReport->networks_id = $this->network_id;
		$dailyReport->imp = $campaign['impressions'];
		$dailyReport->clics = $campaign['clicks'];
		$dailyReport->conv_api = ConvLog::model()->count("campaign_id=:campaignid AND DATE(date)=:date", array(":campaignid"=>$dailyReport->campaigns_id, ":date"=>$date));
		$dailyReport->conv_adv = 0;
		// cost is return in micropound, why? google, why?
		$dailyReport->spend = number_format($campaign['cost'] / 1000000, 2, '.', ''); // ignore thousands separetor to save properly in db.
		$dailyReport->updateRevenue();
		$dailyReport->date = date( 'Y-m-d', strtotime($date) );
		if ( !$dailyReport->save() ) {
			print json_encode($dailyReport->getErrors()) . "<br>";
			print "AdWords: ERROR - saving campaign: " . $campaign['campaign'] . "<br>";
			return NULL;
		} 
		return $dailyReport;
	}
}
<?php

class AffiliatesAPI
{ 

	public function downloadInfo()
	{
		if ( isset( $_GET['date']) ) {
			$date = $_GET['date'];
		} else {
			$date = date('Y-m-d', strtotime('yesterday'));
		}

		$affiliates = Affiliates::model()->findAll();

		// Download api for every affiliate
		foreach ($affiliates as $affiliate) {
			$provider = Providers::model()->findByPk($affiliate->providers_id);

			// validate if info have't been dowloaded already.
			if ( DailyReport::model()->exists("providers_id=:providers AND DATE(date)=:date", array(":providers"=>$affiliate->providers_id, ":date"=>$date)) ) {
				Yii::log("Information already downloaded.", 'warning', 'system.model.api.affiliate.' . $provider->affiliates->name);
				continue;
			}

			$campaigns = Campaigns::model()->findAll( 'providers_id=:pid', array(':pid' => $affiliate->providers_id) );

			foreach ($campaigns as $campaign) {
				$conv   = ConvLog::model()->count("campaign_id=:cid AND DATE(date)=:date", array(':date'=>$date, ":cid"=>$campaign->id));
				$clicks = ClicksLog::model()->count("campaigns_id=:cid AND DATE(date)=:date", array(':date'=>$date, ":cid"=>$campaign->id));

				if ($conv == 0 && $clicks == 0)
					continue;

				$dailyReport               = new DailyReport();
				$dailyReport->campaigns_id = $campaign->id;
				$dailyReport->date         = $date;
				$dailyReport->providers_id = $affiliate->providers_id;
				$dailyReport->imp          = 0;
				$dailyReport->clics        = $clicks;
				$dailyReport->conv_api     = $conv;
				$dailyReport->spend        = $dailyReport->conv_api * $affiliate->rate;
				$dailyReport->updateSpendAffiliates();
				$dailyReport->updateRevenue();
				$dailyReport->setNewFields();
				if ( !$dailyReport->save() ) {
					Yii::log("Can't save campaign: '" . $campaign->name . "message error: " . json_encode($dailyReport->getErrors()), 'error', 'system.model.api.affiliate.' . $provider->affiliates->name);
					continue;
				}
			}
			Yii::log("SUCCESS - Daily info downloaded", 'info', 'system.model.api.affiliate.' . $provider->affiliates->name);
		}
		Yii::log("SUCCESS - Daily info downloaded for all affiliates", 'info', 'system.model.api.affiliate');
		return 0;
	}

}
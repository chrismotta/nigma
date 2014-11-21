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
			$network = Networks::model()->findByPk($affiliate->networks_id);

			// validate if info have't been dowloaded already.
			if ( DailyReport::model()->exists("networks_id=:network AND DATE(date)=:date", array(":network"=>$affiliate->networks_id, ":date"=>$date)) ) {
				Yii::log("Information already downloaded.", 'warning', 'system.model.api.affiliate.' . $network->name);
				continue;
			}

			$campaigns = Campaigns::model()->findAll( 'networks_id=:nid', array(':nid' => $affiliate->networks_id) );

			foreach ($campaigns as $campaign) {
				$dailyReport               = new DailyReport();
				$dailyReport->campaigns_id = $campaign->id;
				$dailyReport->date         = $date;
				$dailyReport->networks_id  = $affiliate->networks_id;
				$dailyReport->imp          = 0;
				$dailyReport->clics        = ClicksLog::model()->count("campaigns_id=:cid AND DATE(date)=:date", array(':date'=>$date, ":cid"=>$campaign->id));
				$dailyReport->conv_api     = ConvLog::model()->count("campaign_id=:cid AND DATE(date)=:date", array(':date'=>$date, ":cid"=>$campaign->id));
				$dailyReport->spend        = $dailyReport->conv_api * $affiliate->rate;
				$dailyReport->updateRevenue();
				$dailyReport->setNewFields();
				if ( !$dailyReport->save() ) {
					Yii::log("Can't save campaign: '" . $campaign->name . "message error: " . json_encode($dailyReport->getErrors()), 'error', 'system.model.api.affiliate.' . $network->name);
					continue;
				}
			}
			Yii::log("SUCCESS - Daily info downloaded", 'info', 'system.model.api.affiliate.' . $network->name);
		}
		Yii::log("SUCCESS - Daily info downloaded for all affiliates", 'info', 'system.model.api.affiliate');
		return 0;
	}

}
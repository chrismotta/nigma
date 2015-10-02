<?php

class AffiliatesAPI
{ 

	public function downloadInfo()
	{
		$return = '';

		if ( isset( $_GET['date']) ) {
			$date = $_GET['date'];
		} else {
			$date = date('Y-m-d', strtotime('yesterday'));
		}

		$fixedRate = isset($_GET['rate']) ? $_GET['rate'] : null;
		$fixedCid  = isset($_GET['cid']) ? $_GET['cid'] : null;

		$affiliates = Affiliates::model()->findAll();

		// Download api for every affiliate
		foreach ($affiliates as $affiliate) {
			$provider = Providers::model()->findByPk($affiliate->providers_id);

			// validate if info have't been dowloaded already.
			if ( DailyReport::model()->exists("providers_id=:providers AND DATE(date)=:date", array(":providers"=>$affiliate->providers_id, ":date"=>$date)) ) {
				Yii::log("Information already downloaded.", 'warning', 'system.model.api.affiliate.' . $provider->name);
				continue;//comment for debug
			}

			// if ($provider->prospect != 10) {
			// 	Yii::log("Affiliate " . $provider->name . " hasn't prospect 10", 'warning', 'system.model.api.affiliate.' . $provider->name);
			// 	continue;	
			// }

			$cpCriteria = new CDbCriteria;
			$cpCriteria->compare('providers_id',$affiliate->providers_id);
			if(isset($fixedCid)) $cpCriteria->compare('id',$fixedCid);
			$campaigns = Campaigns::model()->findAll($cpCriteria);

			foreach ($campaigns as $campaign) {
				// $conv   = ConvLog::model()->count("campaigns_id=:cid AND DATE(date)=:date", array(':date'=>$date, ":cid"=>$campaign->id));
				// $clicks = ClicksLog::model()->count("campaigns_id=:cid AND DATE(date)=:date", array(':date'=>$date, ":cid"=>$campaign->id));
				
				$testSource = 29;
				
				$clicksCriteria = new CDbCriteria;
				$clicksCriteria->compare('DATE(date)',$date);
				$clicksCriteria->compare('campaigns_id',$campaign->id);
				$clicksCriteria->compare('providers_id','<>'.$testSource);
				$clicks = ClicksLog::model()->count($clicksCriteria);
				
				$convCriteria = new CDbCriteria;		
				$convCriteria->compare('DATE(t.date)',$date);
				$convCriteria->compare('t.campaigns_id',$campaign->id);
				$convCriteria->with = array('clicksLog');
				$convCriteria->compare('clicksLog.providers_id','<>'.$testSource);
				$conv = ConvLog::model()->count($convCriteria);

				if ($conv == 0 && $clicks == 0)
					continue;

				$dailyReport               = new DailyReport();
				$dailyReport->campaigns_id = $campaign->id;
				$dailyReport->date         = $date;
				$dailyReport->providers_id = $affiliate->providers_id;
				$dailyReport->imp          = 0;
				$dailyReport->clics        = $clicks;
				$dailyReport->conv_api     = $conv;
				
				$dailyReport->updateRevenue();
				$dailyReport->setNewFields();
				$dailyReport->updateSpendAffiliates($fixedRate);
				$return.= $affiliate->providers_id.'::'.$campaign->id .' - '.$clicks.' - '.$conv.' - '.$dailyReport->spend.'<br/>';
				// continue;// uncomment for debug

				if ( !$dailyReport->save() ) {
					Yii::log("Can't save campaign: '" . $campaign->name . "message error: " . json_encode($dailyReport->getErrors()), 'error', 'system.model.api.affiliate.' . $provider->name);
					continue;
				}
			}
			Yii::log("SUCCESS - Daily info downloaded", 'info', 'system.model.api.affiliate.' . $provider->name);
		}
		Yii::log("SUCCESS - Daily info downloaded for all affiliates", 'info', 'system.model.api.affiliate');
		return $return;
	}

}
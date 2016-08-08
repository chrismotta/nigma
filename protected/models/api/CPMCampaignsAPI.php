<?php

class CPMCampaignsAPI
{ 
	private $apiLog;

	public function downloadInfo($offset)
	{
		date_default_timezone_set('UTC');
		$return = '';
		$fixedRate = isset($_GET['rate']) ? $_GET['rate'] : null;
		$fixedCid  = isset($_GET['cid']) ? $_GET['cid'] : null;

		if ( isset( $_GET['date']) ) {
		
			$date = $_GET['date'];
			$return.= $this->downloadDateInfo($date);
		
		} else {

			if(date('G')<=$offset){
				$return.= '<hr/>yesterday<hr/>';
				$date = date('Y-m-d', strtotime('yesterday'));
				$return.= $this->downloadDateInfo($date);
			}
			//default
			$return.= '<hr/>today<hr/>';
			$date = date('Y-m-d', strtotime('today'));
			$return.= $this->downloadDateInfo($date);
		
		}

		return $return;
	}

	public function downloadDateInfo($date)
	{
		$return = '';
		$testSource = 29;


		$criteria = new CDbCriteria;
		$criteria->with = array('campaigns.opportunities', 'providers');
		$criteria->select = array('t.campaigns_id AS campaign', 't.providers_id AS provider', 'COUNT(t.id) AS clicks');
		$criteria->compare('DATE(t.date)',$date);
		$criteria->compare('opportunities.model_adv', 'CPM');
		$criteria->compare('providers.type', array('Publisher','Network'));
		$criteria->compare('providers.has_api', '0');
		$criteria->group = 't.campaigns_id';

		$clicks = ClicksLog::model()->findAll($criteria);
		// return json_encode($campaigns, JSON_PRETTY_PRINT);

		// -- //

		$updated = 0;

		foreach ($clicks as $c) {
							
			$this->apiLog = ApiLog::initLog($date, $c->provider, null);
			$this->apiLog->updateLog('Processing', 'Calculating traffic data');

			// if exists overwrite, else create a new
			$dailyReport = DailyReport::model()->find(
				"providers_id=:providers AND DATE(date)=:date AND campaigns_id=:cid", 
				array(
					":providers"=>$c->provider, 
					":date"=>$date, 
					":cid"=>$c->campaign,
					)
				);
			if(!$dailyReport)
				$dailyReport = new DailyReport();
			
			$dailyReport->campaigns_id = $c->campaign;
			$dailyReport->date         = $date;
			$dailyReport->providers_id = $c->provider;
			$dailyReport->imp          = $c->clicks;
			$dailyReport->clics        = $c->clicks;
			$dailyReport->conv_api     = 0;
			$dailyReport->spend        = 0;
			$dailyReport->updateRevenue();
			$dailyReport->setNewFields();
			
			if ( !$dailyReport->save() ) {
				$return.="Can't save campaign: '" . $c->campaign . "message error: " . json_encode($dailyReport->getErrors());
				Yii::log("Can't save campaign: '" . $c->campaign . "message error: " . json_encode($dailyReport->getErrors()), 'error', 'system.model.api.cpmCampaigns.');
				continue;
			}
			$updated++;

			Yii::log("SUCCESS - Daily info downloaded", 'info', 'system.model.api.cpmCampaigns.');
			$this->apiLog->updateLog('Completed', 'Procces completed: '.$updated.' campaigns updated');
		}

		// -- //

		$return.="SUCCESS - Daily info downloaded for all cpm campaigns<br/>";
		Yii::log("SUCCESS - Daily info downloaded for all cpm campaigns", 'info', 'system.model.api.cpmCampaigns');
		return $return;
	}

}
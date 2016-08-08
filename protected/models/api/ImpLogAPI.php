<?php

class ImpLogAPI
{ 
	private $apiLog;

	public function downloadInfo($offset)
	{
		date_default_timezone_set('UTC');
		$return = '';
		// $fixedRate = isset($_GET['rate']) ? $_GET['rate'] : null;
		// $fixedCid  = isset($_GET['cid']) ? $_GET['cid'] : null;

		if ( isset( $_GET['date']) ) {
		
			$date = $_GET['date'];
			$return.= '<hr/>'.$date.'<hr/>';
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

		$criteria = new CDbCriteria;
		$criteria->with = array('tags','placements.sites','dBid');
		$criteria->compare('DATE(t.date_time)',$date);
		$criteria->group = 'tags.campaigns_id, sites.providers_id';
		$criteria->select = array(
			'tags.campaigns_id AS campaign', 
			'sites.providers_id AS provider',
			'count(t.id) AS impressions', 
			'sum(dBid.revenue) AS revenue',
			'sum(dBid.cost) AS cost',
			);

		$impressions = FImpressions::model()->findAll($criteria);
		// return json_encode($impressions, JSON_PRETTY_PRINT);

		// -- //

		$updated = 0;

		foreach ($impressions as $c) {
							
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
			if(!$dailyReport){
				$dailyReport = new DailyReport();
				$return.='New: ';
			}else{
				$return.='Update: ';
			}
			
			$dailyReport->campaigns_id = $c->campaign;
			$dailyReport->date         = $date;
			$dailyReport->providers_id = $c->provider;
			$dailyReport->imp          = $c->impressions;
			$dailyReport->clics        = 0;
			$dailyReport->conv_api     = 0;
			$dailyReport->spend        = $c->cost;
			$dailyReport->revenue      = $c->revenue;
			// $dailyReport->updateRevenue();
			$dailyReport->setNewFields();
			
			if ( !$dailyReport->save() ) {
				$return.="Can't save campaign: '" . $c->campaign . "message error: " . json_encode($dailyReport->getErrors());
				Yii::log("Can't save campaign: '" . $c->campaign . "message error: " . json_encode($dailyReport->getErrors()), 'error', 'system.model.api.cpmCampaigns.');
				continue;
			}else{
				$return.='Campaign: '.$c->campaign.' - Traffic Source: '.$c->provider.' - Impressions: '.$c->impressions.' - Revennue: '.$c->revenue.' - Cost: '.$c->cost.'<br/>';
			}
			$updated++;

			Yii::log("SUCCESS - Daily info downloaded", 'info', 'system.model.api.cpmCampaigns.');
			$this->apiLog->updateLog('Completed', 'Procces completed: '.$updated.' campaigns updated');
		}

		// -- //

		$return.="<hr/>SUCCESS - Daily info downloaded for all cpm campaigns<br/>";
		Yii::log("SUCCESS - Daily info downloaded for all cpm campaigns", 'info', 'system.model.api.cpmCampaigns');
		return $return;
	}

}
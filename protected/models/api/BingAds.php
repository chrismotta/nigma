<?php

class BingAds
{ 
	private $network_id = 20;

	public function downloadInfo()
	{
		echo "ERROR - Not implemented. <br>";

		if ( isset( $_GET['date']) ) {
			$date = $_GET['date'];
		} else {
			$date = date('Y-m-d', strtotime('yesterday'));
		}
		// validate if info have't been dowloaded already.
		if ( DailyReport::model()->exists("networks_id=:network AND DATE(date)=:date", array(":network"=>$this->network_id, ":date"=>$date)) ) {
			Yii::log("Information already downloaded.", 'warning', 'system.model.api.bingAds');
			return 2;
		}

		// FIXME falla en ClientProxy.php al definicion del namespace y los use es correcta?
		Yii::import('application.external.bing.PHP.bingads.*');

		// Config values
		$network        = Networks::model()->findbyPk($this->network_id);
		$wsdl           = $network->url;
		$UserName       = $network->token1;
		$Password       = $network->token2;
		$DeveloperToken = $network->token3;

		try {
			$proxy = ClientProxy::ConstructWithCredentials($wsdl, $UserName, $Password, $DeveloperToken, null);
		
			// Set date range for report
		    $report->Aggregation = ReportAggregation::Daily;
			$report->Time = new ReportTime();
			$timestamp = strtotime($date);
			$date = new Date(date("d", $timestamp), date("m", $timestamp), date("Y", $timestamp));
			$report->Time->CustomDateRangeStart = $date;
			$report->Time->CustomDateRangeEnd   = $date;

			// Set columns for report
		    $report = new CampaignPerformanceReportRequest();
		    $report->Columns = array(
		    	CampaignPerformanceReportRequest::CampaignName,
		    	CampaignPerformanceReportRequest::Impressions,
		    	CampaignPerformanceReportRequest::Clicks,
		    	CampaignPerformanceReportRequest::Spend,
		    	);

		    $encodedReport = new SoapVar($report, SOAP_ENC_OBJECT, 'CampaignPerformanceReportRequest', $proxy->GetNamespace());

		    // Set the request information.
		    $request = new SubmitGenerateReportRequest();
		    $request->ReportRequest = $encodedReport;
		    $reportRequestId = $proxy->GetService()->SubmitGenerateReport($request)->ReportRequestId;

		    // Wait for reponse
		    $waitTime = 60 * 1; 
	    	$reportRequestStatus = null;
		    for ($i = 0; $i < 10; $i++) {
		    	sleep($waitTime);
		    
		    	// PollGenerateReport helper method calls the corresponding Bing Ads service operation
		    	// to get the report request status.
		    	
		    	$request = new PollGenerateReportRequest();
			    $request->ReportRequestId = $reportRequestId;

			    $reportRequestStatus = $proxy->GetService()->PollGenerateReport($request)->ReportRequestStatus;
		    
		    	if ($reportRequestStatus->Status == ReportRequestStatusType::Success ||
		    		$reportRequestStatus->Status == ReportRequestStatusType::Error) {
		    		break;
		    	}
		    }

		    if ($reportRequestStatus != null) {	    	
		    	if ($reportRequestStatus->Status == ReportRequestStatusType::Success) {
		    		Yii::log("Processing response %s.\n\n", 'info', 'system.model.api.bingAds');
		    	} else if ($reportRequestStatus->Status == ReportRequestStatusType::Error) {
		    		Yii::log("The request failed. Try requesting the report "."later.\nIf the request continues to fail, contact support.\n", 'error', 'system.model.api.bingAds');
		    	}
		    } else { // Pending 
	    		Yii::log("The request is taking longer than expected.\n " . $reportRequestId, 'error', 'system.model.api.bingAds');
	    	}
		} catch (Exception $e) {
	        Yii::log($e->getCode()." ".$e->getMessage()."\n\n".$e->getTraceAsString()."\n\n", 'error', 'system.model.api.bingAds');
		}
	}
}

?>

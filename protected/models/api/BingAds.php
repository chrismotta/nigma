<?php

// Include the Bing Ads namespaced class files available  
// for download at http://go.microsoft.com/fwlink/?LinkId=322147
Yii::import('application.external.bing.PHP.bingads.ReportingClasses', true);
Yii::import('application.external.bing.PHP.bingads.ClientProxy', true);


// Specify the BingAds\Reporting objects that will be used.
// use BingAds\Reporting;
use BingAds\Reporting\CampaignPerformanceReportRequest;
use BingAds\Reporting\CampaignPerformanceReportColumn;
use BingAds\Reporting\AccountThroughCampaignReportScope;
use BingAds\Reporting\ReportAggregation;
use BingAds\Reporting\ReportFormat;
use BingAds\Reporting\ReportTime;
use BingAds\Reporting\Date;
use BingAds\Reporting\SubmitGenerateReportRequest;
use BingAds\Reporting\PollGenerateReportRequest;
use BingAds\Reporting\ReportRequestStatusType;
use BingAds\Reporting\ReportTimePeriod;

// Specify the BingAds\Proxy object that will be used.
use BingAds\Proxy\ClientProxy;


class BingAds
{ 
	private $network_id = 20;
	private $path = 'uploads/';
	private $name = 'API_BingAds_DailyReport.zip';

	public function downloadInfo()
	{
		if ( isset( $_GET['date']) ) {
			$dateGet = $_GET['date'];
		} else {
			$dateGet = date('Y-m-d', strtotime('yesterday'));
		}
		// validate if info have't been dowloaded already.
		if ( DailyReport::model()->exists("networks_id=:network AND DATE(date)=:date", array(":network"=>$this->network_id, ":date"=>$dateGet)) ) {
			Yii::log("Information already downloaded.", 'warning', 'system.model.api.bingAds');
			return 2;
		}

		// Disable WSDL caching.
		ini_set("soap.wsdl_cache_enabled", "0");
		ini_set("soap.wsdl_cache_ttl", "0");

		// Config values
		$network         = Networks::model()->findbyPk($this->network_id);
		$tmp             = explode(',', $network->token1);
		$ClientID        = $tmp[0];
		$ClientSecretKey = $tmp[1];
		$wsdl            = $network->url;
		$DeveloperToken  = $network->token2;
		$RefreshToken    = $network->token3;

		$AuthorizationToken = $this->getAuthorizationToken($ClientID, $ClientSecretKey, $RefreshToken);
		if ( !$AuthorizationToken )
			return;

		try {
			$proxy = ClientProxy::ConstructWithCredentials($wsdl, NULL, NULL, $DeveloperToken, $AuthorizationToken);

			// Set configs for report
			$report                         = new CampaignPerformanceReportRequest();
			$report->Format                 = ReportFormat::Csv;
			$report->ReportName             = 'API BingAds DailyReport';
			$report->ReturnOnlyCompleteData = true;
			$report->Aggregation            = ReportAggregation::Daily;
			$report->Time                   = new ReportTime();

			$timestamp = strtotime($dateGet);
			$report->Time->CustomDateRangeStart        = new Date();
			$report->Time->CustomDateRangeStart->Day   = (int)date("d", $timestamp);
			$report->Time->CustomDateRangeStart->Month = (int)date("m", $timestamp);
			$report->Time->CustomDateRangeStart->Year  = (int)date("Y", $timestamp);
			$report->Time->CustomDateRangeEnd          = new Date();
			$report->Time->CustomDateRangeEnd->Day     = (int)date("d", $timestamp);
			$report->Time->CustomDateRangeEnd->Month   = (int)date("m", $timestamp);
			$report->Time->CustomDateRangeEnd->Year    = (int)date("Y", $timestamp);

			$report->Scope = new AccountThroughCampaignReportScope();

			// Set columns for report
		    $report->Columns = array(
		    	CampaignPerformanceReportColumn::CampaignName,
		    	CampaignPerformanceReportColumn::Impressions,
		    	CampaignPerformanceReportColumn::Clicks,
		    	CampaignPerformanceReportColumn::Spend,
		    	);

		    $encodedReport = new SoapVar($report, SOAP_ENC_OBJECT, 'CampaignPerformanceReportRequest', $proxy->GetNamespace());

		    // Set the request information.			
		    $request = new SubmitGenerateReportRequest();
		    $request->ReportRequest = $encodedReport;
		    $reportRequestId = $proxy->GetService()->SubmitGenerateReport($request)->ReportRequestId;

		    // Wait for reponse
		    $waitTime = 30; // seg
	    	$reportRequestStatus = NULL;
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

		    if ( !$reportRequestStatus) {
		    	Yii::log("The request is taking longer than expected.<br> " . $reportRequestId, 'error', 'system.model.api.bingAds');
	        	return 1;
	        }

	        if ($reportRequestStatus->Status == ReportRequestStatusType::Error) {
				Yii::log("The request failed. Try requesting the report "."later.<br>If the request continues to fail, contact support.<br>", 'error', 'system.model.api.bingAds');
				return 1;
			}

	    	if ($reportRequestStatus->Status == ReportRequestStatusType::Success) {
	    		$reportDownloadUrl = $reportRequestStatus->ReportDownloadUrl;

				if ( $this->downloadFile($reportDownloadUrl) == 0)
					$this->processReportFile($dateGet);
	    	}

		} catch (SoapFault $e)
		{
			// Output the last request/response.

			// print "<br>Last SOAP request/response:<br>";
			// print $proxy->GetWsdl() . "<br>";
			// print $proxy->GetService()->__getLastRequest()."<br>";
			// print $proxy->GetService()->__getLastResponse()."<br>";
			 
			// Reporting service operations can throw AdApiFaultDetail.
			if (isset($e->detail->AdApiFaultDetail))
			{
				// Log this fault.

				Yii::log("The operation failed with the following faults:<br>", 'error', 'system.model.api.bingAds');

				$errors = is_array($e->detail->AdApiFaultDetail->Errors->AdApiError)
				? $e->detail->AdApiFaultDetail->Errors->AdApiError
				: array('AdApiError' => $e->detail->AdApiFaultDetail->Errors->AdApiError);

				// If the AdApiError array is not null, the following are examples of error codes that may be found.
				foreach ($errors as $error)
				{
					// print "AdApiError<br>";
					Yii::log("Code: ". $error->Code . "<br>Error Code: " . $error->ErrorCode . "<br>Message: " . $error->Message . "<br>", 'error', 'system.model.api.bingAds');
					// printf("Code: %d<br>Error Code: %s<br>Message: %s<br>", $error->Code, $error->ErrorCode, $error->Message);

					switch ($error->Code)
					{
						case 0:    // InternalError
							break;
						case 105:  // InvalidCredentials
							break;
						default:
							// print "Please see MSDN documentation for more details about the error code output above.<br>";
							break;
					}
				}
			}

			// Reporting service operations can throw ApiFaultDetail.
			elseif (isset($e->detail->ApiFaultDetail))
			{
				// Log this fault.

				Yii::log("The operation failed with the following faults:<br>", 'error', 'system.model.api.bingAds');

				// If the BatchError array is not null, the following are examples of error codes that may be found.
				if (!empty($e->detail->ApiFaultDetail->BatchErrors))
				{
					$errors = is_array($e->detail->ApiFaultDetail->BatchErrors->BatchError)
					? $e->detail->ApiFaultDetail->BatchErrors->BatchError
					: array('BatchError' => $e->detail->ApiFaultDetail->BatchErrors->BatchError);

					foreach ($errors as $error)
					{
						Yii::log("BatchError at Index: ". $error->Index . "<br>", 'error', 'system.model.api.bingAds');
						Yii::log("Code: ". $error->Code . "<br>Error Code: " . $error->ErrorCode . "<br>Message: " . $error->Message . "<br>", 'error', 'system.model.api.bingAds');
						// printf("BatchError at Index: %d<br>", $error->Index);
						// printf("Code: %d<br>Error Code: %s<br>Message: %s<br>", $error->Code, $error->ErrorCode, $error->Message);

						switch ($error->Code)
						{
							case 0:     // InternalError
								break;
							default:
								// print "Please see MSDN documentation for more details about the error code output above.<br>";
								break;
						}
					}
				}

				// If the OperationError array is not null, the following are examples of error codes that may be found.
				if (!empty($e->detail->ApiFaultDetail->OperationErrors))
				{
					$errors = is_array($e->detail->ApiFaultDetail->OperationErrors->OperationError)
					? $e->detail->ApiFaultDetail->OperationErrors->OperationError
					: array('OperationError' => $e->detail->ApiFaultDetail->OperationErrors->OperationError);

					foreach ($errors as $error)
					{
						Yii::log("OperationError", 'error', 'system.model.api.bingAds');
						Yii::log("Code: ". $error->Code . "<br>Error Code: " . $error->ErrorCode . "<br>Message: " . $error->Message . "<br>", 'error', 'system.model.api.bingAds');
						// print "OperationError<br>";
						// printf("Code: %d<br>Error Code: %s<br>Message: %s<br>", $error->Code, $error->ErrorCode, $error->Message);

						switch ($error->Code)
						{
							case 0:     // InternalError
								break;
							case 106:   // UserIsNotAuthorized
								break;
							case 2100:  // ReportingServiceInvalidReportId
								break;
							default:
								// print "Please see MSDN documentation for more details about the error code output above.<br>";
								break;
						}
					}
				}
			}
		} catch (Exception $e)
		{
		    if ($e->getPrevious())
		    {
		        ; // Ignore fault exceptions that we already caught.
		    }
		    else
		    {
		    	Yii::log($e->getCode()." ".$e->getMessage(), 'error', 'system.model.api.bingAds');
		    	Yii::log($e->getTraceAsString(), 'error', 'system.model.api.bingAds');
		        // print $e->getCode()." ".$e->getMessage()."<br><br>";
		        // print $e->getTraceAsString()."<br><br>";
		    }
		}
	}


	public function getAuthorizationToken($ClientID, $ClientSecretKey, $RefreshToken)
	{
		$url = "https://login.live.com/oauth20_token.srf?client_id=" . $ClientID . "&client_secret=" . $ClientSecretKey . "&grant_type=refresh_token&redirect_uri=http://kickadserver.mobi/apiUpdate/bingCode&refresh_token=" . $RefreshToken;

		$curl   = curl_init($url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		$result = curl_exec($curl);
		$result = json_decode($result);
		if (!$result) {
			Yii::log("ERROR - getting AuthorizationToken", 'error', 'system.model.api.bingAds');
			return NULL;
		}
		if ( isset($result->error) ) {
			Yii::log("ERROR - getting AuthorizationToken, error: " . $result->error . ", message: " . $result->error_description, 'error', 'system.model.api.bingAds');
			return NULL;
		}

		curl_close($curl);

		$network         = Networks::model()->findbyPk($this->network_id);
		$network->token3 = $result->refresh_token;

		if ( !$network->save() ) {
			Yii::log("ERROR - updating AuthorizationToken, message: " . json_encode($network->getErrors()), 'error', 'system.model.api.bingAds');
			return NULL;
		}
		
		return $result->access_token;
	}


	// Using the URL that the PollGenerateReport operation returned,
	// send an HTTP request to get the report and write it to the specified
	// ZIP file.
	private function downloadFile($url)
	{
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_SSLVERSION,3);
		$file = curl_exec($curl);

		if ($file === false) {
			Yii::log("ERROR - downloading report file, message: " .  curl_error($curl), 'error', 'system.model.api.bingAds');
			return 1;
		}

		$fp = fopen($this->path . $this->name, 'w');
		fwrite($fp, $file);
		fclose($fp);
		return 0;

	}


	private function processReportFile($date)
	{
		// Unzip file
		$zip = new ZipArchive;
		if ( $zip->open($this->path . $this->name) == false ) {
			Yii::log("ERROR - unzipping file report", 'error', 'system.model.api.bingAds');
			return;
		}
	    $zip->extractTo($this->path . "zip");
	    $zip->close();

	    // Get file contents
		$file = scandir($this->path . "zip/", 1);
		$csv  = file_get_contents($this->path . "zip/" . $file[0]);
		
		// Parse csv file
		$array = array_map("str_getcsv", explode("\n", $csv));
		foreach ($array as $row) {
			// only process files with campaign info, discard header and footer info.
			if ( count($row) == 4 && $row[0] != 'CampaignName' ) {

				if ( $row[1] == 0 && $row[2] == 0) { // if no impressions dismiss campaign
					continue;
				}

				$dailyReport = new DailyReport();
				
				// get campaign ID used in KickAds Server, from the campaign name use in the external network
				$dailyReport->campaigns_id = Utilities::parseCampaignID($row[0]);
$dailyReport->campaigns_id = 11;

				if ( !$dailyReport->campaigns_id ) {
					Yii::log("Invalid external campaign name: '" . $row[0], 'error', 'system.model.api.bingAds');
					continue;
				}

				$dailyReport->date = $date;
				$dailyReport->networks_id = $this->network_id;
				$dailyReport->imp = $row[1];
				$dailyReport->clics = $row[2];
				$dailyReport->conv_api = ConvLog::model()->count("campaign_id=:campaignid AND DATE(date)=:date", array(":campaignid"=>$dailyReport->campaigns_id, ":date"=>$date));
				//$dailyReport->conv_adv = 0;
				$dailyReport->spend = $row[3];
				$dailyReport->updateRevenue();
				$dailyReport->setNewFields();
				if ( !$dailyReport->save() ) {
					Yii::log("Can't save campaign: '" . $row[0] . "message error: " . json_encode($dailyReport->getErrors()), 'error', 'system.model.api.bingAds');
					continue;
				}
			}
		}
		unlink($this->path . 'zip/' . $file[0]);
		unlink($this->path . $this->name);
		Yii::log("SUCCESS - Daily info downloaded", 'info', 'system.model.api.bingAds');
	}
}

?>

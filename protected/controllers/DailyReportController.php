<?php

class DailyReportController extends Controller
{
	public function actionIndex()
	{

		$yesterday = date("Y-m-d", strtotime("yesterday"));
		$today = date("Y-m-d H:i:s", strtotime("today"));

		//$this->render('index');
		echo "controller de daily report - airpush";
		echo "<hr/>";

		$cron_log = ApiCronLog::model()->findByAttributes(
			array('networks_id'=>1), 
			array(
		        'condition'=>'date >= "'.$today.'"'
		    )
		);
		var_dump($today);
		echo "<hr/>";
		var_dump($cron_log);
		echo "<hr/>";

		if(!$cron_log){
			$cron_log = new ApiCronLog();
			$cron_log->networks_id = 1;
			$cron_log->save();

			$url = "http://openapi.airpush.com/getAdvertiserReports";
			$params = array("apikey"=>"n6r4jf4nwtb8es5gw3xx3mkn", "startDate"=>$yesterday, "endDate"=>$yesterday);
			$output = Yii::app()->curl->setOptions(array(CURLOPT_HEADER=>false))->get($url, $params);

			$jsonData = json_decode($output);

			//var_dump($jsonData);
			//echo "<hr/>";

			foreach ($jsonData->advertiser_data as $key => $value) {
				//var_dump($value);

				if ($value->campaignstatus == "Active") {

					$model = new DailyReport();

					
					echo $value->campaignid;
					echo " - ";
					echo $value->campaignname;
					echo " - Impressions: ";
					echo $value->impression;
					echo " - Clicks: ";
					echo $value->clicks;
					echo " - Spent: ";
					echo $value->Spent;
					echo " - CTR: ";
					echo $value->ctr;

					echo "<hr/>";

					$model->campaigns_id = $value->campaignid;
					$model->networks_id = 2;
					$model->imp = (int)$value->impression;
					$model->clics = (int)$value->clicks;
					$model->spend = $value->Spent;


					$model->save();
				}
			}

		}
	}

	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array/			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
}
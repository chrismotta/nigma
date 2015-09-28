<?php

class ConvlogController extends Controller
{

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}
	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',
				'actions'=>array('index', 'excelReport'),
				'users'=>array('*'),
			),
			array('allow',
				'actions'=>array('storage'),
				'ips'=>array('54.88.85.63'),
			),
			array('allow', 
				'actions'=>array('storage'),
				'roles'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Print daily conversions usin EExcelWriter
	 * @return excel file
	 */
	public function actionExcelReport()
	{
		if ( isset($_GET["date"]) ) {
			$date = $_GET["date"];
		} else {
			$date = 'yesterday';
		}

		if( isset($_GET["apiKey"]) ) {

			$validApiKey = ApiKey::model()->findByAttributes(array('key' => $_GET["apiKey"]));

			if(isset($validApiKey)){

				$model = new ConvLog;
				$model->advertiser_id = $validApiKey->advertisers_id;
				$model->date = date_format( new DateTime($date), "Y-m-d" );

				$this->renderPartial('excelReport',array(
					'model' => $model,
				));
		
			} else {
				echo 'Incorrect Token';
			}
		} else {
			echo 'Access Denied';
		}

	}

	/**
	 * Record a conversion stamp
	 * @return [type] [description]
	 */
	public function actionIndex()
	{
		/*

		$gc_callback = "http://www.googleadservices.com/pagead/";
		$gc_callback.= "conversion/970651684/";
		$gc_callback.= "?label=wAWJCLyWhQoQpPDrzgM";
		$gc_callback.= "&guid=ON&script=0";

		$gc_callback = "http://localhost/nigma/appserver/conv.php?mytoken=".$_GET['mytoken'];
		//print $gc_callback ."<hr/>";
		// Crea un nuevo recurso cURL
		$curl = curl_init();

		// Establece la URL y otras opciones apropiadas
		curl_setopt($curl, CURLOPT_URL, $gc_callback);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

		// Captura la URL y la envía al navegador
		$exec = curl_exec($curl);

		// Cierrar el recurso cURLy libera recursos del sistema
		curl_close($curl);

		//echo "<img src='//".$exec."' />";

		$gc_log = fopen("gc.log", "a");
		fwrite($gc_log, $_GET['mytoken']."\n\r");
		fclose($gc_log);
		
		print "<hr/>".$exec;

		Yii::app()->end();

		*/

		// Get Request
		if( isset( $_GET['tmltoken'] ) ){
			$tid = $_GET['tmltoken'];
			print "get tid: ".$tid."<hr/>";
		}else{
			print "get tid: null<hr/>";
			Yii::app()->end();
		}

		//Yii::app()->end();


		// Get Campaign
		
		if($click = ClicksLog::model()->findByAttributes(
			array('tid'=>$tid)
			)){

			if($conv = ConvLog::model()->findByAttributes(
				array('tid'=>$tid)
				)){

				print "OK";
				// print "ConvLog: exists<hr/>";
			}else{


				$conv = new ConvLog();
				$conv->tid = $tid;
				$conv->campaigns_id = $click->campaigns_id;
				$conv->clicks_log_id = $click->id;
				$conv->rate = $conv->campaign->external_rate;
				$conv->date       = date("Y-M-D G:i:s");

				$conv->save();

				// s4s (server for server)

				if( Providers::model()->findByPk($conv->campaign->providers_id)->has_s2s ) {

					$haystack = $conv->campaign->providers->callback;
					$needle   = '{ntoken}';
					
					// if there is ntoken macro in the callback
					if( strpos($haystack, $needle)!==false ){
						// replace placeholder macro with value
						$s4s_url = str_replace('{ntoken}', $tid, $conv->campaign->providers->callback);
					} else {
						// add value at the end of callback
						$s4s_url = $conv->campaign->providers->callback . $tid;
					}

					if ( isset($click->custom_params) && $click->custom_params != NULL ) {
						if( strpos($s4s_url, "?") ){
							$s4s_url.= "&";
						} else {
							$s4s_url.= "?";
						}
						$s4s_url.=$click->custom_params;
					}

					//enviar macros
					if($conv->hasMacro($s4s_url))
						$s4s_url = $conv->replaceMacro($s4s_url);

					echo $s4s_url;
					echo '<hr/>';

					$s4s_curl = curl_init($s4s_url); 
		            curl_setopt($s4s_curl, CURLOPT_RETURNTRANSFER, TRUE); 
		            $return = curl_exec($s4s_curl); 
		            curl_close($s4s_curl); 
					
					var_dump($return);
				}




				
				//var_dump($conv);

				/** 
				 * Only for adwords campaigns
				 * Setting Google Conversion Tracking
				 * Adwords = 3
				 */

				/*
				if($click->networks_id == 3){

					$campaign = Campaigns::model()->findByPk($click->campaigns_id);
					if($campaign->gc_id != 'NULL'){
						//print "3"."<hr/>";
						$gc_callback = "http://www.googleadservices.com/pagead/";
						$gc_callback.= "conversion/".$campaign->gc_id."/";
						$gc_callback.= "?label=".$campaign->gc_label;
						$gc_callback.= "&guid=ON&script=0";
						//print $gc_callback;

						// Crea un nuevo recurso cURL
						$curl = curl_init();

						// Establece la URL y otras opciones apropiadas
						curl_setopt($curl, CURLOPT_URL, $gc_callback);
						curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
						curl_setopt($curl, CURLOPT_HEADER, 0);

						// Captura la URL y la envía al navegador
						curl_exec($curl);

						// Cierrar el recurso cURLy libera recursos del sistema
						curl_close($curl);

						$gc_log = fopen( "log/gc.log", "a");
						fwrite($gc_log, $_GET['mytoken']."\n\r");
						fclose($gc_log);
						
					}
				}
				*/

			}

		}else{
			print "ClicksLog: not match<hr/>";
		}

		Yii::app()->end();

	}

	/**
	 * Store conv_log data from 1 month ago in conv_lof_storage
	 * @return string Transaction status
	 */
	public function actionStorage()
	{
		$copyRows     = 'INSERT INTO conv_log_storage 
						SELECT * FROM conv_log WHERE id < (
							SELECT id FROM conv_log 
							WHERE DATE(date) = DATE(DATE_SUB(NOW(), INTERVAL 1 MONTH)) 
							ORDER BY id ASC 
							LIMIT 0,1) 
						ORDER BY id ASC 
						LIMIT 0,300000';

		$deleteRows	  = 'DELETE FROM conv_log 
						WHERE id <= (
							SELECT id FROM conv_log_storage 
							ORDER BY id DESC 
							LIMIT 0,1
							) 
						ORDER BY id ASC';
		
		$result['action'] = 'ConvLogStorage';
		$command = Yii::app()->db->createCommand($copyRows);
		$result['inserted']  = $command->execute();
		$command = Yii::app()->db->createCommand($deleteRows);
		$result['deleted']  = $command->execute();
		echo json_encode($result);
	}

}

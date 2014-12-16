<?php

class ConvLogController extends Controller
{
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

		$gc_callback = "http://localhost/kickads/appserver/conv.php?mytoken=".$_GET['mytoken'];
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
		if( isset( $_GET['ktoken'] ) ){
			$tid = $_GET['ktoken'];
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

				print "ConvLog: exists<hr/>";
			}else{


				$conv = new ConvLog();
				$conv->tid = $tid;
				$conv->campaign_id = $click->campaigns_id;
				$conv->clicks_log_id = $click->id;
				$conv->save();

				// s4s (server for server)
				
				if(Providers::model()->findByPk($conv->campaign->providers_id)->isNetwork() && $conv->campaign->providers->networks->has_s2s){

					$s4s_url = $conv->campaign->providers->networks->callback . $tid;
					
					if ( isset($click->custom_params) && $click->custom_params != NULL ) {
						if( strpos($s4s_url, "?") ){
							$s4s_url.= "&";
						} else {
							$s4s_url.= "?";
						}
						$s4s_url.=$click->custom_params;
					}

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
			//print "ClicksLog: null<hr/>";
		}

		Yii::app()->end();

	}

}
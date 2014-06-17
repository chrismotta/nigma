<?php

class ConvLogController extends Controller
{
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
		if( isset( $_GET['mytoken'] ) ){
			$tid = $_GET['mytoken'];
			//print "get tid: ".$tid."<hr/>";
		}else{
			//print "get tid: null<hr/>";
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

				//print "ConvLog: exists<hr/>";

			}else{

				$conv = new ConvLog();
				$conv->tid = $tid;
				$conv->save();
				
				//var_dump($conv);

				/** 
				 * Only for adwords campaigns
				 * Setting Google Conversion Tracking
				 * Adwords = 3
				 */

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

			}

		}else{
			//print "ClicksLog: null<hr/>";
		}

		Yii::app()->end();

	}

}
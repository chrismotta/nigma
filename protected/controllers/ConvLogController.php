<?php

class ConvLogController extends Controller
{
	public function actionIndex()
	{

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
						print $gc_callback;
						$gc_callback = "http://www.google.com";

						// Crea un nuevo recurso cURL
						$curl = curl_init();

						// Establece la URL y otras opciones apropiadas
						curl_setopt($curl, CURLOPT_URL, $gc_callback);
						curl_setopt($curl, CURLOPT_HEADER, 0);

						// Captura la URL y la envía al navegador
						curl_exec($curl);
						//echo $curl;
						// Cierrar el recurso cURLy libera recursos del sistema
						curl_close($curl);
					}
				}

			}

		}else{
			//print "ClicksLog: null<hr/>";
		}

		Yii::app()->end();





		//print_r($campaign);
		//print "url: ".$redirectURL."<hr/>";

		// Write down a log

		$model = new ClicksLog();
		//$model->id = 2;
		$model->campaigns_id = (int)$cid;
		$model->networks_id = (int)$nid;
		//$model->date = 0;

		// Get visitor parameters
		
		$model->server_ip = isset($_SERVER["SERVER_ADDR"]) ? $_SERVER["SERVER_ADDR"] : null;
		//"$model->forwarded_ip = isset($_SERVER["HTTP_X_FORWARDED_FOR"]) ? $_SERVER["HTTP_X_FORWARDED_FOR"] : null;
		$model->server_name = isset($_SERVER["SERVER_NAME"]) ? $_SERVER["SERVER_NAME"] : null;
		$model->user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : null;
		$model->languaje = isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? $_SERVER['HTTP_ACCEPT_LANGUAGE'] : null;
		$model->referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null;

		//var_dump($model);
		//print "<hr/>";

		// Save active record and redirect
		
		if($model->save()){

			$mytoken = md5($model->id);
			//print "guardado - tid: ".$mytoken;
			//print "<hr/>";
			$model->tid = $mytoken;
			$model->save();

			// Guardo los datos en cookies (Expira en 1 hora)
			setcookie('sma_tid', $mytoken, time() + 1 * 1 * 60 * 60, '/');

			$redirectURL.= "&mytoken=".$mytoken;
			//print $redirectURL;
			// redirect to campaign url
			//$this->redirect($redirectURL);
		}else{
			//print "no guardado";
		}

	}

}
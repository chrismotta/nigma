<?php

class WurflController extends Controller
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
				'actions'=>array('import', 'importFile', 'parseUA'),
				'roles'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	public function actionImport($hash=null){
		
		if(!$hash) die('ERROR: Hash needed!');
		$handle = fopen('uploads/'.$hash.'.csv', "r");

		if ($handle) {
		    while (($line = fgets($handle)) !== false) {

		        // process the line read.
		        // echo $line;
		        // echo '<br/>';
		        if (preg_match('/"([^""]+)"/', $line, $m)) {
				    $ua = $m[1];   
				}
		        // echo '<br/>';
		        if (preg_match('/,([0-9]+)/', $line, $d)) {
				    $conv = $d[1];
				}
		        // echo '<br/>';
		    
				$data = $this->parseUserAgent($ua);

				$useragent = new Useragent1();
				$useragent->user_agent = $ua;
				$useragent->device_brand = $data['brand_name'];
				$useragent->device_model = $data['model_name'];
				$useragent->device_name = $data['marketing_name'];
				$useragent->os_type = $data['device_os'];
				$useragent->os_version = $data['device_os_version'];
				$useragent->browser_type = $data['advertised_browser'];
				$useragent->browser_version = $data['advertised_browser_version'];
				$useragent->conv = $conv;
				$useragent->save();
			
				echo "<hr/>Added<hr/>";
				var_dump($useragent);

		    }

		    fclose($handle);
		} else {
		    echo 'error opening the file.';
		} 
	}

	public function actionParseUA(){
		// die($hash);

		$ua = $this->parseUserAgent($_GET['ua']);
		
		echo $ua['brand_name'];
		echo "<br/>";
		echo $ua['marketing_name'];
		echo "<br/>";
		echo $ua['model_name'];
		echo "<br/>";
		echo $ua['model_extra_info'];
		echo "<br/>";
		echo $ua['device_os'];
		echo "<br/>";
		echo $ua['device_os_version'];
		echo "<br/>";
		echo $ua['advertised_browser'];
		echo "<br/>";
		echo $ua['advertised_browser_version'];
		echo "<br/>";


	}
	
	private function parseUserAgent($user_agent){

		$wurfl  = WurflManager::loadWurfl();
		$device = $wurfl->getDeviceForUserAgent($user_agent);
		$return['brand_name'] = $device->getCapability('brand_name');
		$return['marketing_name'] = $device->getCapability('marketing_name');
		$return['model_name'] = $device->getCapability('model_name');
		$return['model_extra_info'] = $device->getCapability('model_extra_info');
		$return['device_os'] = $device->getCapability('device_os');
		$return['device_os_version'] = $device->getCapability('device_os_version');
		$return['advertised_browser'] = $device->getVirtualCapability('advertised_browser');
		$return['advertised_browser_version'] = $device->getVirtualCapability('advertised_browser_version');
		return $return;
	}

	public function actionImportFile()
        {
           $model=new UserImportForm;
 
           if(isset($_POST['UserImportForm']))
             {
 
               $model->attributes=$_POST['UserImportForm'];
 
               if($model->validate())
                 {
 
                  $csvFile=CUploadedFile::getInstance($model,'file');  
                  $tempLoc=$csvFile->getTempName();
 
                    $sql="LOAD DATA LOCAL INFILE '".$tempLoc."'
				        INTO TABLE `tbl_user`
				        FIELDS
				            TERMINATED BY ','
				            ENCLOSED BY '\"'
				        LINES
				            TERMINATED BY '\n'
				         IGNORE 1 LINES
				        (`name`, `age`, `location`)
				        ";
 
                    $connection=Yii::app()->db;
                    $transaction=$connection->beginTransaction();
                        try
                            {
 
                                $connection->createCommand($sql)->execute();
                                $transaction->commit();
                            }
                            catch(Exception $e) // an exception is raised if a query fails
                             {
                                print_r($e);
                                exit;
                                $transaction->rollBack();
 
                             }
                      $this->redirect(array("user/index"));
 
 
                 }
             }  
 
           $this->render("importcsv",array('model'=>$model));
        }

}
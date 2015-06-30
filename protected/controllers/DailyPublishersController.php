<?php

class DailyPublishersController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column1';

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
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index', 'uploadCSV'),
				'roles'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	public function actionIndex()
	{
		$model = new DailyPublishers('search');
		$model->unsetAttributes();
		$this->render('index', array('model'=>$model));
	}

	public function actionUploadCSV() {
		$tempFile = Yii::getPathOfAlias('webroot') . '/uploads/temp_daily.csv';
		$model    = new DailyPublishers('dump');

		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($model);

        if(isset($_POST['DailyPublishers']))
        {
        	$model->attributes = $_POST['DailyPublishers'];

			if ($model->validate()){

	       		$model->csvFile = CUploadedFile::getInstance($model,'csvFile');
	            $model->csvFile->saveAs( $tempFile );
	            // redirect to success page
	            // $this->redirect(array('uploadCSV'));
	            
	            $fileHandler = fopen($tempFile,'r');
				if($fileHandler){
				    while($line = fgetcsv($fileHandler,1000)){
						
						$placementID = substr($line[0], 0, strpos($line[0], '-'));
						if(is_numeric($placementID)){
							
							echo $placementID . ": " . $line[0] . "<br/>";
							var_dump($line);

							$lineModel = DailyPublishers::model()->findByAttributes(array(
								'placements_id' => $placementID,
								'exchanges_id'  => $model->exchanges_id,
								'date'          => $model->date
								));
							if(isset($lineModel)){
								echo "<br/>===>EXISTS!!";
							}else{
								$lineModel = new DailyPublishers;
								$lineModel->date          = $model->date;
								$lineModel->placements_id = $placementID;
								$lineModel->exchanges_id  = $model->exchanges_id;
								$lineModel->ad_request    = $line[2];
								$lineModel->imp_exchange  = $line[5];
								$lineModel->clicks        = $line[8];
								$lineModel->revenue       = $line[1];
								if($lineModel->save()) echo "<br/>===>SAVED!!";
							}
							
							echo '<hr/>';
						}
				      
				   }
				}
	            
	            die('=> ok csv');

			}else{
				echo json_encode($model->getErrors());
			}
	        // die('=> ok form');
        }

		$exchangesList = CHtml::listData(Exchanges::model()->findAll( array('order'=>'name') ), 'id', 'name' );
        $this->render('_uploadCSV', array('model'=>$model, 'exchanges'=>$exchangesList));
        
    }

    /**
	 * Performs the AJAX validation.
	 * @param Opportunities $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='upload-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

}
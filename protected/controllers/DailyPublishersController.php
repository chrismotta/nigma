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
				'actions'=>array('index', 'uploadCSV', 'update', 'totals'),
				'roles'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	public function actionIndex()
	{
		KHtml::paginationController();
		
		$model = new DailyPublishers('search');
		$model->unsetAttributes();

		if(isset($_GET['DailyPublishers']))
			$model->attributes=$_GET['DailyPublishers'];

		$this->render('index', array('model'=>$model));
	}

	public function actionTotals($hash){
		if($hash!='smaato')
			die('Incorrect Exchange Name');
		
		$model=new DailyPublishers('search');
		$model->unsetAttributes();  // clear any default values

		// $providers = CHtml::listData(Providers::model()->findAll(), 'name', 'name');
		// $publisher_id   = Publishers::model()->findByUser($userId);
		// $user_visibility = Visibility::model()->findByAttributes(array('users_id' => $userId));

		$this->render('totals',array(
			'model'           => $model,
			'publisher_id'    => 'all',
			// 'user_visibility' => $user_visibility,
			'preview'         => true,
			'userId'          => 'all',
			'hash' => $hash
		));
	}

	public function actionUploadCSV() {
		$tempFile = Yii::getPathOfAlias('webroot') . '/uploads/temp_daily.csv';
		$model    = new DailyPublishers('dump');
		$return   = '';
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
							
							$return.= '<hr/>';
							$return.= "#" . $line[0] . "<br/>";
							$return.= "data: " . json_encode($line);

							$lineModel = DailyPublishers::model()->findByAttributes(array(
								'placements_id' => $placementID,
								'exchanges_id'  => $model->exchanges_id,
								'date'          => $model->date
								));
							if(isset($lineModel)){
								$return.= "<br/>===> EXISTS!!";
							}else{
								$lineModel = new DailyPublishers;
								$lineModel->date          = $model->date;
								$lineModel->placements_id = $placementID;
								$lineModel->exchanges_id  = $model->exchanges_id;
								$lineModel->ad_request    = $line[2];
								$lineModel->imp_exchange  = $line[5];
								$lineModel->clicks        = $line[8];
								$lineModel->revenue       = $line[1];
								if($lineModel->save()) $return.= "<br/>===> SAVED!!";
							}	
						}
				    }
							$return.= '<hr/>';

				}
	            
        		$this->render('_uploadCSVreturn', array('return'=>$return));

			}else{
				$return.= json_encode($model->getErrors());
			}
        }

		$exchangesList = CHtml::listData(Exchanges::model()->findAll( array('order'=>'name') ), 'id', 'name' );
        $this->render('_uploadCSV', array('model'=>$model, 'exchanges'=>$exchangesList));
        
    }

	/**
	* Creates a new model.
	* If creation is successful, the browser will be redirected to the 'view' page.
	*/
	public function actionCreate()
	{
		$model=new DailyPublishers;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['DailyPublishers']))
		{
			$model->attributes=$_POST['DailyPublishers'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('create',array(
			'model'=>$model,
			));
	}

	/**
	* Updates a particular model.
	* If update is successful, the browser will be redirected to the 'view' page.
	* @param integer $id the ID of the model to be updated
	*/
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['DailyPublishers']))
		{
			$model->attributes=$_POST['DailyPublishers'];
			if($model->save())
				$this->redirect(array('admin'));
		}

		// $this->render('update',array(
		// 	'model'=>$model,
		// 	));
		$this->renderPartial('_form', array(
			'model'     => $model,
		), false, true);
	}

	/**
	* Deletes a particular model.
	* If deletion is successful, the browser will be redirected to the 'admin' page.
	* @param integer $id the ID of the model to be deleted
	*/
	public function actionDelete($id)
	{
		if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
			$this->loadModel($id)->delete();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/**
	* Returns the data model based on the primary key given in the GET variable.
	* If the data model is not found, an HTTP exception will be raised.
	* @param integer the ID of the model to be loaded
	*/
	public function loadModel($id)
	{
		$model=DailyPublishers::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	* Performs the AJAX validation.
	* @param CModel the model to be validated
	*/
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='daily-publishers-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

}
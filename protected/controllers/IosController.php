<?php

class IosController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

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
				'actions'=>array('index','view','create','update','admin','delete', 'archived','redirect','generatePdf','uploadPdf','viewPdf'),
				'roles'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
	$model = $this->loadModel($id);
		$this->renderPartial('_view', array( 
			'model'=>$model 
		), false, true);
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Ios;

		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($model);

		if(isset($_POST['Ios']))
		{
			$model->attributes=$_POST['Ios'];
			if($model->save())
			{
				if(isset($_POST['opp_ids']))
				{
					foreach ($_POST['opp_ids'] as $value) {
						if(IosHasOpportunities::model()->checkRelation($model->id,$value))
							continue;
						$modelHasIos = new IosHasOpportunities;
						$modelHasIos->ios_id=$model->id;
						$modelHasIos->opportunities_id=$value;
						$modelHasIos->save();
					}
				}
				$this->redirect(array('admin'));					
			}
		}

		$this->renderFormAjax($model);
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
		$this->performAjaxValidation($model);

		if(isset($_POST['Ios']))
		{
			$model->attributes=$_POST['Ios'];
			if($model->save())
				$this->redirect(array('admin'));
		}

		$this->renderFormAjax($model);
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Ios');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Ios('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Ios']))
			$model->attributes=$_GET['Ios'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Ios the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Ios::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Ios $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='ios-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	public function renderFormAjax($model) 
	{
		$this->renderPartial('_form',array(
			'model'      =>$model,
		), false, true);
	}


	public function actionGeneratePdf($id) 
	{
		$model = $this->loadModel($id);
	//	if (isset($_POST['submit'])) {

			$opportunities=array();
			foreach (IosHasOpportunities::model()->getOpportunities($id) as $value) {
				$opportunities[]=$value->opportunities_id;
			}
			$pdf = PDFInsertionOrder::doc();
	        $pdf->setData( array(
				'advertiser'    => Advertisers::model()->findByPk($model->financeEntities->advertisers_id),
				'io'            => $model,
				'opportunities' => $opportunities,
	        ));
	        $pdf->output();
	        Yii::app()->end();
//	    }

	  /*  $this->renderPartial('_generatePDF', array(
	    	'model' => $model,
	    ), false, true);*/
	}
	
	public function actionViewPdf($id) 
	{
		$model = $this->loadModel($id);
		$path = PDF::getPath();

		if ( file_exists($path . $model->pdf_name) ) {
			$info = pathinfo($model->pdf_name);
			if ( $info['extension'] == 'pdf') { // pdf file show in a new tab
				$this->redirect( array('uploads/' . $model->pdf_name) );
			} else { // other files download
				Yii::app()->getRequest()->sendFile( $model->pdf_name, file_get_contents($path . $model->pdf_name) );
			}
		} else {
			throw new CHttpException(404,"The file doesn't exist.");
		}
		Yii::app()->end();
	}

	public function actionUploadPdf($id) 
	{
		$model = $this->loadModel($id);
		$path = PDF::getPath();

		if(isset($_POST['submit'])) {

			if ( is_uploaded_file($_FILES["upload-file"]["tmp_name"]) ) {
				// Create new name for file
				$extension = substr( $_FILES["upload-file"]["name"], strrpos($_FILES["upload-file"]["name"], '.') );
				$newName = 'Adv-' . $model->financeEntities->advertisers_id . '_IO-' . $id . $extension;
				
				if ( ! move_uploaded_file($_FILES["upload-file"]['tmp_name'], $path . $newName) ) {
					Yii::app()->end();
				}

				// Update prospect to complete
				//$model->prospect = 10;
				$model->pdf_name = $newName;
				$model->save();
			}
			$this->redirect(array('admin'));
		}

		$this->renderPartial('_uploadPDF', array(
			'model' => $model,
		));
	}
}

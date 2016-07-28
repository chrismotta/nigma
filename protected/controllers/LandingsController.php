<?php

class LandingsController extends Controller
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
					'actions'=>array('index','view','create','update','duplicate','admin', 'response','delete', 'archived','redirect'),
					'roles'=>array('admin', 'commercial', 'commercial_manager', 'media_manager', 'account_manager','account_manager_admin'),
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
	$this->render('view',array(
	'model'=>$this->loadModel($id),
	));
	}

	/**
	* Creates a new model.
	* If creation is successful, the browser will be redirected to the 'view' page.
	*/
	public function actionCreate()
	{
		$model=new Landings;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Landings']))
		{
		$model->attributes=$_POST['Landings'];
		if($model->save())
		$this->redirect(array('admin','id'=>$model->id));
		}

		$this->renderForm($model, 'create');
		
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

		if(isset($_POST['Landings']))
		{
			$model->attributes=$_POST['Landings'];
			if($model->save())
				$this->redirect(array('admin','id'=>$model->id));
		}

		$this->renderForm($model, 'update');

	}

	public function actionDuplicate($id) 
	{
		$old = $this->loadModel($id);

		$new = clone $old;
		unset($new->id);
		$new->unsetAttributes(array('id'));
		$new->isNewRecord = true;
		
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($new);

		if(isset($_POST['Landings']))
		{
			$new->attributes=$_POST['Landings'];
			if($new->save())
				$this->redirect(array('admin', 'id'=>$new->id));
		} 
		
		$this->renderForm($new, 'duplicate');
	}

	public function renderForm($model, $action){

		$background_images_id = CHtml::listData(
			LandingImages::model()->findAllByAttributes(array('type'=>'Background'))
			, 'id', 'id');
		$headline_images_id = CHtml::listData(
			LandingImages::model()->findAllByAttributes(array('type'=>'HeadLine'))
			, 'id', 'id');
		$byline_images_id = CHtml::listData(
			LandingImages::model()->findAllByAttributes(array('type'=>'ByLine'))
			, 'id', 'id');

		$country = CHtml::listData(GeoLocation::model()->findAll( array('order'=>'name', "condition"=>"status='Active' AND type='Country'") ), 'id_location', 'name' );

		$this->render($action,array(
			'model' => $model,
			'background_images_id' => $background_images_id,
			'headline_images_id' => $headline_images_id,
			'byline_images_id' => $byline_images_id,
			'country' => $country,
			));

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
	* Lists all models.
	*/
	public function actionIndex()
	{
		$this->redirect(array('admin'));

		// $dataProvider=new CActiveDataProvider('Landings');
		// $this->render('index',array(
		// 'dataProvider'=>$dataProvider,
		// ));
	}

	/**
	* Manages all models.
	*/
	public function actionAdmin()
	{
	$model=new Landings('search');
	$model->unsetAttributes();  // clear any default values
	if(isset($_GET['Landings']))
	$model->attributes=$_GET['Landings'];

	$this->render('admin',array(
	'model'=>$model,
	));
	}

	/**
	* Returns the data model based on the primary key given in the GET variable.
	* If the data model is not found, an HTTP exception will be raised.
	* @param integer the ID of the model to be loaded
	*/
	public function loadModel($id)
	{
	$model=Landings::model()->findByPk($id);
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
	if(isset($_POST['ajax']) && $_POST['ajax']==='landings-form')
	{
	echo CActiveForm::validate($model);
	Yii::app()->end();
	}
	}
}

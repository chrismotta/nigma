<?php

class PublishersController extends Controller
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
				'actions'=>array('index','view','create','update','admin','delete','archived'),
				'roles'=>array('admin','media_manager'),
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
		$modelPubl = $this->loadModel($id);
		$modelProv = Providers::model()->findByPk($modelPubl->providers_id);

		$this->renderPartial('_view', array( 
			'modelPubl'=>$modelPubl,
			'modelProv'=>$modelProv,
		), false, true);
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$modelPubl=new Publishers;
		$modelProv=new Providers;

		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($modelPubl);
		$this->performAjaxValidation($modelProv);

		if(isset($_POST['Publishers']) && isset($_POST['Providers']))
		{
			$modelProv->attributes=$_POST['Providers'];
			if ($modelProv->save()) {
				$modelPubl->attributes=$_POST['Publishers'];
				$modelPubl->providers_id = $modelProv->id;
				if ($modelPubl->save())
					$this->redirect(array('admin'));
			}
		}

		$this->renderFormAjax($modelPubl, $modelProv);
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$modelPubl=$this->loadModel($id);
		$modelProv=Providers::model()->findByPk($modelPubl->providers_id);

		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($modelPubl);
		$this->performAjaxValidation($modelProv);

		if(isset($_POST['Publishers']) && isset($_POST['Providers']))
		{
			$modelPubl->attributes=$_POST['Publishers'];
			$modelProv->attributes=$_POST['Providers'];
			if($modelPubl->save() && $modelProv->save())
				$this->redirect(array('admin'));
		}

		$this->renderFormAjax($modelPubl, $modelProv);
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{

		$model = Providers::model()->findByPk($id);
		switch ($model->status) {
			case 'Active':
				if ( Placements::model()->count("publishers_id=:app_id AND status='Active'", array(":app_id" => $id)) > 0 ) {
					echo "To remove this item must delete the placements associated with it.";
					Yii::app()->end();
				} else {
					$model->status = 'Archived';
				}
				break;
			case 'Archived':
				$model->status = 'Active';
				break;
		}

		$model->save();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Publishers('search');
		$model->unsetAttributes();  // clear any default values
		// $model->status = 'Active';
		if(isset($_GET['Publishers']))
			$model->attributes=$_GET['Publishers'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Manages archived models.
	 */
	public function actionArchived()
	{
		$model=new Publishers('search');
		$model->unsetAttributes();  // clear any default values
		$model->status = 'Archived';
		if(isset($_GET['Publishers']))
			$model->attributes=$_GET['Publishers'];

		$this->render('admin',array(
			'model'      => $model,
			'isArchived' => true,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Publishers the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Publishers::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Publishers $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='publishers-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	private function renderFormAjax($modelPubl, $modelProv) 
	{
		$this->renderPartial('_form', array(
			'modelProv' =>$modelProv,
			'modelPubl' =>$modelPubl,
		), false, true);
	}
}

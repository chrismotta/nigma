<?php

class NetworksController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column1', meaning
	 * using two-column layout. See 'protected/views/layouts/column1.php'.
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
			array('allow',
				'actions' =>array('admin','view','create','update'),
				'roles'   =>array('admin','media_manager'),
			),
			array('allow',
				'actions' =>array('admin','view'),
				'roles'   =>array('media','finance'),
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
		$modelNetw = $this->loadModel($id);
		$modelProv = Providers::model()->findByPk($modelNetw->providers_id);

		$this->renderPartial('_view', array( 
			'modelNetw'=>$modelNetw,
			'modelProv'=>$modelProv,
		), false, true);
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'admin' page.
	 */
	public function actionCreate()
	{
		$modelNetw=new Networks;
		$modelProv=new Providers;

		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($modelNetw, $modelProv);

		if(isset($_POST['Networks']) && isset($_POST['Providers']))
		{
			$modelProv->attributes=$_POST['Providers'];
			if ($modelProv->save()) {
				$modelNetw->attributes=$_POST['Networks'];
				$modelNetw->providers_id = $modelProv->id;
				if ($modelNetw->save())
					$this->redirect(array('admin'));
			}
		}

		$this->renderFormAjax($modelNetw, $modelProv);
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$modelNetw=$this->loadModel($id);
		$modelProv=Providers::model()->findByPk($modelNetw->providers_id);

		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($modelNetw, $modelProv);

		if(isset($_POST['Networks']) && isset($_POST['Providers']))
		{
			$modelNetw->attributes=$_POST['Networks'];
			$modelProv->attributes=$_POST['Providers'];
			if($modelNetw->save() && $modelProv->save())
				$this->redirect(array('admin'));
		}

		$this->renderFormAjax($modelNetw, $modelProv);
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
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Networks('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Networks']))
			$model->attributes=$_GET['Networks'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Networks the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Networks::model()->with('providers')->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Networks $model the model to be validated
	 */
	protected function performAjaxValidation($modelNetw, $modelProv)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='networks-form')
		{
			echo CActiveForm::validate(array($modelNetw, $modelProv));
			Yii::app()->end();
		}
	}

	/**
	 * [renderFormAjax description]
	 * @param  [type] $modelNetw [description]
	 * @param  [type] $modelProv [description]
	 * @return [type]        	 [description]
	 */
	private function renderFormAjax($modelNetw, $modelProv)
	{
		$this->renderPartial('_form', array(
			'modelNetw' =>$modelNetw,
			'modelProv' =>$modelProv,
		), false, true);
	}
}

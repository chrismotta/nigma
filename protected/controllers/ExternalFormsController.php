<?php

class ExternalFormsController extends Controller
{
	public function actionIndex()
	{
		$this->render('index');
	}
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('revenueValidation'),
				'roles'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
	public function actionRevenueValidation()
	{
		$token =isset($_GET['token']) ? $_GET['token'] : null;		
		$model =new RevenueValidation;
		$this->render('revenueValidation',array(
			'model'=>$model->loadModelByToken($token),
		));
	}
}
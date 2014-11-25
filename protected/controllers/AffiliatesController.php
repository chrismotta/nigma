<?php

class AffiliatesController extends Controller
{
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index'),
				'roles'=>array('admin', 'affiliate'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	public function actionIndex()
	{
		// $model=new DailyReport('search');
		// $model->unsetAttributes();  // clear any default values
		// if(isset($_GET['DailyReport']))
		// 	$model->attributes=$_GET['DailyReport'];

		$model=new Campaigns;
		// $networks = CHtml::listData(Networks::model()->findAll(), 'name', 'name');
		if(Yii::app()->user->id)
		{
			$network=Affiliates::model()->findByUser(Yii::app()->user->id)->networks_id;
			$this->render('index',array(
				'model'=>$model,
				'network' => $network,
			));
		}
		else
		{			
			$this->redirect(Yii::app()->baseUrl);
		}
		
		
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
}
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
		if(Yii::app()->user->id)
		{
			$model=new Affiliates;
			$provider=Affiliates::model()->findByUser(Yii::app()->user->id)->providers_id;

			$dateStart      = isset($_GET['dateStart']) ? $_GET['dateStart'] : '-1 week' ;
			$dateEnd        = isset($_GET['dateEnd']) ? $_GET['dateEnd'] : 'today';
			$sum            = isset($_GET['sum']) ? $_GET['sum'] : 0;
			
			$dateStart  = date('Y-m-d', strtotime($dateStart));
			$dateEnd    = date('Y-m-d', strtotime($dateEnd));
			$data = $model->getAffiliates($dateStart, $dateEnd, $provider);

			$this->render('index',array(
				'model'     =>$model,
				'provider'  =>$provider,
				'dateStart' =>$dateStart,
				'dateEnd'   =>$dateEnd,
				'sum'       =>$sum,
				'data'      =>$data
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
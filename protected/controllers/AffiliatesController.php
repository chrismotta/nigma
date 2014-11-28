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
			$model=new Campaigns;
			$network=Affiliates::model()->findByUser(Yii::app()->user->id)->networks_id;

			$dateStart      = isset($_GET['dateStart']) ? $_GET['dateStart'] : '-1 week' ;
			$dateEnd        = isset($_GET['dateEnd']) ? $_GET['dateEnd'] : 'today';
			$accountManager = isset($_GET['accountManager']) ? $_GET['accountManager'] : NULL;
			$opportunities  = isset($_GET['opportunities']) ? $_GET['opportunities'] : NULL;
			$networks       = isset($_GET['networks']) ? $_GET['networks'] : NULL;
			$adv_categories = isset($_GET['advertisers-cat']) ? $_GET['advertisers-cat'] : NULL;
			$sum            = isset($_GET['sum']) ? $_GET['sum'] : 0;

			$dateStart  = date('Y-m-d', strtotime($dateStart));
			$dateEnd    = date('Y-m-d', strtotime($dateEnd));
			$data = $model->getAffiliates($dateStart, $dateEnd, $network);

			$this->render('index',array(
				'model'          =>$model,
				'network'        => $network,
				'dateStart'      =>$dateStart,
				'dateEnd'        =>$dateEnd,
				'accountManager' =>$accountManager,
				'opportunities'  =>$opportunities,
				'networks'       =>$networks,
				'adv_categories' =>$adv_categories,
				'sum'            =>$sum,
				'data'           =>$data
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
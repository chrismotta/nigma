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
				'actions'=>array('revenueValidation','revenueApproved','revenueDisputed','changeEmail'),
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
		$IosValidation =new IosValidation;
		if(!$model=$IosValidation->loadModelByToken($token))
		{
			$this->render('revenueValidation',array(
			'error'        =>"Invalid token",
		));
		}
		$consolidated=array();
		$totals['revenue']=0;
		$totals['conv']=0;
		$IosModel=new Ios;
		$clients = $IosModel->getClientsNew(date('m', strtotime($model->period)),date('Y', strtotime($model->period)),null,$model->ios_id);
		foreach ($clients as $ios) {
			foreach ($ios as $carriers) {
				foreach ($carriers as $data) {
					$consolidated[]=$data;
					$totals['revenue']+=$data['revenue'];
					$totals['conv']+=$data['conv'];
				}
			}
		}
		$dataProvider=new CArrayDataProvider($consolidated, array(
		    'id'=>'consolidated',
		    'sort'=>array(
		        'attributes'=>array(
		             'id', 'name', 'model', 'entity', 'currency', 'rate', 'conv','revenue', 'carrier','opportunitie','country','mobileBrand','product'
		        ),
		    ),
		    'pagination'=>array(
		        'pageSize'=>30,
		    ),
		));


		$this->render('revenueValidation',array(
			'model'        =>$model,
			'dataProvider' =>$dataProvider,
			'totals'       =>$totals,
		));
	}

	public function actionRevenueApproved()
	{
		$token =isset($_POST['token']) ? $_POST['token'] : null;		
		$comment =isset($_POST['comment']) ? $_POST['comment'] : null;		
		$model =new IosValidation;
		$this->renderPartial('revenueApproved',array(
			'model'=>$model->loadModelByToken($token),
			'comment'=>$comment,
		));
	}

	public function actionRevenueDisputed()
	{
		$token =isset($_POST['token']) ? $_POST['token'] : null;		
		$comment =isset($_POST['comment']) ? $_POST['comment'] : null;		
		$model =new IosValidation;
		$this->renderPartial('revenueDisputed',array(
			'model'=>$model->loadModelByToken($token),
			'comment'=>$comment,
		));
	}

	public function actionChangeEmail()
	{
		$token         =isset($_POST['token']) ? $_POST['token'] : null;		
		$email         =isset($_POST['email_validation']) ? $_POST['email_validation'] : null;		
		$iosValidation =new IosValidation;
		$ios           =new Ios;
		$model         =$iosValidation->loadModelByToken($token);
		$this->renderPartial('changeEmail',array(
			'model' =>$model,
			'ios'   =>$ios->findByPk($model->ios_id),
			'email' =>$email
		));
	}
}
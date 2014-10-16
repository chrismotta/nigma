<?php

class DailyTotalsController extends Controller
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
				'actions'=>array('consolidated'),
				'roles'=>array('admin'),
			),
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('consolidated'),
				'ips'=>array('54.88.85.63'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	public function actionIndex()
	{
		$this->render('index');
	}
	public function actionConsolidated()
	{		
		$model =new DailyTotals;
		if(isset($_GET['startDate']) && isset($_GET['endDate']))$model->consolidated($_GET['startDate'],$_GET['endDate']);
		else if(isset($_GET['startDate']))$model->consolidated($_GET['startDate'],null);
		else if(isset($_GET['endDate']))$model->consolidated(null,$_GET['endDate']);
		else $model->consolidated();
	}
}
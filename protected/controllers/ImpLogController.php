<?php

class ImpLogController extends Controller
{
	public function actionIndex()
	{
		$cid = Yii::app()->request->getQuery('cid');
		//$this->render('index');
		print "cid: ".$cid."<br/>";

		$model = Campaigns::model()->findByPk($cid);
		$redirectURL = $model->url;
		//print_r($model);
		print "url: ".$redirectURL."<br/>";

		$this->redirect($redirectURL);
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
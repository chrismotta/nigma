<?php

class CurrencyController extends Controller
{


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
				'actions'=>array('index','view','admin','create','update','delete'),
				'roles'=>array('admin', 'business', 'finance'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	public function actionAdmin()
	{
		$model=new Currency('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Currency']))
			$model->attributes=$_GET['Currency'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}
	/**
	 * Load currency change for all currencies specified in columns of "currency" table. To add new 
	 * currency create new column in database and named it with the ISO 4217 currency code.
	 */
	public function actionIndex()
	{
		// validate if info have't been dowloaded already.
		if ( Currency::model()->exists("date=DATE(:date)", array(":date"=>date('Y-m-d', strtotime('today')))) ) {
			print "Currency: WARNING - information already downloaded.";
			Yii::app()->end(1);
		}

		$url = 'http://rate-exchange.appspot.com/currency?from=USD&to=';

		$currency = new Currency;
		$currency->date = date('Y-m-d', strtotime('today'));
		$currencies = $currency->getTableSchema()->getColumnNames();
		array_shift($currencies); // removed id column
		array_shift($currencies); // removed date column

		foreach ($currencies as $code) {
			$curl = curl_init($url . $code);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			$result = curl_exec($curl);
			$result = json_decode($result);
			if ( !$result ) {
				print "Currency: ERROR - downloading currency info <hr>";
				Yii::app()->end(2);
			}
			curl_close($curl);

			if ( isset($result->err) ) {
				print "Currency: ERROR url:" . $url . $code . ", message: " . $result->err . "<hr>";
				Yii::app()->end(2);
			}
			$currency[$code] = $result->rate;
		}

		if ( ! $currency->save() ) {
			print json_encode($currency->getErrors());
			Yii::app()->end(2);
		}

		print "Currency: SUCCESS - Currency updated";
		Yii::app()->end();
	}

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
		$model=new Currency;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Currency']))
		{
			$model->attributes=$_POST['Currency'];
			if($model->save())
				$this->redirect(array('admin'));
		}

		$this->renderPartial('create',array(
			'model'=>$model,
		),false,true);
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

		if(isset($_POST['Currency']))
		{
			$model->attributes=$_POST['Currency'];
			if($model->save())
				$this->redirect(array('admin'));
		}

		$this->renderPartial('update',array(
			'model'=>$model,
		),false,true);
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

	
	// public function actionAdmin()
	// {
	// 	$model=new Currency('search');
	// 	$model->unsetAttributes();  // clear any default values
	// 	if(isset($_GET['Currency']))
	// 		$model->attributes=$_GET['Currency'];

	// 	$this->render('admin',array(
	// 		'model'=>$model,
	// 	));
	// }

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Currency::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='currency-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
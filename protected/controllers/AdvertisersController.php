<?php

class AdvertisersController extends Controller
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
				'actions'=>array('index','view','create','update','admin','delete', 'externalForm'),
				'roles'=>array('admin', 'commercial', 'commercial_manager', 'media_manager'),
			),
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view','redirect','admin'),
				'roles'=>array('businness'),
			),
			// array('allow', // allow authenticated user to perform 'create' and 'update' actions
			// 	'actions'=>array('create','update'),
			// 	'users'=>array('@'),
			// ),
			// array('allow', // allow admin user to perform 'admin' and 'delete' actions
			// 	'actions'=>array('admin','delete'),
			// 	'users'=>array('admin'),
			// ),
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
		$model=$this->loadModel($id);
		$this->renderPartial('_view', array('model'=>$model));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Advertisers;

		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($model);

		if(isset($_POST['Advertisers']))
		{
			$model->attributes=$_POST['Advertisers'];
			if($model->save())
				$this->redirect(array('admin'));
		}

		$this->renderFormAjax($model);
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
		$this->performAjaxValidation($model);

		if(isset($_POST['Advertisers']))
		{
			$model->attributes=$_POST['Advertisers'];
			if($model->save())
				$this->redirect(array('admin'));
		}

		$this->renderFormAjax($model);
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		if ( Ios::model()->count("advertisers_id=:adv_id", array(":adv_id" => $id)) > 0 ) {
			echo "To remove this item must delete the ios associated with it.";
			Yii::app()->end();
		} else {
			$this->loadModel($id)->delete();
		}

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	public function actionExternalForm($id)
	{
		$validTime = ExternalIoForm::getExpirationHashTime();
		
		$model = $this->loadModel($id);

		// FIXME si se entra mas de un IO???
		$formURL = ExternalIoForm::model()->find( "advertisers_id=:adv AND status='Pending'", array(':adv'=>$id) );

		if ( $formURL ) { // validate expiration hash's time
			if ( (time() - strtotime($formURL->create_date) ) > $validTime ) { // hash expired
				$formURL->create_date = date( 'Y-m-d H:i:s', time() );
				$formURL->hash = sha1($id . $model->name . $formURL->create_date);
				if ( ! $formURL->save() ) {
					// echo "ERROR saving new hash <br>";
					return;
				}
			} // else, hash not expired, do nothing
		} else { // Create new row for ExternalIoForm
			$formURL                 = new ExternalIoForm;
			$formURL->advertisers_id = $id;
			$formURL->commercial_id  = Yii::app()->user->id;
			$formURL->hash           = sha1($id . $model->name . $formURL->create_date);
			if ( ! $formURL->save() ) {
				// echo "ERROR saving new External IO Form <br>";
				return;
			}
		}

		$url   = Yii::app()->getBaseUrl(true) . '/ios/externalCreate?ktoken=' . $formURL->hash;
		$this->renderPartial('_externalForm',array(
			'model'    => $model,
			'formURL'  => $formURL,
			'url'      => $url,
			'timeLeft' => round( ( $validTime - (time() - strtotime($formURL->create_date)) ) / 3600 ), // date in hours to hash expiration.
		), false, true);
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Advertisers');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Advertisers('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Advertisers']))
			$model->attributes=$_GET['Advertisers'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Advertisers the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Advertisers::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Advertisers $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='advertisers-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	public function renderFormAjax($model) 
	{
		$cat = KHtml::enumItem($model, 'cat');

		if ( $model->isNewRecord ) {
			$model->commercial_id = Yii::app()->user->id;
			$commercial = Users::model()->findByPk($model->commercial_id);
		} else {
			$commercial = Users::model()->findByPk($model->commercial_id);
		}

		$this->renderPartial('_form',array(
			'model'      =>$model,
			'categories' =>$cat,
			'commercial' =>$commercial,
		), false, true);
	}
}

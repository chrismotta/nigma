<?php

class OpportunitiesController extends Controller
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
				'actions'=>array('index','view','create','update','admin','delete', 'getIos', 'getCarriers'),
				'roles'=>array('admin', 'commercial', 'commercial_manager', 'media_manager'),
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
		$model = $this->loadModel($id);
		$this->renderPartial('_view',array(
			'model'=>$model,
		), false, true);
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Opportunities;

		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($model);

		if(isset($_POST['Opportunities']))
		{
			$model->attributes=$_POST['Opportunities'];
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

		if(isset($_POST['Opportunities']))
		{
			$model->attributes=$_POST['Opportunities'];
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
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Opportunities');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Opportunities('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Opportunities']))
			$model->attributes=$_GET['Opportunities'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Opportunities the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Opportunities::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Opportunities $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='opportunities-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	public function renderFormAjax($model)
	{
		
		if ( $model->isNewRecord ) {
			// Get only Advertisers and IOs that were created by the current user logged.
			// comentado provisoriamente, generar permiso de admin
			// $advertiser = CHtml::listData( Advertisers::model()->findAll( 'commercial_id=:c_id', array( ':c_id'=>Yii::app()->user->id) ), 'id', 'name' );
			$advertiser = CHtml::listData( Advertisers::model()->findAll(), 'id', 'name' );
			$ios = CHtml::listData(Ios::model()->findAll( 'commercial_id=:c_id', array( ':c_id'=>Yii::app()->user->id) ), 'id', 'name');
		} else {
			// If update register, only show Opportunity's IO and Advertiser
			$ios = Ios::model()->findByPk($model->ios_id);
			$advertiser = Advertisers::model()->findByPk($ios->advertisers_id);
		}
		
		// Get users with authorization "media"
		$account = CHtml::listData(Users::model()->findUsersByRole('media'), 'id', 'username' );

		// Get countries and carriers with status "Active"
		$country = CHtml::listData(GeoLocation::model()->findAll( array('order'=>'name', "condition"=>"status='Active' AND type='Country'") ), 'id_location', 'name' );
		
		if ( $model->isNewRecord ) {
			$carrier = array();
		} else {
			$carrier = CHtml::listData(Carriers::model()->findAll( array('order'=>'mobile_brand', "condition"=>"id_country=" . $model->country_id . " AND status='Active'") ), 'id_carrier', 'mobile_brand' );
		}
		
		$model_adv = KHtml::enumItem($model, 'model_adv');

		$this->renderPartial('_form',array(
			'model'      =>$model,
			'advertiser' =>$advertiser,
			'ios'        =>$ios,
			'account'    =>$account,
			'country'    =>$country,
			'carrier'    =>$carrier,
			'model_adv'  =>$model_adv,
		), false, true);
	}

	public function actionGetIos($id)
	{
		// comentado provisoriamente, generar permiso de admin
		//$ios = Ios::model()->findAll( "advertisers_id=:advertiser AND commercial_id=:c_id", array(':advertiser'=>$id, ':c_id'=>Yii::app()->user->id) );
		$ios = Ios::model()->findAll( "advertisers_id=:advertiser", array(':advertiser'=>$id) );

		$response='<option value="">Select an IOs</option>';
		foreach ($ios as $io) {
			$response .= '<option value="' . $io->id . '">' . $io->name . '</option>';
		}
		echo $response;
		Yii::app()->end();
	}

	public function actionGetCarriers($id)
	{
		$carriers = Carriers::model()->findAll( "id_country=:country AND status='Active'", array(':country'=>$id) );

		$response='<option value="">Select a carrier</option>';
		foreach ($carriers as $carrier) {
			$response .= '<option value="' . $carrier->id_carrier . '">' . $carrier->mobile_brand . '</option>';
		}
		echo $response;
		Yii::app()->end();
	}
}

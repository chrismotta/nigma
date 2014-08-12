<?php

class IosController extends Controller
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
				'actions'=>array('index', 'view','create','update','admin','delete', 'duplicate', 'externalCreate', 'generatePdf'),
				'roles'=>array('admin'),
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
		$this->renderPartial('_view', array( 
			'model'=>$model 
		), false, true);
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Ios;

		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($model);

		if(isset($_POST['Ios']))
		{
			$model->attributes=$_POST['Ios'];
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

		if(isset($_POST['Ios']))
		{
			$model->attributes=$_POST['Ios'];
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
		$dataProvider=new CActiveDataProvider('Ios');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Ios('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Ios']))
			$model->attributes=$_GET['Ios'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	public function actionDuplicate($id) 
	{
		$old = $this->loadModel($id);

		$new = clone $old;
		unset($new->id);
		$new->unsetAttributes(array('id'));
		$new->isNewRecord = true;
		
		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($new);

		if(isset($_POST['Ios']))
		{
			$new->attributes=$_POST['Ios'];
			if($new->save())
				$this->redirect(array('admin'));
		} 

		$this->renderFormAjax($new);
	}

	public function actionExternalCreate()
	{

		if ( isset($_GET['ktoken']) ) {
			$ktoken = $_GET['ktoken'];
		} else {
			echo "ERROR invalid parameters <br>";
			Yii::app()->end();	
		}

		$external = ExternalIoForm::model()->find( 'hash=:ktoken', array(':ktoken' => $ktoken) );

		// Validate hash expiration time
		$validTime = ExternalIoForm::getExpirationHashTime();
		if ( ! $external || ( (time() - strtotime($external->create_date) ) > $validTime ) ) { // hash expired
			$this->render('externalCreate', array(
				'action'   => 'expire',
			));
			Yii::app()->end();
		}

		if ( $external->status == 'Submitted' ) {
			$this->render('externalCreate', array(
				'action'   => 'alreadySubmitted',
			));
			Yii::app()->end();
		}

		$ios = new Ios;

		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($ios);

		if(isset($_POST['Ios'])) {
			$ios = new Ios;
			$ios->attributes=$_POST['Ios'];
			$ios->status = 'Submitted';
			if( $ios->save() )
				$this->render('externalCreate', array(
					'action'=> 'submit',
				));
			Yii::app()->end();
		}

		$currency   = KHtml::enumItem($ios, 'currency');
		$entity     = KHtml::enumItem($ios, 'entity');
		$advertiser = Advertisers::model()->findByPk($external->advertisers_id);
		$country    = CHtml::listData(GeoLocation::model()->findAll( "status='Active'" ), 'id_location', 'name' );
		$commercial = Users::model()->findByPk($external->commercial_id);;

		$ios->status = 1;	// FIXME completar con status correspondiente
		$ios->commercial_id = $commercial->id;
		// $ios->entity = 'LLC';	// FIXME dejar en blanco o hardcodear?
		$ios->advertisers_id = $advertiser->id;

		$this->render('externalCreate', array(
			'action'     => 'form',
			'model'      => $ios,
			'currency'   => $currency,
			'entity'     => $entity,
			'commercial' => $commercial,
			'advertiser' => $advertiser,
			'country'    => $country,
		));
		
		
		echo "OK submitting IO <br>";
		Yii::app()->end();
	}

	public function actionGeneratePdf($id) 
	{

		$model = $this->loadModel($id);
		$opportunities = Opportunities::model()->findAll( 'ios_id=:ios', array(':ios'=>$id) );

		if (! $opportunities) {
			echo "Must Assigned opportunities"; return;
		}

		$this->renderPartial('_generatePDF', array(
			'model'         => $model,
			'opportunities' => $opportunities
		));
		
		Yii::app()->end();
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Ios the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Ios::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Ios $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='ios-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	public function renderFormAjax($model) 
	{
		$currency   = KHtml::enumItem($model, 'currency');
		$entity     = KHtml::enumItem($model, 'entity');
		$advertiser = CHtml::listData(Advertisers::model()->findAll(), 'id', 'name'); 
		$country = CHtml::listData(GeoLocation::model()->findAll( "status='Active'" ), 'id_location', 'name' );

		if ( $model->isNewRecord ) {
			$model->commercial_id = Yii::app()->user->id;
			$commercial = Users::model()->findByPk($model->commercial_id);;
		} else {
			$commercial = Users::model()->findByPk($model->commercial_id);
		}

		$this->renderPartial('_form',array(
			'model'      =>$model,
			'currency'   =>$currency,
			'entity'     =>$entity,
			'commercial' =>$commercial,
			'advertiser' =>$advertiser,
			'country'    =>$country,
		), false, true);
	}
}

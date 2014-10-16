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
			array('allow',
				'actions'=>array('externalCreate'),
				'users'=>array('*'),
			),
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','clients', 'view','create','update','admin','delete', 'duplicate', 'externalCreate', 'generatePdf', 'uploadPdf', 'viewPdf'),
				'roles'=>array('admin', 'commercial', 'commercial_manager', 'media_manager'),
			),
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view','redirect','admin'),
				'roles'=>array('businness', 'finance'),
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
		if ( Opportunities::model()->count("ios_id=:ios_id", array(":ios_id" => $id)) > 0 ) {
			echo "To remove this item must delete the opportunities associated with it.";
			Yii::app()->end();
		} else {
			$this->loadModel($id)->delete();
		}

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
			echo "submited";
			$ios = new Ios;
			$ios->attributes=$_POST['Ios'];
			$ios->status = NULL; // FIXME completar con status correspondiente
			if( $ios->save() )
				$this->render('externalCreate', array(
					'action'=> 'submit',
				));
			else
				echo "error saveing" . json_encode($ios->getErrors());
			Yii::app()->end();
		}

		$currency   = KHtml::enumItem($ios, 'currency');
		$entity     = KHtml::enumItem($ios, 'entity');
		$advertiser = Advertisers::model()->findByPk($external->advertisers_id);
		$country = CHtml::listData(GeoLocation::model()->findAll( array('order'=>'name', "condition"=>"status='Active' AND type='Country'") ), 'id_location', 'name' );
		$commercial = Users::model()->findByPk($external->commercial_id);;

		$ios->status = 1;	// FIXME completar con status correspondiente
		$ios->commercial_id = $commercial->id;
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

		$pdf = Pdf::doc();
        $pdf->setData( array(
			'advertiser'    => Advertisers::model()->findByPk($model->advertisers_id),
			'io'            => $model,
			'opportunities' => Opportunities::model()->findAll( 'ios_id=:id', array(':id'=>$id) ),
        ));
        $pdf->output();

        Yii::app()->end();
	}

	public function actionViewPdf($id) 
	{
		$model = $this->loadModel($id);
		$path = Pdf::getPath();

		if ( file_exists($path . $model->pdf_name) ) {
			$info = pathinfo($model->pdf_name);
			if ( $info['extension'] == 'pdf') { // pdf file show in a new tab
				$this->redirect( array('uploads/Adv-1_IO-1.pdf') );
			} else { // other files download
				Yii::app()->getRequest()->sendFile( $model->pdf_name, file_get_contents($path . $model->pdf_name) );
			}
		} else {
			throw new CHttpException(404,"The file doesn't exist.");
		}
		Yii::app()->end();
	}

	public function actionUploadPdf($id) 
	{
		$model = $this->loadModel($id);
		$path = Pdf::getPath();

		if(isset($_POST['submit'])) {

			if ( is_uploaded_file($_FILES["upload-file"]["tmp_name"]) ) {
				// Create new name for file
				$extension = substr( $_FILES["upload-file"]["name"], strrpos($_FILES["upload-file"]["name"], '.') );
				$newName = 'Adv-' . $model->advertisers_id . '_IO-' . $id . $extension;
				
				if ( ! move_uploaded_file($_FILES["upload-file"]['tmp_name'], $path . $newName) ) {
					Yii::app()->end();
				}

				// Update status to complete
				$model->status = 10;
				$model->pdf_name = $newName;
				$model->save();
			}
			$this->redirect(array('admin'));
		}

		$this->renderPartial('_uploadPDF', array(
			'model' => $model,
		));
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
		$advertiser = CHtml::listData(Advertisers::model()->findAll(array('order'=>'name')), 'id', 'name'); 
		$country = CHtml::listData(GeoLocation::model()->findAll( array('order'=>'name', "condition"=>"status='Active' AND type='Country'") ), 'id_location', 'name' );

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

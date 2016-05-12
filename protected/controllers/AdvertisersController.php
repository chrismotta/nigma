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
				'actions'=>array('index','view','create','update', 'response','admin','delete', 'externalForm', 'archived','viewAjax','getAdvertisers'),
				'roles'=>array('admin', 'commercial', 'commercial_manager', 'media_manager', 'account_manager','account_manager_admin'),
			),
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view','redirect','admin','archived'),
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
	 * Displays a particular model by ajax.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionViewAjax($id)
	{
		$model = $this->loadModel($id);

		$this->renderPartial('_view',array(
			'model'=>$model,
		), false, true);
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
				$this->redirect(array('response', 'id'=>$model->id, 'action'=>'created'));
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
				$this->redirect(array('response', 'id'=>$model->id, 'action'=>'updated'));
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
		$model = $this->loadModel($id);		
		switch ($model->status) {
			case 'Active':
				if ( Ios::model()->count("advertisers_id=:adv_id AND status='Active'", array(":adv_id" => $id)) > 0 ) {
					echo "To remove this item must delete the ios associated with it.";
					Yii::app()->end();
				} else {
					$model->status = 'Archived';
				}
				break;
				
			case 'Archived':
				$model->status = 'Active';
				break;
		}

		$model->save();

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

		$url   = Yii::app()->getBaseUrl(true) . '/financeEntities/externalCreate?tmltoken=' . $formURL->hash;
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
		$this->redirect(array('admin'));

		// $dataProvider=new CActiveDataProvider('Advertisers');
		// $this->render('index',array(
		// 	'dataProvider'=>$dataProvider,
		// ));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		KHtml::paginationController();
		
		$model=new Advertisers('search');
		$model->unsetAttributes();  // clear any default values
		$model->status = 'Active';
		if(isset($_GET['Advertisers']))
			$model->attributes=$_GET['Advertisers'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Manages archived models.
	 */
	public function actionArchived()
	{
		$model=new Advertisers('search');
		$model->unsetAttributes();  // clear any default values
		$model->status = 'Archived';
		if(isset($_GET['Advertisers']))
			$model->attributes=$_GET['Advertisers'];

		$this->render('admin',array(
			'model'=>$model,
			'isArchived' => true,
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
		$this->layout='//layouts/modalIframe';
		$cat = KHtml::enumItem($model, 'cat');

		if ( $model->isNewRecord ) {
			$model->commercial_id = Yii::app()->user->id;
			$commercial = Users::model()->findByPk($model->commercial_id);
		} else {
			$commercial = Users::model()->findByPk($model->commercial_id);
		}

		$users = CHtml::listData( Users::model()->findAll( array('condition'=>'status="Active"','order'=>'username')), 'id', 'username');

		$this->render('_form',array(
			'model'      =>$model,
			'categories' =>$cat,
			'commercial' =>$commercial,
			'users'      =>$users,
		));
	}

	public function actionResponse($id){
		
		$action = isset($_GET['action']) ? $_GET['action'] : 'created';
		$this->layout='//layouts/modalIframe';
		$this->render('//layouts/mainResponse',array(
			'entity' => 'Advertiser',
			'action' => $action,
			'id'    => $id,
		));
	}

	public function actionGetAdvertisers()
	{
		// comentado provisoriamente, generar permiso de admin
		//$ios = Ios::model()->findAll( "advertisers_id=:advertiser AND commercial_id=:c_id", array(':advertiser'=>$id, ':c_id'=>Yii::app()->user->id) );
		$criteria=new CDbCriteria;
        $criteria->with  = array('ios', 'ios.advertisers');
        $criteria->compare('t.status', 'Active');
        $criteria->order = 'advertisers.name';
        $criteria->group = 'advertisers.name';
		$ids = isset($_GET['accountManager']) ? $_GET['accountManager'] : null;
		if ( $ids != NULL) {
			if(is_array($ids))
			{
				$query="(";
				$i=0;
				foreach ($ids as $id) {	
					if($i==0)			
						$query.="account_manager_id='".$id."'";
					else
						$query.=" OR account_manager_id='".$id."'";
					$i++;
				}
				$query.=")";
				$criteria->addCondition($query);				
			}
			else
			{
				$criteria->compare('account_manager_id',$ids);
			}
		}
		$opps =Opportunities::model()->findAll($criteria);
		$response='';
		// $response='<option value="">All opportunities</option>';
		foreach ($opps as $op) {
			$response .= '<option value="' . $op->ios->advertisers->id . '">' . $op->ios->advertisers->name . '</option>';
		}
		echo $response;
		Yii::app()->end();
	}
}

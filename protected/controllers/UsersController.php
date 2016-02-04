<?php

class UsersController extends Controller
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
				'actions'=>array('index','view','create','update','admin','delete','response', 'adminRoles','visibility','notAssigned'),
				'roles'=>array('admin','media_buyer_admin'),
			),
			array('allow', 
				'actions'=>array('profile'),
				//'roles'=>array('admin'),
				'users'=>array('@'),
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
			'model'=>$model,
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Users;
		$model->repeat_password = $model->password;


		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($model);

		if(isset($_POST['Users']))
		{
			$oldPassword = $model->password;
           	$model->attributes = $_POST['Users'];
           	if ($model->password != $oldPassword) {
				$model->password        = sha1($model->password);
				$model->repeat_password = sha1($model->repeat_password);
           	}

			if($model->save())
				$this->redirect(array('adminRoles', 'id'=>$model->id, 'action'=>'created'));
		}

		$this->renderFormAjax($model);
	}

	public function actionResponse($id){
		
		$action = isset($_GET['action']) ? $_GET['action'] : 'created';
		$this->layout='//layouts/modalIframe';
		$this->render('//layouts/mainResponse',array(
			'entity' => 'User',
			'action' => $action,
			'id'    => $id,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);
		$model->repeat_password = $model->password;

		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($model);

		if(isset($_POST['Users']))
		{	
			$oldPassword = $model->password;
           	$model->attributes = $_POST['Users'];
           	if ($model->password != $oldPassword) {
				$model->password        = sha1($model->password);
				$model->repeat_password = sha1($model->repeat_password);
           	}

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
		foreach ( Yii::app()->authManager->getRoles($id) as $role => $value ) {
			Yii::app()->authManager->revoke($role, $id);
		}

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
		$dataProvider=new CActiveDataProvider('Users');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		KHtml::paginationController();
	
		$model=new Users('search');
		$model->unsetAttributes();  // clear any default values

		if(isset($_GET['Users']))
			$model->attributes=$_GET['Users'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	public function actionAdminRoles($id) 
	{
		$this->layout = '//layouts/modalIframe';
		// $roles = Yii::app()->authManager->getAuthItem('publisher'); // Get only "roles"
		$roles = Yii::app()->authManager->getAuthItems(2); // Get only "roles"
		$user = Users::model()->findByPk($id);

		// Validate if the callback is from form's submit
		if ( isset($_POST['submit']) ) {
			foreach ($roles as $role) {
				$isAssigned = Yii::app()->authManager->checkAccess($role->name, $id);

				if( isset($_POST[$role->name]) ) { // REQUEST only submit roles to assign

					if ( ! $isAssigned ) { // if not already assigned, assign new role
						if ( ! Yii::app()->authManager->assign($role->name, $id) ) {
							echo "ERROR assigning role: " . $role->name . " to user: $id"; 
						}
					}

				} else { // If role missing in REQUEST revoke it
					if ( $isAssigned ) { // if already assigned, revoke role
						if ( ! Yii::app()->authManager->revoke($role->name, $id) ) {
							echo "ERROR revoking role: " . $role->name . " to user: $id";
						}
					}
				}
			}
			$this->redirect(array('response', 'id'=>$user->id, 'action'=>'updated'));
		}
		
		$this->render('_roles',array(
			'model'=>$user,
			'roles'=>$roles,
		));
	}

	public function actionVisibility($id) 
	{
		$this->layout = '//layouts/modalIframe';
		$model = Visibility::model()->findByAttributes( array('users_id'=>$id) );
		$user  = Users::model()->findByPk($id);
		
		if($model===null){
			$model = new Visibility();
			$model->users_id = $id;
		}

		if ( isset($_POST['Visibility']) ) {
			$model->attributes = $_POST['Visibility'];
			if($model->save())
				$this->redirect(array('response', 'id'=>$user->id, 'action'=>'updated'));
		}

		$advertiser = Advertisers::model()->findByAttributes( array('users_id' => $id) );

		$this->render('_visibility',array(
			'model'      => $model,
			'user'       => $user,
			'advertiser' => $advertiser,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Users the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Users::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Users $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='users-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	public function renderFormAjax($model)
	{
		$status = KHtml::enumItem($model, 'status');

		$this->renderPartial('_form',array(
			'model'        =>$model,
			'status'       =>$status,
		), false, true);
	}

	public function actionNotAssigned(){
		$this->render('notAssigned');
	}

	public function actionProfile()
	{
		$model=$this->loadModel(Yii::App()->user->getId());
		$model->repeat_password = $model->password;

		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($model);

		if(isset($_POST['Users']))
		{	
			$oldPassword = $model->password;
           	$model->attributes = $_POST['Users'];
           	if ($model->password != $oldPassword) {
				$model->password        = sha1($model->password);
				$model->repeat_password = sha1($model->repeat_password);
           	}

			if($model->save())
				$this->redirect(array('profile'));
		}

		$this->render('profile',array(
			'model'        =>$model,
		), false, true);
	}
}

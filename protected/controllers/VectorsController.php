<?php

class VectorsController extends Controller
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
				'actions'=>array('index','view','redirectAjax','admin','create','update','delete','createRelation','updateRelation','deleteRelation','archived', 'updateEditable'),
				'roles'=>array('admin', 'media_manager', 'business', 'affiliates_manager', 'account_manager_admin'),
			),
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
		$model=new Vectors;

		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($model);

		if(isset($_POST['Vectors']))
		{
			$model->attributes=$_POST['Vectors'];
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

		if(isset($_POST['Vectors']))
		{
			$model->attributes=$_POST['Vectors'];
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
		$model = $this->loadModel($id);
		switch ($model->status) {
			case 'Active':
				$model->status = 'Archived';
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
	
	/**
	 * Generate redirects.
	 */
	public function actionRedirectAjax($id)
	{
		$vectorsModel    = $this->loadModel($id);
		//$provider = Providers::model()->findByPk($model->providers_id);

		$this->renderPartial('_redirects',array(
			'model'        => $vectorsModel,
			//'provider'      => $provider,
			'vectorName' => Vectors::model()->getExternalName($id),
		), false, true);
	}

	/**
	 * Add campaign to vector.
	 */
	public function actionCreateRelation($id)
	{		
		$vhc               = new VectorsHasCampaigns;
		$vhc->vectors_id   = $id;
		$vhc->campaigns_id = $_POST['Campaigns']['name'];
		$vhc->save();

		$campaigns = $this->getCampaigns($id);

		$response='<option value="">Select a campaign</option>';
		foreach ($campaigns as $campaign) {
			$response .= '<option value="' . $campaign->id . '">' . $campaign->getExternalName($campaign->id) . '</option>';
		}
		echo $response;
	}

	/**
	 * Asociate campaigns to a vector.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionUpdateRelation($id)
	{
		$vectorsModel = $this->loadModel($id);

		// Get campaigns available for adding to vector
		$campaigns = CHtml::listData( $this->getCampaigns($id),
			'id', 
			function($c) { return $c->getExternalName($c->id); } );

		$campaignsModel = new Campaigns;
		$campaignsModel->unsetAttributes(); 

		$vhc = new VectorsHasCampaigns;
		$vhc->unsetAttributes();
		$vhc->vectors_id = $id;

		$this->layout='//layouts/modalIframe';

		$this->render('_updateRelation',array(
			'campaigns'      => $campaigns,
			'campaignsModel' => $campaignsModel,
			'vectorsModel'   => $vectorsModel,
			'vhc' 			 => $vhc,
		));
	
	}

	/**
	 * Deletes added campaigns.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 */
	public function actionDeleteRelation()
	{
		$model = VectorsHasCampaigns::model()->findByPk( array(
				'vectors_id' => $_GET['vid'],
				'campaigns_id' => $_GET['cid'],
			));
		$model->delete();

		$campaigns = $this->getCampaigns($model->vectors_id);
		$response='<option value="">Select a campaign</option>';
		foreach ($campaigns as $campaign) {
			$response .= '<option value="' . $campaign->id . '">' . $campaign->getExternalName($campaign->id) . '</option>';
		}
		echo $response;

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		// if(!isset($_GET['ajax']))
		// 	$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Vectors');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Vectors('search');
		$model->unsetAttributes();  // clear any default values
		$model->status = 'Active';
		if(isset($_GET['Vectors']))
			$model->attributes=$_GET['Vectors'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Manages archived models.
	 */
	public function actionArchived()
	{
		$model=new Vectors('search');
		$model->unsetAttributes();  // clear any default values
		$model->status = 'Archived';
		if(isset($_GET['Vectors']))
			$model->attributes=$_GET['Vectors'];

		$this->render('admin',array(
			'model'      =>$model,
			'isArchived' =>true,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Vectors::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	private function renderFormAjax($model)
	{	// excepción comentada provisoriamente
		$criteria        = new CDbCriteria;
		$criteria->compare('use_vectors',1);
		$criteria->order = 'name';
		$providers = CHtml::listData(Providers::model()->findAll($criteria), 'id', 'name');

		$this->renderPartial('_form',array(
			'model'    => $model,
			'providers' => $providers,
		), false, true);
	}

	private function getCampaigns($id)
	{
		$vectorsModel = $this->loadModel($id);

		$criteria = new CDbCriteria;
		$criteria->with = array('vectors');
		$criteria->addCondition("t.id NOT IN (SELECT vhc.campaigns_id FROM vectors_has_campaigns vhc WHERE vhc.vectors_id=". $id . ")");
		$criteria->compare('t.providers_id', $vectorsModel->providers_id);
		$criteria->compare('t.status', 'Active');
		FilterManager::model()->addUserFilter($criteria, 'campaign.account');

		return Campaigns::model()->findAll( $criteria );
	}


	public function actionUpdateEditable(){
		$req = Yii::app()->getRequest();
		$pk = $req->getParam('pk');
		$model = VectorsHasCampaigns::model()->findByAttributes(array(
			'campaigns_id' => $pk['campaigns_id'],
			'vectors_id'   => $pk['vectors_id'],
			));

		$model[$req->getParam('name')] = $req->getParam('value');
		$model->save();

		// Yii::log($pk['campaigns_id'], 'warning', 'system.model.VectorsHasCampaigns');
		Yii::app()->end();
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='vectors-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}

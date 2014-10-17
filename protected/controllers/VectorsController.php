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
				'actions'=>array('index','view','admin','create','update','delete','updateRelation','deleteRelation'),
				'roles'=>array('admin', 'media_manager', 'sem', 'media'),
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

		$this->renderPartial('_form',array(
			'model'=>$model,
		), false, true);
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

		$this->renderPartial('_form',array(
			'model'=>$model,
		), false, true);
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


	/**
	 * Asociate campaigns to a vector.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionUpdateRelation($id)
	{
		if ( isset($_POST['submit']) ) {
			// echo "POST: " . json_encode($_REQUEST) . "<hr>"; return;
			$vhc               = new VectorsHasCampaigns;
			$vhc->vectors_id   = $id;
			$vhc->campaigns_id = $_POST['Campaigns']['name'];
			$vhc->save();
			echo "OK";
			Yii::app()->end();
			// return false;
		}

		// TODO Get campaigns available for adding to vector
		$criteria = new CDbCriteria;
		$criteria->with = array('vectors');
		$criteria->addCondition("t.id NOT IN (SELECT vhc.campaigns_id FROM vectors_has_campaigns vhc WHERE vhc.vectors_id=". $id . ")");
		FilterManager::model()->addUserFilter($criteria, 'campaign.account');

		$campaigns = CHtml::listData( Campaigns::model()->findAll( $criteria ),
			'id', 
			function($c) { return $c->getExternalName($c->id); } );

		$campaignsModel = new Campaigns;
		$campaignsModel->unsetAttributes();  // clear any default values

		$vectorsModel = $this->loadModel($id); 
		// $vectorsModel = Vectors::model()->findByPk($id);
		// $vhcModel     = new VectorsHasCampaigns;

		$this->renderPartial('_updateRelation',array(
			'campaigns'      => $campaigns,
			'campaignsModel' => $campaignsModel,
			'vectorsModel'   => $vectorsModel,
			// 'vhcModel'       => $vhcModel,
		), false, true);
	
	}

	/**
	 * Deletes added campaigns.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 */
	public function actionDeleteRelation()
	{
		// if(Yii::app()->request->isPostRequest) {
			// we only allow deletion via POST request
			// echo json_encode($_REQUEST); return;
			$model = VectorsHasCampaigns::model()->findByPk( array(
					'vectors_id' => $_GET['vid'],
					'campaigns_id' => $_GET['cid'],
				));
			$model->delete();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		// }
		// else
		// 	throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
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
		if(isset($_GET['Vectors']))
			$model->attributes=$_GET['Vectors'];

		$this->render('admin',array(
			'model'=>$model,
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

<?php

class CampaignsController extends Controller
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
				'actions'=>array('index','view','create','createAjax','update','updateAjax','redirectAjax','admin','delete'),
				'roles'=>array('admin'),
			),
			/*
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('admin'),
			),*/
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
		$model=new Campaigns;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Campaigns']))
		{
			$model->attributes=$_POST['Campaigns'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}


	/**
	 * AJAX ACTIONS
	 */

	/**
	 * Generate redirects.
	 */
	public function actionRedirectAjax()
	{
		$cid=$_POST['cid'];

		/*
		$model=new Campaigns('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Campaigns']))
			$model->attributes=$_GET['Campaigns'];

		$this->render('admin',array(
			'model'=>$model,
		));
		*/
		
		$data['header'] = '
            <a class="close" data-dismiss="modal">&times;</a>
            <h4>Redirects for campaign #'.$cid.'</h4>
            ';
		$data['body'] = '
			<p class="span6"><button type="button" class="btn btn-default btn-sm">copy</button> <strong>Airpush: </strong>http://kickadserver.mobi/test/clicksLog?cid='.$cid.'&nid=1 </p>
			<p><button type="button" class="btn btn-default btn-sm">copy</button> <strong>Reporo: </strong>http://kickadserver.mobi/test/clicksLog?cid='.$cid.'&nid=2 </p>
			<p><button type="button" class="btn btn-default btn-sm">copy</button> <strong>Ajillion: </strong>http://kickadserver.mobi/test/clicksLog?cid='.$cid.'&nid=3 </p>
			<p><button type="button" class="btn btn-default btn-sm">copy</button> <strong>Adwords: </strong>http://kickadserver.mobi/test/clicksLog?cid='.$cid.'&nid=4 </p>
			<p><button type="button" class="btn btn-default btn-sm">copy</button> <strong>Kimia: </strong>http://kickadserver.mobi/test/clicksLog?cid='.$cid.'&nid=5 </p>
			<p><button type="button" class="btn btn-default btn-sm">copy</button> <strong>Leadbolt: </strong>http://kickadserver.mobi/test/clicksLog?cid='.$cid.'&nid=6 </p>
			';
		$data['footer'] = '
			Copy and paste the redirect URL into the traffic source.
			';

		echo json_encode($data);
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreateAjax()
	{
		$model=new Campaigns;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Campaigns']))
		{
			$model->attributes=$_POST['Campaigns'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}
		
		$this->renderPartial('_formAjax',array(
			'model'=>$model,
		), false, true);
	}
	
	/**
	 * Updates a particular model by ajax.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdateAjax($id=null)
	{
		isset($id) ? $cid = $id : $cid = $_POST['cid'];
		$model = $this->loadModel($cid);

		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($model);

		if(isset($_POST['Campaigns']))
		{
			$model->attributes=$_POST['Campaigns'];
			if($model->save())
				$this->redirect(array('admin'));
		}

		$this->renderPartial('_formAjax',array(
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
		// $this->performAjaxValidation($model);

		if(isset($_POST['Campaigns']))
		{
			$model->attributes=$_POST['Campaigns'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
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
		$dataProvider=new CActiveDataProvider('Campaigns');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Campaigns('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Campaigns']))
			$model->attributes=$_GET['Campaigns'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Campaigns the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Campaigns::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Campaigns $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='campaigns-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}


}

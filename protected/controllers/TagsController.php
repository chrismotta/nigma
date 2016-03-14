<?php

class TagsController extends Controller
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
				'actions'=>array('index','view','create','update','admin','response','adminByCampaign','getTag','getTxt','getSites','getPlacements','toggle','delete'),
				'roles'=>array('admin', 'media_manager', 'external'),
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
		$this->layout='//layouts/modalIframe';
		$this->render('view',array(
			'model'=>$this->loadModel($id),
			));
	}

	public function actionGetTag($id, $parent='c')
	{
		$this->layout='//layouts/modalIframe';

		$publishers = CHtml::listData( 
			Providers::model()->findAll(
				array(
					'order'=>'name', 
					'condition' => "type='Publisher' AND status='Active'",
					'select'=>array('id','name','CONCAT_WS(" - ",id,name) AS idname'),
					)), 'id', 'name');

		$this->render('get_tag',array(
			'model'=>$this->loadModel($id),
			'publishers' => $publishers,
			'parent'=>$parent,
			));
	}
	public function actionGetTxt($id)
	{
		$model=$this->loadModel($id);
		$pid = isset($_GET['pid']) ? $_GET['pid'] : '<placementID>';

		header('Content-Disposition: attachment; filename="TML_tag#'.$model->id.'.txt"');
		echo '<iframe src="http://bidbox.co/tag/'. $model->id . '?pid='.$pid.'&pubid=<INSERT_PUBID_MACRO_HERE>" width="'. $model->bannerSizes->width .'" height="'. $model->bannerSizes->height .'" frameborder="0" scrolling="no" ></iframe>';
	}

	private function getBannerSizes()
	{
		return CHtml::listData(BannerSizes::model()->findAll(array('order'=>'size')), 'id', 'size');
	}

	public function actionGetSites($id)
	{
		$sites = Sites::model()->findByPublishersId($id);
		
		$response = '<option value="">Select a site</option>';
		foreach ($sites as $site) {
			$response .= '<option value="' . $site->id . '">' . $site->name . '</option>';
		}
		echo $response;
		Yii::app()->end();
	}
	public function actionGetPlacements($id)
	{
		$placements = Placements::model()->findBySitesId($id);

		$response = '<option value="">Select a placement</option>';
		foreach ($placements as $placement) {
			$response .= '<option value="' . $placement->id . '">' . $placement->idname . '</option>';
		}
		echo $response;
		Yii::app()->end();
	}

	/**
	* Creates a new model.
	* If creation is successful, the browser will be redirected to the 'view' page.
	*/
	public function actionCreate()
	{
		$this->layout='//layouts/modalIframe';
		$model=new Tags;

	// Uncomment the following line if AJAX validation is needed
	// $this->performAjaxValidation($model);

		if(isset($_POST['Tags']))
		{
			$model->attributes=$_POST['Tags'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}
		
		$model->campaigns_id = isset($_GET['cid']) ? $_GET['cid'] : null;
		$this->render('create',array(
			'model'=>$model,
			'bannerSizes'=>$this->getBannerSizes(),
			));
	}

	/**
	* Updates a particular model.
	* If update is successful, the browser will be redirected to the 'view' page.
	* @param integer $id the ID of the model to be updated
	*/
	public function actionUpdate($id, $parent='c')
	{
		$this->layout='//layouts/modalIframe';
		$model=$this->loadModel($id);

	// Uncomment the following line if AJAX validation is needed
	// $this->performAjaxValidation($model);

		if(isset($_POST['Tags']))
		{
			$model->attributes=$_POST['Tags'];
			if($model->save())
				$this->redirect(array('response', 'id'=>$model->id, 'action'=>'updated'));
		}

		$this->render('update',array(
			'model'=>$model,
			'bannerSizes'=>$this->getBannerSizes(),
			'parent'=>$parent,
			));
	}

	public function actionResponse($id){
		
		$action = isset($_GET['action']) ? $_GET['action'] : 'created';
		$this->layout='//layouts/modalIframe';
		$this->render('//layouts/mainResponse',array(
			'entity' => 'Tag',
			'action' => $action,
			'id'    => $id,
		));
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
	* Lists all models.
	*/
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Tags');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
			));
	}

	/**
	* Manages all models.
	*/
	public function actionAdmin()
	{
		$model=new Tags('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Tags']))
			$model->attributes=$_GET['Tags'];

		$this->render('admin',array(
			'model'=>$model,
			));
	}
	public function actionAdminByCampaign($id)
	{
		$this->layout='//layouts/modalIframe';

		$model=new Tags('search');
		$model->unsetAttributes();
		$model->campaigns_id = $id;
		if(isset($_GET['Tags']))
			$model->attributes=$_GET['Tags'];

		$this->render('admin_short',array(
			'model'=>$model,
			));
	}
	public function actions()
    {
        return array(
            'toggle' => array(
                'class'=>'bootstrap.actions.TbToggleAction',
                'modelName' => 'Tags',
                )
            );
    }

	/**
	* Returns the data model based on the primary key given in the GET variable.
	* If the data model is not found, an HTTP exception will be raised.
	* @param integer the ID of the model to be loaded
	*/
	public function loadModel($id)
	{
		$model=Tags::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='tags-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}

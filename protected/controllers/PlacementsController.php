<?php

class PlacementsController extends Controller
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
				'actions'=>array('index','view','create','update','admin','delete','archived','getSites','waterfall','waterfallSort','waterfallAdd','waterfallDel'),
				'roles'=>array('admin'),
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
		$model = $this->loadModel($id);
		$this->renderPartial('_view', array(
			'model' => $model,
		), false, true);
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Placements;

		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($model);

		if(isset($_POST['Placements']))
		{
			$model->attributes=$_POST['Placements'];
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

		if(isset($_POST['Placements']))
		{
			$model->attributes=$_POST['Placements'];
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
				if ($model->publishers->status == 'Active') {
					$model->status = 'Active';
				} else {
					echo "To restore this item must restore the publisher associated with it.";
					Yii::app()->end();
				}
				break;
		}

		$model->save();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Placements');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Placements('search');
		$model->unsetAttributes();  // clear any default values
		$model->status = 'Active';
		if(isset($_GET['Placements']))
			$model->attributes=$_GET['Placements'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Manages archived models.
	 */
	public function actionArchived()
	{
		$model=new Placements('search');
		$model->unsetAttributes();  // clear any default values
		$model->status = 'Archived';
		if(isset($_GET['Placements']))
			$model->attributes=$_GET['Placements'];

		$this->render('admin',array(
			'model'      => $model,
			'isArchived' => true,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Placements the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Placements::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Placements $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='placements-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	private function renderFormAjax($model) 
	{
		$sizes      = CHtml::listData( BannerSizes::model()->findAll(array('order'=>'width, height')), 'id', 'size' );
		$exchanges  = CHtml::listData( Exchanges::model()->findAll(array('order'=>'name')), 'id', 'name');
		$sites      = CHtml::listData( Sites::model()->findAll(array('order'=>'name')), 'id', 'name');
		$publishers = CHtml::listData( Publishers::model()->with('providers')->findAll(array('order'=>'providers.name', 'condition' => "providers.status='Active'")), 'providers_id', 'providers.name');
		// $publishers = CHtml::listData( Publishers::model()->with('sites.providers')->findAll(array('order'=>'providers.name', 'condition' => "providers.status='Active'")), 'providers_id', 'providers.name');

		$this->renderPartial('_form', array(
			'model'      => $model,
			'sizes'      => $sizes,
			'exchanges'  => $exchanges,
			'sites'      => $sites,
			'publishers' => $publishers,
		), false, true);
	}

	public function actionGetSites($id)
	{
		// comentado provisoriamente, generar permiso de admin
		//$ios = Ios::model()->findAll( "advertisers_id=:advertiser AND commercial_id=:c_id", array(':advertiser'=>$id, ':c_id'=>Yii::app()->user->id) );
		$sites = Sites::model()->findByPublishersId($id);
		
		$response = '<option value="">Select a site</option>';
		foreach ($sites as $site) {
			$response .= '<option value="' . $site->id . '">' . $site->name . '</option>';
		}
		echo $response;
		Yii::app()->end();
	}

	public function actionWaterfall($id){

		$this->layout='//layouts/modal';

		$placementsModel = $this->loadModel($id);
		$exchangesModel  = new Exchanges('search');
		$waterfallModel  = new PlacementsHasExchanges('search');
		
		$exchangesModel->unsetAttributes();
		$waterfallModel->unsetAttributes();
		$waterfallModel->placements_id = $id;
		
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		// if(isset($_POST['Placements']))
		// {
		// 	$model->attributes=$_POST['Placements'];
		// 	if($model->save())
		// 		$this->redirect(array('admin'));
		// }

		$this->render('_waterfall', array(
			'placementsModel' => $placementsModel,
			'waterfallModel'  => $waterfallModel,
			'exchangesModel'  => $exchangesModel,
			// 'sizes'      => $sizes,
			// 'sites'      => $sites,
			// 'publishers' => $publishers,
		));
	}
	public function actionWaterfallSort(){

		$pid = $_POST['pid'];
		$eid = json_decode( $_POST['eid'] );

		foreach ($eid as $key => $value) {
			$model = PlacementsHasExchanges::model()->findByAttributes(
				array(
					'placements_id' => $pid,
					'exchanges_id'  => $value,
					));
			$model->step = $key+1;
			$model->save();
		}

		echo "sort: \n";
		echo "pid = ".$pid."\n";
		echo "eid = ".$_POST['eid']."\n";
	}

	public function actionWaterfallAdd(){

		$pid = $_POST['pid'];
		$eid = $_POST['eid'];
		
		$query = Yii::app()->db->createCommand()
		    ->select('count(*) AS total')
		    ->from('placements_has_exchanges')
		    ->where('placements_id = 1')
		    ->queryAll();

	    $count = $query[0]['total'] +1;

		$model = new PlacementsHasExchanges();
		$model->placements_id = $pid;
		$model->exchanges_id = $eid;
		$model->step = $count;
		$model->save();

		echo 'Step: '.$count;

	}
	public function actionWaterfallDel(){
		
		$pid = $_POST['pid'];
		$eid = $_POST['eid'];

		$model = PlacementsHasExchanges::model()->findByAttributes(
			array('placements_id' => $pid, 'exchanges_id' => $eid));
		$model->delete();
		
		PlacementsHasExchanges::reSort($pid);
		echo var_dump($_POST);
	}

}

<?php

class DailyReportController extends Controller
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
				'actions'=>array('index','getOpportunities','view','create','update','updateAjax','redirectAjax','admin','delete', 'graphic', 'updateColumn', 'excelReport', 'multiRate', 'createByNetwork'),
				'roles'=>array('admin', 'media', 'media_manager', 'business'),
			),
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','viewAjax','redirectAjax','admin'),
				'roles'=>array('commercial'),
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
		$model=new DailyReport;

		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($model);

		if(isset($_POST['DailyReport']))
		{
			$model->attributes = $_POST['DailyReport'];

			$modelCampaign = Campaigns::model()->findByPk($model->campaigns_id);
			$model->networks_id = $modelCampaign->networks_id;
			$model->conv_api = ConvLog::model()->count("campaign_id=:campaignid AND DATE(date)=:date", array(":campaignid"=>$model->campaigns_id, ":date"=>$model->date));
			$model->updateRevenue();
			if($model->save())
				$this->redirect(array('admin'));
		}

		$this->renderFormAjax($model);
	}

	public function actionCreateByNetwork()
	{
		$date = date('Y-m-d', strtotime('yesterday'));
		$currentNetwork = 1;

		// If date and network are submitted then get values
		if ( isset($_GET['networkSubmit']) ) {
			$date           = $_GET['date'];
			$currentNetwork = $_GET['network'];
		}

		if ( isset($_POST['saveSubmit']) ) {
			
			$model=new DailyReport;
			$model->attributes = $_POST['DailyReport'];
			$model->updateRevenue();
				
			// Validate if record has already been entry
			$existingModel = DailyReport::model()->find('campaigns_id=:cid AND networks_id=:nid AND date=:date', array(':cid' => $model->campaigns_id, ':nid' => $model->networks_id, ':date' => $model->date));
			if ( $existingModel ) {
				$model->isNewRecord = false;
				$model->id = $existingModel->id;
			}

			$r = new stdClass();
			$r->c_id = $model->campaigns_id;
			if ( $model->save() ) {
				$r->result = "OK";
			} else {
				$r->result = "ERROR";
				$r->message = $model->getErrors();
			}
			echo json_encode($r);
			Yii::app()->end();
		}
		
		$networks = CHtml::listData(Networks::model()->findAll(array('order'=>'name')), 'id', 'name');

		$this->render('createByNetwork', array(
			'model'          => new DailyReport,
			'campaign'       => new Campaigns,
			'networks'       => $networks,
			'date'           => $date,
			'currentNetwork' => $currentNetwork,
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

		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($model);

		if(isset($_POST['DailyReport']))
		{
			$model->attributes=$_POST['DailyReport'];
			$model->conv_api = ConvLog::model()->count("campaign_id=:campaignid AND DATE(date)=:date", array(":campaignid"=>$model->campaigns_id, ":date"=>$model->date));
			$model->updateRevenue();
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
		$dataProvider=new CActiveDataProvider('DailyReport');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new DailyReport('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['DailyReport']))
			$model->attributes=$_GET['DailyReport'];

		$networks = CHtml::listData(Networks::model()->findAll(), 'name', 'name');

		$this->render('admin',array(
			'model'=>$model,
			'networks_names' => $networks,
		));
	}

	/**
	 * Get data for Graphic
	 */
	public function actionGraphic() {
		if ( isset($_POST['c_id']) && isset($_POST['net_id']) ) {
			$c_id = $_POST['c_id'];
			$net_id = $_POST['net_id'];
		} else {
			// echo json_encode("ERROR c_id or net_id missing");
			Yii::app()->end();
		}

		if ( isset($_POST['endDate']) ) {
			$endDate = new DateTime( $_POST['endDate'] );
		} else {
			$endDate = new DateTime("NOW");
		}

		
		if ( isset($_POST['startDate']) ) {
			$startDate = new DateTime( $_POST['startDate'] );
		} else {
			$startDate = new DateTime( $endDate->format("Y-m-d") );
			$startDate = $startDate->sub( DateInterval::createFromDateString('7 days') );
		}

		$model = new DailyReport();
		$response = $model->getGraphicDateRangeInfo( $c_id, $net_id, $startDate->format("Y-m-d"), $endDate->format("Y-m-d") );
		echo json_encode($response, JSON_NUMERIC_CHECK);
		Yii::app()->end();
	}

	public function actionUpdateColumn() {

		if ( isset($_POST["id"]) && isset($_POST["newValue"]) && isset($_POST["col"]) ) {
			$keyvalue   = $_POST["id"];
	        $newValue  = $_POST["newValue"];
	        $col = $_POST["col"];
		} else {
			// echo json_encode("ERROR missing params.");
			Yii::app()->end();
		}

		$model = DailyReport::model()->findByPk($keyvalue);
		$model[$col] = $newValue;
		$model->updateRevenue();

		if ( ! $model->update(array($col, 'revenue')) ) {
			// echo json_encode("ERROR updating daily report");
		}

		Yii::app()->end();
	}

	public function actionExcelReport()
	{
		if( isset($_POST['excel-report-daily']) ) {
			$this->renderPartial('excelReport', array(
				'model' => new DailyReport,
			));
		}

		$this->renderPartial('_excelReport', array(), false, true);
	}

	public function actionMultiRate($id)
	{

		$model = $this->loadModel($id);

		// 
		// Resolve multi rates submitted
		// 
		if ( isset($_POST['multiRate-submit']) ) {

			// walk through all MultiRate records submitted
			$i = 1;
			$model->conv_adv = 0;
			$model->revenue  = 0;
			while ( isset($_POST['MultiRate' . $i]) ) {

				$tmp_id = $_POST['MultiRate' . $i]['id'];
				if ($tmp_id != '') { // if MultiRate record already exists, load it.
					$modelMultiRate = MultiRate::model()->findByPk($tmp_id);
				} else {
					$modelMultiRate = new MultiRate;
				}
				$modelMultiRate->attributes=$_POST['MultiRate' . $i];
				// ignore records in blank
				if ( $modelMultiRate->rate == 0 && $modelMultiRate->conv == 0 && $tmp_id == '') {
					$i++;
					continue;
				}
				$model->conv_adv += $modelMultiRate->conv;
				// $model->revenue += ($modelMultiRate->conv * $modelMultiRate->rate);

				if ( !$modelMultiRate->save() ) {
					print "ERROR - " .  json_encode($modelMultiRate->getErrors()) . "<br>";
				}

				$i++;
			}

			$model->updateRevenue();
			if ( $model->save() )
				$this->redirect(array('admin'));
		}


		//
		// Render modal for multi rates
		//
		if ( !$model->campaigns->opportunities->country_id ) {
			print "ERROR - country_id NULL";
			Yii::app()->end();
		}

		$carriers = Carriers::model()->findAll( array('order'=>'mobile_brand', 'condition'=>'id_country=:cid', 'params'=>array(':cid'=>$model->campaigns->opportunities->country_id)) ); // FIXME que pasa si country_id == NULL ???

		$multi_rates = MultiRate::model()->findAll(array('order'=>'daily_report_id', 'condition'=>'daily_report_id=:id', 'params'=>array(':id'=>$id)));

		// populate info into carriers list
		foreach ($carriers as $carrier) {
			$found = false;
			// search every carrier in MultiRate, if carrier is not include then add to list with zero values
			foreach ($multi_rates as $multi_rate) {
				if ($multi_rate->carriers_id_carrier == $carrier->id_carrier) {
					$found = true;
					break;
				}
			}
			if ( !$found ) {
				$new = new MultiRate;
				$new->daily_report_id = $id;
				$new->carriers_id_carrier = $carrier->id_carrier;
				$multi_rates[] = $new;
			}
		}

		$this->renderPartial('_multiRate', array(
			'model'       => $model,
			'multi_rates' => $multi_rates,
			'currency'    => $model->campaigns->opportunities->ios->currency,
		), false, false);
	}	

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return DailyReport the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=DailyReport::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param DailyReport $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='daily-report-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	public function renderFormAjax($model)
	{
		//$networks = CHtml::listData(Networks::model()->findAll(array('order'=>'name')), 'id', 'name');
		$campaigns = CHtml::listData(Campaigns::model()->with(array('networks'))->findAll(array('order'=>'t.name', 'condition'=>'has_api = 0')), 'id',
			function($camp) { return $camp->getExternalName($camp->id); }
			);

		if ( $model->isNewRecord )
			$model->is_from_api = 0;

		$this->renderPartial('_form', array(
			'model'     => $model,
			//'networks'  => $networks,
			'campaigns' => $campaigns, 
		), false, true);
	}


	public function actionGetOpportunities($id=null)
	{
		// comentado provisoriamente, generar permiso de admin
		//$ios = Ios::model()->findAll( "advertisers_id=:advertiser AND commercial_id=:c_id", array(':advertiser'=>$id, ':c_id'=>Yii::app()->user->id) );
		if($id)$opps = Opportunities::model()->findAll( "account_manager_id=:accountManager", array(':accountManager'=>$id) );
		else $opps =Opportunities::model()->findAll();
		$response='<option value="">All opportunities</option>';
		foreach ($opps as $op) {
			$response .= '<option value="' . $op->id . '">' . $op->getVirtualName() . '</option>';
		}
		echo $response;
		Yii::app()->end();
	}
}

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
				'actions'=>array('index','getOpportunities','view','create','update','updateAjax','redirectAjax','admin','delete', 'graphic', 'updateColumn', 'excelReport', 'multiRate', 'createByNetwork', 'updateConvs2s', 'updateEditable'),
				'roles'=>array('admin', 'media', 'media_manager', 'business'),
			),
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','viewAjax','redirectAjax','admin', 'excelReport', 'multiRate'),
				'roles'=>array('commercial', 'finance', 'sem'),
			),
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('setNewFields','setAllNewFields'),
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
			$model->attributes  = $_POST['DailyReport'];
			
			$modelCampaign      = Campaigns::model()->findByPk($model->campaigns_id);
			$model->networks_id = $modelCampaign->networks_id;
			$model->conv_api    = ConvLog::model()->count("campaign_id=:campaignid AND DATE(date)=:date", array(":campaignid"=>$model->campaigns_id, ":date"=>$model->date));
			$model->updateRevenue();
			$model->setNewFields();
			if($model->save())
				$this->redirect(array('admin'));
		}

		$this->renderFormAjax($model);
	}

	public function actionCreateByNetwork()
	{
		$date = date('Y-m-d', strtotime('yesterday'));
		$currentNetwork = NULL;

		// If date and network are submitted then get values
		if ( isset($_GET['networkSubmit']) ) {
			$date           = $_GET['date'];
			$currentNetwork = $_GET['networks'];
		}

		if ( isset($_POST['saveSubmit']) ) {
			
			if ( Networks::model()->findByPk($_POST['DailyReport']['networks_id'])->use_vectors ) { // Is entry vectors
				$attr = $_POST['DailyReport'];
				$vector_id = $attr['campaigns_id'];
				$campaigns = VectorsHasCampaigns::model()->findAll('vectors_id=:vid', array(':vid' => $vector_id));

				if (empty($campaigns)) { // if vectors hasn't associated campaigns then exit
					$r         = new stdClass();
					$r->result = 'OK';
					$r->c_id   = $vector_id;
					echo json_encode($r);
					Yii::app()->end();
				}

				$porc = count($campaigns);
				$attr['imp']     = round($attr['imp']   / $porc, 0);
				$attr['imp_adv'] = round($attr['imp_adv']   / $porc, 0);
				$attr['clics']   = round($attr['clics'] / $porc, 0);
				$attr['spend']   = round($attr['spend'] / $porc, 2);

				foreach ($campaigns as $campaign) {
					$model=new DailyReport;
					$model->attributes = $attr;
					$model->campaigns_id = $campaign->campaigns_id;
					$r = $model->createByNetwork();
					$r->c_id = $vector_id;
					if ($r->result == 'ERROR') {
						echo json_encode($r);
						Yii::app()->end();
					}
				}
				echo json_encode($r);
			} else { // Is entry campaigns
				$model=new DailyReport;
				$model->attributes = $_POST['DailyReport'];
				echo json_encode($model->createByNetwork());
			}
			Yii::app()->end();
		}
		
		$networks = CHtml::listData(Networks::model()->findAll(array('order'=>'name', 'condition' => 'has_api=0')), 'id', 'name');

		$campaign = new Campaigns('search');
		$campaign->unsetAttributes();  // clear any default values

		$vector = new Vectors('search');
		$vector->unsetAttributes();  // clear any default values

		$daily = new DailyReport('search');
		$daily->unsetAttributes();  // clear any default values

		$this->render('createByNetwork', array(
			'model'          => $daily,
			'campaign'       => $campaign,
			'vector'         => $vector,
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
			$model->setNewFields();
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

	public function actionUpdateEditable(){
		$req = Yii::app()->getRequest();

		$model = DailyReport::model()->findByPk($req->getParam('pk'));
		$model[$req->getParam('name')] = $req->getParam('value');

		$model->updateRevenue();
		$model->setNewFields();
		$model->save();
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
		$model->setNewFields();

		if ( ! $model->save() ) {
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
			$model->setNewFields();
			if ( $model->save() ){
				$this->redirect(array('admin'));
			}else{
				var_dump($model->getErrors());
			}
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
		$campaigns = CHtml::listData(
			Campaigns::model()->with(array('networks','opportunities.ios'))->findAll(
				array('order'=>'ios.name', 'condition'=>'t.status = "Active" AND has_api = 0')
				), 
			'id',
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

	public function actionGetOpportunities()
	{
		// comentado provisoriamente, generar permiso de admin
		//$ios = Ios::model()->findAll( "advertisers_id=:advertiser AND commercial_id=:c_id", array(':advertiser'=>$id, ':c_id'=>Yii::app()->user->id) );
		$criteria=new CDbCriteria;
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
		$response='<option value="">All opportunities</option>';
		foreach ($opps as $op) {
			$response .= '<option value="' . $op->id . '">' . $op->getVirtualName() . '</option>';
		}
		echo $response;
		Yii::app()->end();
	}

	public function actionSetNewFields($id){

		if($model = DailyReport::model()->findByPk($id)){
			$model->setNewFields();
			$model->save();
			echo $id . " - updated";
		}else{
			echo $id . "- not exists";
		}

	}
	public function actionSetAllNewFields(){

		set_time_limit(100000);
		if(isset($_GET['date'])){
			$list = DailyReport::model()->findAll(array('condition'=>'date(date)="'.$_GET['date'].'"'));
			foreach ($list as $model) {
				$model->setNewFields();
				$model->save();
				echo $model->id . " - updated<br/>";
			}
		}else{
			echo "no date seted";
		}
	}

	public function actionUpdateConvs2s()
	{
		set_time_limit(100000);
		if(isset($_GET['date'])) {
			$list = DailyReport::model()->findAll(array('condition'=>'date(date)="'.$_GET['date'].'"'));
			foreach ($list as $model) {
				if ($model->conv_api == 0) {
					$model->conv_api    = ConvLog::model()->count("campaign_id=:campaignid AND DATE(date)=:date", array(":campaignid"=>$model->campaigns_id, ":date"=>$model->date));
					$model->updateRevenue();
					$model->setNewFields();
					$model->save();
					echo $model->id . " - updated<br/>";
				}
			}
		}else{
			echo "no date seted";
		}
	}

}

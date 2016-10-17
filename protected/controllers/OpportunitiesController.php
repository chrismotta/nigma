<?php

class OpportunitiesController extends Controller
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
				'actions'=>array('index','view','create','update','duplicate', 'response','admin','delete','getRegions', 'getCarriers', 'archived','managersDistribution','getOpportunities'),
				'roles'=>array('admin', 'commercial', 'commercial_manager', 'media_manager', 'account_manager','account_manager_admin'),
			),
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view','redirect','admin','archived'),
				'roles'=>array('businness', 'finance'),
			),
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('managersDistribution','getOpportunities'),
				'roles'=>array('media'),
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
		$this->renderPartial('_view',array(
			'model'=>$model,
		), false, true);
	}

	public function actionResponse($id){
		
		$action = isset($_GET['action']) ? $_GET['action'] : 'created';
		$this->layout='//layouts/modalIframe';
		$this->render('//layouts/mainResponse',array(
			'entity' => 'Opportunity',
			'action' => $action,
			'id'    => $id,
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Opportunities;

		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($model);

		if(isset($_POST['Opportunities']))
		{
			$model->budget           = NULL;
			$model->attributes=$_POST['Opportunities'];
			if($model->budget == '') $model->budget = new CDbExpression('NULL');
			$model->versionCreatedBy = Users::model()->findByPk(Yii::app()->user->id)->username;
			if($model->save())
				$this->redirect(array('response', 'id'=>$model->id, 'action'=>'created'));
			else
				echo json_encode($model->getErrors());
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

		if(isset($_POST['Opportunities']))
		{
			$model->budget           = NULL;
			$model->carriers_id      = NULL;
			$model->rate             = NULL;
			$model->attributes       = $_POST['Opportunities'];
			if($model->budget == '') $model->budget = new CDbExpression('NULL');
			$model->versionCreatedBy = Users::model()->findByPk(Yii::app()->user->id)->username;
			if($model->save())
				$this->redirect(array('response', 'id'=>$model->id, 'action'=>'updated'));
		}

		$this->renderFormAjax($model);
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

		if(isset($_POST['Opportunities']))
		{
			$model=new Opportunities;
			$model->attributes       = $_POST['Opportunities'];
			$model->versionCreatedBy = Users::model()->findByPk(Yii::app()->user->id)->username;
			if($model->save())
				$this->redirect(array('response', 'id'=>$model->id, 'action'=>'duplicate'));
		} 
		
		$this->renderFormAjax($new, 'Duplicate');
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
			case 'Inactive':
				$query ='UPDATE tags t
					RIGHT JOIN campaigns c ON(t.campaigns_id = c.id) 
					SET t.status = "Archived", c.status = "Archived" 
					WHERE c.opportunities_id = :pk
					';
				$return = Yii::app()->db->createCommand($query)->bindParam('pk',$id)->execute();

				$model->status = 'Archived';
				$model->save();
				break;
			case 'Archived':
				if ($model->regions->status == 'Active') {
					$model->status = 'Active';
					$model->save();
				} else {
					echo "To restore this item must restore the IO associated with it.";
					Yii::app()->end();
				}
				break;
		}

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Manages archived models.
	 */
	public function actionArchived()
	{
		$model=new Opportunities('search');
		$model->unsetAttributes();  // clear any default values
		$model->status = 'Archived';
		if(isset($_GET['Opportunities']))
			$model->attributes=$_GET['Opportunities'];

		$this->render('admin',array(
			'model'      => $model,
			'isArchived' => true,
		));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$this->redirect(array('admin'));
		
		// $dataProvider=new CActiveDataProvider('Opportunities');
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
	
		$model=new Opportunities('search');
		$model->unsetAttributes();  // clear any default values
		$model->status = array('Active', 'Inactive');
		if(isset($_GET['Opportunities']))
			$model->attributes=$_GET['Opportunities'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Opportunities the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Opportunities::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Opportunities $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='opportunities-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	public function renderFormAjax($model, $action=null)
	{
		$this->layout='//layouts/modalIframe';
	
		if ( $model->isNewRecord) {
			// Get only Advertisers and IOs that were created by the current user logged.
			// comentado provisoriamente, generar permiso de admin
			// $advertiser = CHtml::listData( Advertisers::model()->findAll( 'commercial_id=:c_id', array( ':c_id'=>Yii::app()->user->id) ), 'id', 'name' );
			
	        $criteria=new CDbCriteria;
	        $criteria->compare('status','Active');
	        $criteria->order = 'name';
	        if( UserManager::model()->isUserAssignToRole('account_manager') || UserManager::model()->isUserAssignToRole('account_manager_admin') )
	            $criteria->compare('cat', array('VAS','Affiliates','App Owners'));

			if( UserManager::model()->isUserAssignToRole('operation_manager') )
				$criteria->compare('cat', array('Networks','Incent'));	        

			$advertiser = CHtml::listData( Advertisers::model()->findAll($criteria), 'id', 'name' );

	        if($action == 'Duplicate'){
	        	$regions = Regions::model()->findByPk($model->regions_id);
				$adv = Advertisers::model()->findByPk($regions->financeEntities->advertisers_id);

				$model->advertiser_name = $adv->id;
				$regions = CHtml::listData( Regions::model()->findByAdvertisers($adv->id), 'id', 'country.name' );
				
	        }else{
				$regions = array();
	        }
			
			//$ios = CHtml::listData(Ios::model()->findAll( array('condition' => 'commercial_id=:c_id', 'params' => array( ':c_id'=>Yii::app()->user->id), 'order'=>'name') ), 'id', 'name');
		} else {
			// If update register, only show Opportunity's IO and Advertiser
			//$ios = Ios::model()->findByPk($model->ios_id);
			$regions = Regions::model()->findByPk($model->regions_id);
			$advertiser = Advertisers::model()->findByPk($regions->financeEntities->advertisers_id);
		}
		
		// Get users with authorization "media"
		$account = CHtml::listData(Users::model()->findUsersByRole(array('admin','account_manager_admin')), 'id', 'username' );

		// Get countries and carriers with status "Active"
		$country = CHtml::listData(GeoLocation::model()->findAll( array('order'=>'name', "condition"=>"status='Active' AND type='Country'") ), 'id_location', 'name' );
		
		if ( ($model->isNewRecord&&$action!='Duplicate') || !isset($model->regions_id) || !isset($model->regions->country_id)) {
			$carrier = array();
		} else {
			$carrier = CHtml::listData(Carriers::model()->findAll( array('order'=>'mobile_brand', "condition"=>"id_country=" . $model->regions->country_id . " AND status='Active'") ), 'id_carrier', 'mobile_brand' );
		}
		
		$connection_type = KHtml::enumItem($model, 'wifi');
		$model_adv = KHtml::enumItem($model, 'model_adv');
		$channels = KHtml::enumItem($model, 'channel');
		$model->open_budget = $model->budget == null ? 1 : 0;

		$this->render('_form',array(
			'model'      =>$model,
			'advertiser' =>$advertiser,
			//'ios'        =>$ios,
			'regions'    =>$regions,
			'account'    =>$account,
			'country'    =>$country,
			'carrier'    =>$carrier,
			'model_adv'  =>$model_adv,
			'channels'   =>$channels,
			'action'     =>$action,
			'connection_type'   =>$connection_type,
		));
	}

	public function actionGetIos($id)
	{
		// comentado provisoriamente, generar permiso de admin
		//$ios = Ios::model()->findAll( "advertisers_id=:advertiser AND commercial_id=:c_id", array(':advertiser'=>$id, ':c_id'=>Yii::app()->user->id) );
		$ios = Ios::model()->findAll( array('condition' => "advertisers_id=:advertiser", 'params' => array(':advertiser'=>$id), 'order' => 'name' ));

		$response='<option value="">Select an IOs</option>';
		foreach ($ios as $io) {
			$response .= '<option value="' . $io->id . '">' . $io->name . '</option>';
		}
		echo $response;
		Yii::app()->end();
	}

	public function actionGetRegions($id)
	{
		// comentado provisoriamente, generar permiso de admin
		//$ios = Ios::model()->findAll( "advertisers_id=:advertiser AND commercial_id=:c_id", array(':advertiser'=>$id, ':c_id'=>Yii::app()->user->id) );
		$regions = Regions::model()->findByAdvertisers($id);

		$response='<option value="">Select a Region</option>';
		foreach ($regions as $region) {
			$response .= '<option value="' . $region->id . '">' . $region->country->name . '</option>';
		}
		echo $response;
		Yii::app()->end();
	}

	public function actionGetCarriers($id)
	{
		$conutry = Regions::model()->findByPk($id)->country_id;
		$carriers = Carriers::model()->findAll( "id_country=:country AND status='Active'", array(':country'=>$conutry) );

		$response='<option value="">Select a carrier</option>';
		foreach ($carriers as $carrier) {
			$response .= '<option value="' . $carrier->id_carrier . '">' . $carrier->mobile_brand . '</option>';
		}
		echo $response;
		Yii::app()->end();
	}

	public function actionManagersDistribution()
	{

		if (FilterManager::model()->isUserTotalAccess('daily'))
			$accountManager = isset($_GET['accountManager']) ? $_GET['accountManager'] : NULL;
		else
			$accountManager = Yii::app()->user->getId();
		$advertisers    = isset($_GET['advertisers']) ? $_GET['advertisers'] : NULL;
		$countries      = isset($_GET['advertisersCountry']) ? $_GET['advertisersCountry'] : NULL;
		$models         = isset($_GET['modelAdvertisers']) ? $_GET['modelAdvertisers'] : NULL;

		$model=new Opportunities;
		$dataProvider=$model->getManagersDistribution($accountManager,$advertisers,$countries,$models);
		$this->render('managersDistribution',array(
			'model'          =>$model,
			'dataProvider'   =>$dataProvider,
			'accountManager' =>$accountManager,
			'advertisers'    =>$advertisers,
			'countries'      =>$countries,
			'models'         =>$models
		));
	}

	public function actionGetOpportunities()
	{
		// comentado provisoriamente, generar permiso de admin
		//$ios = Ios::model()->findAll( "advertisers_id=:advertiser AND commercial_id=:c_id", array(':advertiser'=>$id, ':c_id'=>Yii::app()->user->id) );
		$criteria=new CDbCriteria;
		$criteria->compare('t.status', 'Active');
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
		$response='<option value="">All opportunities</option>';
		foreach ($opps as $op) {
			$response .= '<option value="' . $op->id . '">' . $op->getVirtualName() . '</option>';
		}
		echo $response;
		Yii::app()->end();
	}
}

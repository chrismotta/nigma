<?php

class FinanceEntitiesController extends Controller
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
			array('allow',
				'actions'=>array('externalCreate'),
				'users'=>array('*'),
			),
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view','create','update', 'response','admin','delete', 'archived','redirect','getOpportunities'),
				'roles'=>array('admin', 'account_manager','account_manager_admin'),
			),
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view','redirect','admin','archived'),
				'roles'=>array('businness', 'finance'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		KHtml::paginationController();
	
		$model=new FinanceEntities('search');
		$accountManager   = isset($_GET['accountManager']) ? $_GET['accountManager'] : NULL;
		$advertiser   = isset($_GET['advertiser']) ? $_GET['advertiser'] : NULL;
		$country   = isset($_GET['country']) ? $_GET['country'] : NULL;
		$model->unsetAttributes();  // clear any default values
		$model->status = array('Active','Inactive');
		if(isset($_GET['FinanceEntities']))
			$model->attributes=$_GET['FinanceEntities'];

		$this->render('admin',array(
			'model'=>$model,
			'accountManager'=>$accountManager,
			'advertiser'=>$advertiser,
			'country'=>$country
		));
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
	$model = $this->loadModel($id);
		$this->renderPartial('_view', array( 
			'model'=>$model 
		), false, true);
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new FinanceEntities;

		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($model);

		if(isset($_POST['FinanceEntities']))
		{
			$model->attributes=$_POST['FinanceEntities'];
			if($model->save())
				$this->redirect(array('response', 'id'=>$model->id, 'action'=>'created'));
			else{
				echo json_encode($model->getErrors());
			return;
			}
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

		if(isset($_POST['FinanceEntities']))
		{
			$model->attributes=$_POST['FinanceEntities'];
			if($model->save())
				$this->redirect(array('response', 'id'=>$model->id, 'action'=>'updated'));
		}

		$this->renderFormAjax($model);
	}

	public function actionResponse($id){
		
		$action = isset($_GET['action']) ? $_GET['action'] : 'created';
		$this->layout='//layouts/modalIframe';
		$this->render('//layouts/mainResponse',array(
			'entity' => 'Finance Entity',
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
		$model = $this->loadModel($id);
		switch ($model->status) {
			case 'Active':
			case 'Inactive':
				if ( Regions::model()->count("finance_entities_id=:finance_entities_id AND status='Active'", array(":finance_entities_id" => $id)) > 0 ) {
					echo "To remove this item must delete the opportunities associated with it.";
					Yii::app()->end();
				} else {
					$model->status = 'Archived';
				}
				break;
				
			case 'Archived':
				if ($model->advertisers->status == 'Active') {
					$model->status = 'Active';
				} else {
					echo "To restore this item must restore the advertiser associated with it.";
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
		$this->redirect(array('admin'));
		
		// $dataProvider=new CActiveDataProvider('FinanceEntities');
		// $this->render('index',array(
		// 	'dataProvider'=>$dataProvider,
		// ));
	}

	/**
	 * Manages archived models.
	 */
	public function actionArchived()
	{
		$model=new FinanceEntities('search');
		$accountManager   = isset($_GET['accountManager']) ? $_GET['accountManager'] : NULL;
		$advertiser   = isset($_GET['advertiser']) ? $_GET['advertiser'] : NULL;
		$country   = isset($_GET['country']) ? $_GET['country'] : NULL;
		$model->unsetAttributes();  // clear any default values
		$model->status = 'Archived';
		if(isset($_GET['FinanceEntities']))
			$model->attributes=$_GET['FinanceEntities'];

		$this->render('admin',array(
			'model'=>$model,
			'accountManager'=>$accountManager,
			'advertiser'=>$advertiser,
			'country'=>$country,
			'isArchived' => true,
		));
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

		if(isset($_POST['FinanceEntities']))
		{
			$new->attributes=$_POST['FinanceEntities'];
			if($new->save())
				$this->redirect(array('admin'));
		} 

		$this->renderFormAjax($new);
	}

	public function actionExternalCreate()
	{
		if ( isset($_GET['tmltoken']) ) {
			$tmltoken = $_GET['tmltoken'];
		} else {
			echo "ERROR invalid parameters <br>";
			Yii::app()->end();	
		}

		$external = ExternalIoForm::model()->find( 'hash=:tmltoken', array(':tmltoken' => $tmltoken) );

		// Validate hash expiration time
		$validTime = ExternalIoForm::getExpirationHashTime();
		if ( ! $external || ( (time() - strtotime($external->create_date) ) > $validTime ) ) { // hash expired
			$this->render('externalCreate', array(
				'action'   => 'expire',
			));
			Yii::app()->end();
		}

		if ( $external->status == 'Submitted' ) {
			$this->render('externalCreate', array(
				'action'   => 'alreadySubmitted',
			));
			Yii::app()->end();
		}

		$fe = new FinanceEntities;

		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($fe);

		if(isset($_POST['Ios'])) {
			echo "submited";
			$fe = new FinanceEntities;
			$fe->attributes=$_POST['Ios'];
			$fe->prospect = NULL; // FIXME completar con prospect correspondiente
			if( $fe->save() )
				$this->render('externalCreate', array(
					'action'=> 'submit',
				));
			else
				echo "error saveing" . json_encode($fe->getErrors());
			Yii::app()->end();
		}

		$currency   = KHtml::enumItem($fe, 'currency');
		$entity     = KHtml::enumItem($fe, 'entity');
		$advertiser = Advertisers::model()->findByPk($external->advertisers_id);
		$country = CHtml::listData(GeoLocation::model()->findAll( array('order'=>'name', "condition"=>"status='Active' AND type='Country'") ), 'id_location', 'name' );
		$commercial = Users::model()->findByPk($external->commercial_id);;

		$fe->prospect = 1;	// FIXME completar con prospect correspondiente
		$fe->commercial_id = $commercial->id;
		$fe->advertisers_id = $advertiser->id;

		$this->render('externalCreate', array(
			'action'     => 'form',
			'model'      => $fe,
			'currency'   => $currency,
			'entity'     => $entity,
			'commercial' => $commercial,
			'advertiser' => $advertiser,
			'country'    => $country,
		));
		
		
		echo "OK submitting IO <br>";
		Yii::app()->end();
	}

	public function actionGeneratePdf($id) 
	{
		$model = $this->loadModel($id);

		if (isset($_POST['submit'])) {

			if (!isset($_POST['opp_ids'])) // no opportunities selected
				$this->redirect(array('admin'));

			$pdf = PDFInsertionOrder::doc();
	        $pdf->setData( array(
				'advertiser'    => Advertisers::model()->findByPk($model->advertisers_id),
				'io'            => $model,
				'opportunities' => $_POST['opp_ids'],
	        ));
	        $pdf->output();
	        Yii::app()->end();
	    }

	    $this->renderPartial('_generatePDF', array(
	    	'model' => $model,
	    ), false, true);
	}

	public function actionViewPdf($id) 
	{
		$model = $this->loadModel($id);
		$path = PDF::getPath();

		if ( file_exists($path . $model->pdf_name) ) {
			$info = pathinfo($model->pdf_name);
			if ( $info['extension'] == 'pdf') { // pdf file show in a new tab
				$this->redirect( array('uploads/' . $model->pdf_name) );
			} else { // other files download
				Yii::app()->getRequest()->sendFile( $model->pdf_name, file_get_contents($path . $model->pdf_name) );
			}
		} else {
			throw new CHttpException(404,"The file doesn't exist.");
		}
		Yii::app()->end();
	}

	public function actionUploadPdf($id) 
	{
		$model = $this->loadModel($id);
		$path = PDF::getPath();

		if(isset($_POST['submit'])) {

			if ( is_uploaded_file($_FILES["upload-file"]["tmp_name"]) ) {
				// Create new name for file
				$extension = substr( $_FILES["upload-file"]["name"], strrpos($_FILES["upload-file"]["name"], '.') );
				$newName = 'Adv-' . $model->advertisers_id . '_IO-' . $id . $extension;
				
				if ( ! move_uploaded_file($_FILES["upload-file"]['tmp_name'], $path . $newName) ) {
					Yii::app()->end();
				}

				// Update prospect to complete
				$model->prospect = 10;
				$model->pdf_name = $newName;
				$model->save();
			}
			$this->redirect(array('admin'));
		}

		$this->renderPartial('_uploadPDF', array(
			'model' => $model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Ios the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=FinanceEntities::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Ios $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='financeEntities-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	public function renderFormAjax($model) 
	{
		$this->layout='//layouts/modalIframe';
	
		$pre_post_payment   = KHtml::enumItem($model, 'pre_post_payment');
		$currency   = KHtml::enumItem($model, 'currency');
		$entity     = KHtml::enumItem($model, 'entity');

		$criteria=new CDbCriteria;
		$criteria->order = 'name';
		$criteria->compare('status', 'Active');
		if( UserManager::model()->isUserAssignToRole('account_manager') || UserManager::model()->isUserAssignToRole('account_manager_admin') )
			$criteria->compare('cat', array('VAS','Affiliates','App Owners'));
		$advertiser = CHtml::listData(Advertisers::model()->findAll($criteria), 'id', 'name'); 
		$country = CHtml::listData(GeoLocation::model()->findAll( array('order'=>'name', "condition"=>"status='Active' AND type='Country'") ), 'id_location', 'name' );

		if ( $model->isNewRecord ) {
			$model->commercial_id = Yii::app()->user->id;
			$commercial = Users::model()->findByPk($model->commercial_id);;
		} else {
			$commercial = Users::model()->findByPk($model->commercial_id);
		}

		$this->render('_form',array(
			'model'      =>$model,
			'currency'   =>$currency,
			'entity'     =>$entity,
			'commercial' =>$commercial,
			'advertiser' =>$advertiser,
			'country'    =>$country,
			'pre_post_payment'=>$pre_post_payment,
		));
	}

	public function actionGetOpportunities($id)
	{
		$format = isset($_GET['format']) ? $_GET['format'] : 'select';
		$data=array();
		$opportunities=array();
		$criteria=new CDbCriteria;
		$criteria->with=array('regions','regions.financeEntities');
		$criteria->compare('financeEntities.id',$id);
		$criteria->compare('t.status','Active');
		$response='';
		//$response='<option value="">All Carriers</option>';
		$i=0;		
		foreach (Opportunities::model()->findAll($criteria) as $opp) {
			if($format=='select')
				$response .= '<option value="' . $opp->id . '">' . $opp->getVirtualName() . '</option>';
			elseif($format=='check')
				$response.='<input value="'.$opp->id.'" id="opp_ids_'.$i.'" checked="checked" name="opp_ids[]" type="checkbox"> 
							<label for="opp_ids_'.$i.'">'.$opp->getVirtualName().'</label><br>';
			$i++;
		}
		echo $response;
		Yii::app()->end();
	}

}
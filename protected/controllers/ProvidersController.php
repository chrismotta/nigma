<?php

class ProvidersController extends Controller
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
				'actions'=>array('exportPdf','viewPdf','uploadPdf','agreementPdf','viewAgreement','generalDataEntry','financeDataEntry','externalForm','prospect','create','delete'),
				'roles'=>array('admin', 'commercial', 'commercial_manager', 'media_manager','finance'),
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
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Providers the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Providers::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='providers-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	public function actionExportPdf($id)
	{
		$pdf = PDFProviders::doc();
        $pdf->setData(array('provider' => $this->loadModel($id)));
        $pdf->output();
        Yii::app()->end();
	}

	public function actionAgreementPdf($id)
	{
		$pdf = PDFAgreement::doc();
        $pdf->setData(array('provider' => $this->loadModel($id)));
        $pdf->output();
        Yii::app()->end();
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

	public function actionViewAgreement($id) 
	{
		$model = $this->loadModel($id);
		$path = PDF::getPath();

		if ( file_exists($path . $model->pdf_agreement) ) {
			$info = pathinfo($model->pdf_agreement);
			if ( $info['extension'] == 'pdf') { // pdf file show in a new tab
				$this->redirect( array('uploads/' . $model->pdf_agreement) );
			} else { // other files download
				Yii::app()->getRequest()->sendFile( $model->pdf_agreement, file_get_contents($path . $model->pdf_agreement) );
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
		$type = $_POST['type'];
		if(isset($_POST['submit'])) {
			if($type=='io')
			{
				if ( is_uploaded_file($_FILES["upload-file"]["tmp_name"]) ) {
					// Create new name for file
					$extension = substr( $_FILES["upload-file"]["name"], strrpos($_FILES["upload-file"]["name"], '.') );
					$newName = 'Prov-' . $id . '_IO' . $extension;
					
					if ( ! move_uploaded_file($_FILES["upload-file"]['tmp_name'], $path . $newName) ) {
						Yii::app()->end();
					}

					// Update prospect to complete
					$model->prospect = 10;
					$model->pdf_name = $newName;
					$model->save();
				}
				$this->redirect($_SERVER['HTTP_REFERER']);
			}
			elseif($type=='agreement')
			{
				if ( is_uploaded_file($_FILES["upload-file"]["tmp_name"]) ) {
					// Create new name for file
					$extension = substr( $_FILES["upload-file"]["name"], strrpos($_FILES["upload-file"]["name"], '.') );
					$newName = 'Prov-' . $id . '_Agreement' . $extension;
					
					if ( ! move_uploaded_file($_FILES["upload-file"]['tmp_name'], $path . $newName) ) {
						Yii::app()->end();
					}

					// Update prospect to complete
					$model->prospect = 10;
					$model->pdf_agreement = $newName;
					$model->save();
				}
				$this->redirect($_SERVER['HTTP_REFERER']);
			}
		}

		$this->renderPartial('_uploadPDF', array(
			'model' => $model,
			'type'	=> $type
		));
	}
	public function actionGeneralDataEntry($hash=null)
	{

		if ( isset($hash) ) {
			$ktoken = $hash;
		} else {
			echo "ERROR invalid parameters <br>";
			Yii::app()->end();	
		}
		$external = ExternalProviderForm::model()->find( 'hash=:ktoken', array(':ktoken' => $ktoken) );

		// Validate hash expiration time
		$validTime = ExternalProviderForm::getExpirationHashTime();
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

		$providers = Providers::model()->findByPk($external->providers_id);

		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($providers);

		if(isset($_POST['Providers'])) {
			echo "submited";
			$providers = Providers::model()->findByPk($_POST['Providers']['id']);
			$providers->attributes=$_POST['Providers'];
			$providers->prospect = 10; // FIXME completar con prospect correspondiente
			$external->status='Submitted';
			if($external->save())
			{
				if( $providers->save() )
					$this->render('externalCreate', array(
						'action'=> 'submit',
					));
				else
					echo "error saveing" . json_encode($providers->getErrors());
			}
			Yii::app()->end();
		}
		$providers->prospect = 1;	// FIXME completar con prospect correspondiente
		$providers->unsetAttributes();
		$this->render('externalCreate', array(
			'action'     => 'generalForm',
			'model'      => $providers,
			'id'=>$external->providers_id,
		));
		
		
		echo "OK submitting Provider <br>";
		Yii::app()->end();
	}

	public function actionFinanceDataEntry($hash=null)
	{

		if ( isset($hash) ) {
			$ktoken = $hash;
		} else {
			echo "ERROR invalid parameters <br>";
			Yii::app()->end();	
		}
		$external = ExternalProviderForm::model()->find( 'hash=:ktoken', array(':ktoken' => $ktoken) );

		// Validate hash expiration time
		$validTime = ExternalProviderForm::getExpirationHashTime();
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

		$providers = Providers::model()->findByPk($external->providers_id);

		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($providers);

		if(isset($_POST['Providers'])) {
			echo "submited";
			$providers = Providers::model()->findByPk($_POST['Providers']['id']);
			$providers->attributes=$_POST['Providers'];
			$providers->prospect = 10; // FIXME completar con prospect correspondiente
			$external->status='Submitted';
			if($external->save())
			{
				if( $providers->save() )
					$this->render('externalCreate', array(
						'action'=> 'submit',
					));
				else
					echo "error saveing" . json_encode($providers->getErrors());
			}
			Yii::app()->end();
		}
		$providers->prospect = 1;	// FIXME completar con prospect correspondiente
		// $providers->unsetAttributes();
		$this->render('externalCreate', array(
			'action'     => 'financeForm',
			'model'      => $providers,
			'id'=>$external->providers_id,
		));
		
		
		echo "OK submitting Provider <br>";
		Yii::app()->end();
	}

	public function actionExternalForm($id)
	{
		$validTime = ExternalProviderForm::getExpirationHashTime();
		
		$model = $this->loadModel($id);
		$type=isset($_POST['type']) ? $_POST['type'] : null;
		// FIXME si se entra mas de un IO???
		$formURL = ExternalProviderForm::model()->find( "providers_id=:prov AND status='Pending' AND type=:type", array(':prov'=>$id,':type'=>ucwords($type)) );

		if ( $formURL ) { // validate expiration hash's time
			if ( (time() - strtotime($formURL->create_date) ) > $validTime ) { // hash expired
				$formURL->create_date = date( 'Y-m-d H:i:s', time() );
				$formURL->type=ucwords($type);
				$formURL->hash = sha1($id . $model->name . $formURL->create_date);
				if ( ! $formURL->save() ) {
					// echo "ERROR saving new hash <br>";
					return;
				}
			} // else, hash not expired, do nothing
		} else { // Create new row for ExternalIoForm
			$formURL                 = new ExternalProviderForm;
			$formURL->create_date = date( 'Y-m-d H:i:s', time() );
			$formURL->providers_id = $id;
			$formURL->type=ucwords($type);
			// $formURL->commercial_id  = Yii::app()->user->id;
			$formURL->hash           = sha1($id . $model->name . $formURL->create_date);
			if ( ! $formURL->save() ) {
				// echo "ERROR saving new External IO Form <br>";
				return;
			}
		}
		if($type=='general')
			$url   = Yii::app()->getBaseUrl(true) . '/providers/generalDataEntry/' . $formURL->hash;
		elseif($type=='finance')
			$url   = Yii::app()->getBaseUrl(true) . '/providers/financeDataEntry/' . $formURL->hash;
		$this->renderPartial('_externalForm',array(
			'model'    => $model,
			'formURL'  => $formURL,
			'url'      => $url,
			'timeLeft' => round( ( $validTime - (time() - strtotime($formURL->create_date)) ) / 3600 ), // date in hours to hash expiration.
		), false, true);
	}

	/**
	 * Manages all models.
	 */
	public function actionProspect()
	{
		$model=new Providers('search');
		$model->unsetAttributes();  // clear any default values
		// $model->providers->status = 'Active';
		if(isset($_GET['Providers']))
			$model->attributes=$_GET['Providers'];

		$this->render('prospect',array(
			'model'=>$model,
		));
	}
	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Providers;
		$modelAffi=new Affiliates;
		$modelNet=new Networks;
		$modelPub=new Publishers;
		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($model);
		// $this->performAjaxValidation($modelProv);
		if(isset($_POST['Providers']))
		{
			$defaultAttributes=array(
				'status'=>'Inactive',
				'currency'=>'USD',
				'model'=>'CPA',
				'net_payment'=>0,
				'commercial_name'=>'prospect',
				'state'=>'state',
				'zip_code'=>'000',
				'tax_id'=>'000',
				'start_date'=>'0000-00-00',

				);
			$attributes=array(
				'name'=>$_POST['Providers']['name'],
				'entity'=>$_POST['Providers']['entity'],
				'prospect'=>1
				);
			$attributes=array_merge($defaultAttributes,$attributes);
			$model->attributes=$attributes;
			if ($model->save()) {
				$type=$model->getAllTypes()[$_POST['type']];
				switch ($type) {
					case 'Affiliates':
						$modelAffi->attributes=array('providers_id'=>$model->id,'users_id'=>Yii::App()->user->getId());
						if($modelAffi->save())
							$this->redirect(array('prospect'));							
						break;
					case 'Networks':
						$modelNet->attributes=array('providers_id'=>$model->id);
						if($modelNet->save())
							$this->redirect(array('prospect'));							
						break;
					case 'Publishers':
						$modelPub->attributes=array('providers_id'=>$model->id,'account_manager_id'=>Yii::App()->user->getId());
						if($modelPub->save())
							$this->redirect(array('prospect'));							
						break;
					
					default:
						break;
				}
			}
			else
				echo json_encode($model->getErrors());
		}

		$this->renderFormAjax($model);
	}
	/**
	 * [renderFormAjax description]
	 * @param  [type] $modelAffi [description]
	 * @param  [type] $modelProv [description]
	 * @return [type]        	 [description]
	 */
	private function renderFormAjax($model)
	{
		// $users    = CHtml::listData(Users::model()->findAll("status='Active'"), 'id', 'username');

		$this->renderPartial('_formProspect',array(
			'model' =>$model,
		), false, true);
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$model=$this->loadModel($id);
		$model->status = 'Archived';
		$model->save();
		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}
}
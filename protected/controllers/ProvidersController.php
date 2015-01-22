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
				'actions'=>array('exportPdf','viewPdf','uploadPdf','agreementPdf','viewAgreement'),
				'roles'=>array('admin', 'commercial', 'commercial_manager', 'media_manager'),
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

}
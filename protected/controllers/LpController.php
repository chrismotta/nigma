<?php

class LpController extends Controller
{

	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column1', meaning
	 * using two-column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout='//layouts/landing';


	public function actionIndex(){
		echo 'Not defined';
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		if(isset($_POST['Lp'])){
			$lp = $_POST['Lp'];
			if(isset($lp['prefix']) && isset($lp['number']) && isset($lp['tc'])){
				$status = 'thankyou';
			}else{
				$status = 'validate';
			}
		}else{
			$status = 'form';
		}

		$model = $this->loadModel($id);

		$backgroundImage = isset($model->background_images_id) ? $model->backgroundImages->getImagePath($model->backgroundImages->file_name) : null; 
		$headlineImage = isset($model->headline_images_id) ? $model->headlineImages->getImagePath($model->headlineImages->file_name) : null; 
		$bylineImage = isset($model->byline_images_id) ? $model->bylineImages->getImagePath($model->bylineImages->file_name) : null; 

		$this->render('view', array( 
			'model'=>$model,
			'backgroundImage'=>$backgroundImage,
			'headlineImage'=>$headlineImage,
			'bylineImage'=>$bylineImage,
			'status'=>$status, 
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
		$model=Landings::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
}
?>
<?php

class StatsController extends Controller
{
	public function actionIndex(){
		echo 'index';
	}

	public function actionImpressions()
	{
		KHtml::paginationController();
		
		$model = new FImpressions('search');
		$model->unsetAttributes();
		
		// if(isset($_POST['ImpLog']))
		// 	$model->attributes=$_POST['ImpLog'];

		$this->render('impressions', 
			array(
				'model'=>$model
				));
	}


}

?>
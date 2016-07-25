<?php

class ClickMacrosController extends Controller
{
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
				'actions'=>array('index'),
				'roles'=>array('admin',),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	public function actionIndex()
	{
		if(isset($_POST['ClickMacros'])){
			
			$clickMacros = $_POST['ClickMacros'];
			
			$model = new ClickMacros('list');
			$model->date_start = date('Y-m-d', strtotime($clickMacros['date_start']));
			$model->date_end = date('Y-m-d', strtotime($clickMacros['date_end']));
			$model->list = explode("\r\n", $clickMacros['list']);

			$dp = $model->list();

			if( $dp->getData() ){

				foreach ($dp->getData() as $data) {
					$csvData[] = array(
						'opportunity' => $data->opportunity,
						'campaign' => $data->campaign,
						'publisher' => $data->publisher,
						'pubid' => $data->pubid,
						'clicks' => $data->clicks,
						);
				}

				$csv = new ECSVExport( $csvData );
				$csv->setEnclosure(chr(0));//replace enclosure with caracter
				$content = $csv->toCSV(); 

				Yii::app()->getRequest()->sendFile('conv.csv', $content, "text/csv", false);
				
			}else{
				$this->render('index', array('msg'=>'No data available'));

			}
			
		}else{

			$this->render('index');
		}

	}

	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
}
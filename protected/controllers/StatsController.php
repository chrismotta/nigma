<?php

class StatsController extends Controller
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
				'actions'=>array('index','impressions'),
				'roles'=>array('admin', 'media_manager', 'external', 'media_buyer', 'media_buyer_admin','operation_manager'),
				),
			array('deny',  // deny all users
				'users'=>array('*'),
				),
		);
	}

	public function actionIndex(){
		echo 'ERROR';
	}

	public function actionImpressions()
	{

		KHtml::paginationController();
		
		$model = new FImpCompact('search');
		$model->unsetAttributes();
		
		// if(isset($_POST['ImpLog']))
		// 	$model->attributes=$_POST['ImpLog'];
		// 	
		if ( isset($_REQUEST['download']) )
		{
			$this->_sendCsvFile( $model );
		}
		$this->render('impressions', 
			array(
				'model'=>$model
				));
	}

	protected function _sendCsvFile ( $model )
	{
		$csvData = array();
		$dateStart = date('Y-m-d', strtotime($_REQUEST['dateStart']));
		$group  = array_merge($_REQUEST['group1'],$_REQUEST['group2']);
		$sum = $_REQUEST['sum'];
		$dp = $model->cache(3600)->search(false, null, true);

		foreach ($dp->getData() as $data) {
			$row = array();

			if ( $group['date'] )
				$row['Date']      				= $data->date;		


			if ( $group['hour'] )
				$row['Hour']      				= $data->hour;					

			if ( $group['provider'] )
				$row['Provider']      			= $data->provider;


			if ( $group['placement'] )
				$row['Placement']      			= $data->placement;


			if ( $group['tag'] )
				$row['Tag']      				= str_replace(',', '', $data->tag);


			if ( $group['advertiser'] )
				$row['Advertiser']      		= $data->advertiser;


			if ( $group['campaign'] )
				$row['Campaign']      			= $data->campaign;


			if ( $group['pubid'] )
				$row['Pub ID']      			= $data->pubid;


			if ( $group['country'] )
				$row['Country']      			= $data->country;

			if ( $group['os_type'] )
				$row['OS']      				= $data->os_type;

			if ( $group['os_version'] )
				$row['OS Version'] 				= $data->os_version;

			if ( $group['device_type'] )
				$row['Device Type']      		= $data->device_type;

			if ( $group['device_brand'] )
				$row['Device Brand']      		= $data->device_brand;			

			if ( $group['device_model'] )
				$row['Device Model']      		= $data->device_model;
			
			if ( $group['browser_type'] )
				$row['Browser']      			= $data->browser_type;

			if ( $group['browser_version'] )
				$row['Browser Version']      	= $data->browser_version;

			if ( $group['connection_type'] )
				$row['Connection Type']      	= $data->connection_type;

			if ( $group['carrier'] )
				$row['Carrier']      			= $data->carrier;	

			if ( $sum['ad_req'] )
				$row['ad_req']          		= str_replace(',', '', $data->ad_req);			

			if ( $sum['impressions'] )
				$row['Impressions']      		= str_replace(',', '', $data->impressions);			

			if ( $sum['unique_imps'] )
				$row['Unique Imps']      		= str_replace(',', '', $data->unique_imps);

			if ( $sum['revenue'] )
				$row['Revenue']      			= $data->revenue;

			if ( $sum['cost'] )
				$row['Cost']      				= $data->cost;												
			if ( $sum['profit'] )
				$row['Profit']      			= $data->profit;

			if ( $sum['revenue_eCPM'] )
				$row['ReCPM']      				= $data->revenue_eCPM;												

			if ( $sum['cost_eCPM'] )
				$row['CeCPM']      				= $data->cost_eCPM;	


			if ( $sum['profit_eCPM'] )
				$row['PeCPM']      				= $data->profit_eCPM;	

			$csvData[] = $row;
		}

		$csv = new ECSVExport( $csvData );
		$csv->setEnclosure(chr(0));//replace enclosure with caracter
		$csv->setHeader( 'content-type', 'application/csv;charset=UTF-8' );
		$content = $csv->toCSV();   

		if(isset($_REQUEST['v']))
			echo str_replace("\n", '<br/>', $content);
		else
		{
			$filename = 'ImpReport_'.date("Y-m-d", strtotime($dateStart)).'.csv';
			Yii::app()->getRequest()->sendFile($filename, $content, "text/csv", true);		
		}		
	}	


}

?>
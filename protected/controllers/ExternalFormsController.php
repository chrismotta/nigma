<?php

class ExternalFormsController extends Controller
{
	public function actionIndex()
	{
		$this->render('index');
	}
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('revenueValidation','revenueApproved','revenueDisputed','changeEmail'),
				'roles'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
			array('allow',  // allow all users
				'actions'=>array('trafficReport'),
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Reports for OneClick partners
	 * @param  [type] $hash [description] md5 (id + ios_id)
	 * @return [type] JSon  [description] traffic report in json format
	 */
	public function actionTrafficReport($hash=null){
		if(!$hash) die('ERROR: Hash needed!');

		$startDate = isset($_GET['date-start']) ? date('Y-m-d', strtotime($_GET['date-start'])) : date('Y-m-d', strtotime('yesterday'));
		$endDate   = isset($_GET['date-end'])   ? date('Y-m-d', strtotime($_GET['date-end'])) : date('Y-m-d', strtotime('yesterday'));;

		//$return = array($oppID, $startDate, $endDate);
		$model = new DailyReport();
		$data = $model->trafficReport($hash, $startDate, $endDate);
		// $this->widget('bootstrap.widgets.TbGridView', array(

		foreach ($data as $key => $value) {
			$row['date']       = $value['date'];
			$row['url']        = $value['campaigns']['url'];
			$row['imp']        = $value['imp'];
			//$row['imp_fake'] = $value['conv_adv'];
			//$row['imp_true'] = $value['imp_adv'];
			$row['click']      = $value['clics'];
			$row['conv']       = $value['conv_api'];
			$return[]          = $row;
			
			// echo $value['date'] .' - ';
			// echo $value['campaigns']['url'] .' - ';
			// echo $value['imp'] .' - ';
			// echo $value['clics'] .' - ';
			// echo $value['conv_api'] .' - ';
			// echo $value['campaigns']['opportunities_id'];
			// echo '<hr/>';
			// var_dump($value);
			// echo '<hr/><hr/>';
		}
		if(isset($return)){
			echo json_encode($return);
		}else{
			echo json_encode(array('no data available'));
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
	public function actionRevenueValidation()
	{
		$token =isset($_GET['token']) ? $_GET['token'] : null;		
		$IosValidation =new IosValidation;
		$IosModel=new Ios;
		$transactionCount=new TransactionCount;
		if(!$model=$IosValidation->loadModelByToken($token))
		{
			$this->render('revenueValidation',array(
			'error'        =>"Invalid token",
		));
		}
		$consolidated=array();
		$totals['revenue']=0;
		$totals['conv']=0;
		$clients = $IosModel->getClients(date('m', strtotime($model->period)),date('Y', strtotime($model->period)),null,$model->ios_id,null,null,null,null,'profile');
		$consolidated=array();
		$i=0;
		$aux=array();
		if($count=$transactionCount->getTotalsCarrier($IosModel->id,$model->period))
		{
			foreach ($count as $value) {
				foreach ($clients['data'] as $key => $data) {
					if($data['carrier']==$value->carriers_id_carrier){
						if($data['rate']==$value->rate)
						{
							$clients['data'][$key]['conv']    +=$value->volume;
							$clients['data'][$key]['revenue'] +=$value->total;
						}
						else
						{							
							$aux[$i]=$data;						
							$aux[$i]['conv']=$value->volume;
							$aux[$i]['revenue']=$value->total;
							$aux[$i]['rate']=$value->rate;				
							$i++;		
						}

					}

				}				
			}
			foreach ($aux as $value) {
				$consolidated[]=$value;
			}
		}
		foreach ($clients['data'] as $value) {
			$consolidated[]=$value;
		}
		$totals['revenue']=0;
		$totals['conv']=0;
		foreach ($consolidated as $value) {
			$totals['revenue']+=$value['revenue'];
			$totals['conv']+=$value['conv'];
			
		}
		$dataProvider=new CArrayDataProvider($consolidated, array(
		    'id'=>'consolidated',
		    'sort'=>array(
		        'attributes'=>array(
		             'id', 'name', 'model', 'entity', 'currency', 'rate', 'conv','revenue', 'carrier','opportunitie','country','mobileBrand','product'
		        ),
		    ),
		    'pagination'=>array(
		        'pageSize'=>30,
		    ),
		));


		$this->render('revenueValidation',array(
			'model'        =>$model,
			'dataProvider' =>$dataProvider,
			'totals'       =>$totals,
		));
	}

	public function actionRevenueApproved()
	{
		$token =isset($_POST['token']) ? $_POST['token'] : null;		
		$comment =isset($_POST['comment']) ? $_POST['comment'] : null;		
		$model =new IosValidation;
		$this->renderPartial('revenueApproved',array(
			'model'=>$model->loadModelByToken($token),
			'comment'=>$comment,
		));
	}

	public function actionRevenueDisputed()
	{
		$token =isset($_POST['token']) ? $_POST['token'] : null;		
		$comment =isset($_POST['comment']) ? $_POST['comment'] : null;		
		$model =new IosValidation;
		$this->renderPartial('revenueDisputed',array(
			'model'=>$model->loadModelByToken($token),
			'comment'=>$comment,
		));
	}

	public function actionChangeEmail()
	{
		$token         =isset($_POST['token']) ? $_POST['token'] : null;		
		$email         =isset($_POST['email_validation']) ? $_POST['email_validation'] : null;		
		$iosValidation =new IosValidation;
		$ios           =new Ios;
		$model         =$iosValidation->loadModelByToken($token);
		$this->renderPartial('changeEmail',array(
			'model' =>$model,
			'ios'   =>$ios->findByPk($model->ios_id),
			'email' =>$email
		));
	}
}
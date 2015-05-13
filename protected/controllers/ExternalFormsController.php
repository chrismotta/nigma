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
	public function actionRevenueValidation($hash=null)
	{
		$token =isset($hash) ? $hash : null;		
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
		if($count=$transactionCount->getTotalsCarrier($model->ios_id,$model->period))
		{
			foreach ($count as $value) {
				$found = false;
				foreach ($clients['data'] as $key => $data) {
					if($data['country']==$value->getCountry() && $data['product']==$value->product && $data['carrier']==$value->carriers_id_carrier) {
						if($data['rate']==$value->rate) {
							$clients['data'][$key]['conv']    +=$value->volume;
							$clients['data'][$key]['revenue'] +=$value->total;
							$found = true;
							break;
						}
					}
				}
				if (!$found) {
					$aux[$i]            =$data;
					$aux[$i]['conv']    =$value->volume;
					$aux[$i]['revenue'] =$value->total;
					$aux[$i]['rate']    =$value->rate;				
					$i++;		
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
		$token   =isset($_POST['token']) ? $_POST['token'] : null;		
		$comment =isset($_POST['comment']) ? $_POST['comment'] : null;		
		$model   =new IosValidation;
		$model   =$model->loadModelByToken($token);
		$comment =$comment;
		
		$status='Approved';
		$log=new ValidationLog;
		switch ($model->status) {
			case 'Approved':
				echo 'Revenue already approved';
				break;
			
			case 'Expired':
				echo 'Revenue is expired';
				break;
			
			default:
				$model->attributes=array('status'=>$status,'comment'=>$comment);
				if($model->save())
				{
					echo 'Revenue Approved';			
					$log->loadLog($model->id,$status);
				}
				else
					print_r($model->getErrors());
				break;
		}
		Yii::app()->end();		
	}

	public function actionRevenueDisputed()
	{
		$token   =isset($_POST['token']) ? $_POST['token'] : null;		
		$comment =isset($_POST['comment']) ? $_POST['comment'] : null;		
		$model   =new IosValidation;
		$model   =$model->loadModelByToken($token);
		$comment =$comment;
		$status ='Disputed';
		$log    =new ValidationLog;

		switch ($model->status) {
			case 'Disputed':
				echo 'Revenue already disputed';
				break;
			
			case 'Expired':
				echo 'Revenue is expired';
				break;
			
			default:
				$model->attributes=array('status'=>$status,'comment'=>$comment);
				if($model->save())
				{
					echo 'Revenue Disputed';
					$log->loadLog($model->id,$status);
				}
				else
					print_r($model->getErrors());
				break;
		}
		Yii::app()->end();
	}

	public function actionChangeEmail()
	{
		$token         =isset($_POST['token']) ? $_POST['token'] : null;		
		$email         =isset($_POST['email_validation']) ? $_POST['email_validation'] : null;		
		$iosValidation =new IosValidation;
		$ios           =new Ios;
		$model         =$iosValidation->loadModelByToken($token);
		$ios           =$ios->findByPk($model->ios_id);
		
		$status ="Email Changed";
		$log    =new ValidationLog;
		$ios->attributes=array('email_validation'=>$email,'zip_code'=>'0');
		if($ios->save())
		{
			echo $status;
			// Re-envio de mail
			$body = '
							<span style="color:#000">
							  <p>Dear client:</p>
							  <p>Please check the statement of your account by following the link below. We will assume that you are in agreement with us on the statement unless you inform us to the contrary by latest '.date('M j, Y', strtotime('+4 days')).'</p>
							  <p><a href="http://themedialab.co/externalForms/revenueValidation/'.$model->validation_token.'">http://themedialab.co/externalForms/revenueValidation/'.$model->validation_token.'</a></p>
							  <p>If you weren’t the right contact person to verify the invoice, we ask you to follow the link above and update the information. Do not reply to this email with additional information.</p>
							  <p>This process allows us to audit the invoice together beforehand and expedite any paperwork required and payment.</p>
							  <p>Thanks</p>
							</span>
							<hr style="border: none; border-bottom: 1px solid #999;"/>
							<span style="color:#666">
							  <p>Estimado cliente:</p>
							  <p>Por favor verificar el estado de su cuenta a través del link a continuación. Se considerara de acuerdo con el estado actual a menos que se nos notifique lo contrario a mas tardar el '.date('d-m-Y', strtotime('+4 days')).'</p>
							  <p><a href="http://themedialab.co/externalForms/revenueValidation/'.$model->validation_token.'">http://themedialab.co/externalForms/revenueValidation/'.$model->validation_token.'</a></p>
							  <p>Si usted no fuese la persona indicada para hacer esta verificación, le solicitamos ingrese al link anterior y actualice los datos. No responda a este correo con información adicional.</p>
							  <p>Este proceso nos permite auditar en conjunto la facturación previo a realizar y agilizar en lo posible el intercambio de documentos y el pago.</p>
							  <p>Gracias</p> 
							  <p><img src="http://themedialab.co/logo/logo_themedialab_181x56.png"/></p>
							</span>
		                	';
		            $subject = 'themedialab - Statement of account as per '.date('M j, Y');
		      
					$email_validation=is_null($ios->email_validation) ? $ios->email_adm : $ios->email_validation;
					
		            $mail = new CPhpMailerLogRoute;   
		            $mail->send(array($email_validation), $subject, $body); 
			 
			
			$log->loadLog($model->id,$status);
		}
		else
			print_r($ios->getErrors());

		Yii::app()->end();
	}
}
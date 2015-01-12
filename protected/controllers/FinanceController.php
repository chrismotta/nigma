<?php

class FinanceController extends Controller
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
	// /**
	//  * Specifies the access control rules.
	//  * This method is used by the 'accessControl' filter.
	//  * @return array access control rules
	//  */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('clients','view','excelReport','multiRate','providers','excelReportProviders','sendMail','opportunitieValidation','validateOpportunitie','transaction','addTransaction','invoice','revenueValidation','delete','getCarriers'),
				'roles'=>array('admin', 'finance', 'media','media_manager','businness'),
			),
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('updateValidationStatus'),
				'roles'=>array('admin'),
			),
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('updateValidationStatus'),
				'ips'=>array('54.88.85.63'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	public function actionIndex()
	{
		$this->render('index');
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return DailyReport the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=TransactionCount::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('transaction'));
	}
	
	public function actionClients()
	{
		$year   =isset($_GET['year']) ? $_GET['year'] : date('Y', strtotime('today'));
		$month  =isset($_GET['month']) ? $_GET['month'] : date('m', strtotime('today'));
		$entity =isset($_GET['entity']) ? $_GET['entity'] : null;
		$cat    =isset($_GET['cat']) ? $_GET['cat'] : null;
		$status    =isset($_GET['status']) ? $_GET['status'] : null;
		$model  =new Ios;
		$transactions=new TransactionCount;
		if(FilterManager::model()->isUserTotalAccess('finance.clients'))
			$clients =$model->getClients($month,$year,$entity,null,null,null,$cat,$status,null);
		else
			$clients =$model->getClients($month,$year,$entity,null,Yii::App()->user->getId(),null,$cat,$status,null);
		
		$consolidated=array();
		foreach ($clients['data'] as $client) {
			$client['total_revenue']     =$clients['totals_io'][$client['id']];
			$client['total_transaction'] =$transactions->getTotalTransactions($client['id'],$year.'-'.$month.'-01');
			$client['total']             =$client['total_revenue']+$client['total_transaction'];
			$consolidated[]              =$client;
			isset($totalCount[$client['id']]) ? : $totalCount[$client['id']]=0;
			$totalCount[$client['id']]=$transactions->getTotalTransactions($client['id'],$year.'-'.$month.'-01');
		}
		isset($totalCount) ? : $totalCount=array();;
		foreach ($totalCount as $key => $value) {
			$currency=Ios::model()->findByPk($key)->currency;
			isset($totalCountCurrency[$currency]) ? : $totalCountCurrency[$currency]=0;
			$totalCountCurrency[$currency]+=$value;
		}


		$totalsdata=array();
		$filtersForm =new FiltersForm;
		if (isset($_GET['FiltersForm']))
		    $filtersForm->filters=$_GET['FiltersForm'];

		$filteredData=$filtersForm->filter($consolidated);
		$dataProvider=new CArrayDataProvider($filteredData, array(
		    'id'=>'clients',
		    'sort'=>array(
		        'attributes'=>array(
		             'id', 'name', 'model', 'entity', 'currency', 'rate', 'conv','revenue', 'carrier','opportunitie','total_revenue','status_io'
		        ),
		    ),
		    'pagination'=>array(
		        'pageSize'=>30,
		    ),
		));
		$i=0;

		$totalsTransactions=array();
		$totalsInvoicedTransactions=array();
		$totalsTransactionsInvoicedTemp=TransactionCount::model()->getTotalsInvoicedCurrency($year.'-'.$month.'-01');
		$totalsTransactionsTemp=TransactionCount::model()->getTotalsCurrency($year.'-'.$month.'-01');
		foreach ($totalsTransactionsInvoicedTemp as $value) {
			$totalsInvoicedTransactions[$value['currency']]=$value['total'];
		}
		foreach ($totalsTransactionsTemp as $value) {
			$totalsTransactions[$value['currency']]=$value['total'];
		}
		if(isset($clients['totals']))
		{
			foreach ($clients['totals'] as $key => $value) {
				$i++;
				$totalsdata[$i]['id']          =$i;
				$totalsdata[$i]['currency']    =$key;
				$totalsdata[$i]['sub_total']   =$value['revenue'];
				isset($totalsdata[$i]['total_count']) ? : $totalsdata[$i]['total_count']=0;
				$totalsdata[$i]['total_count'] +=isset($totalCountCurrency[$key]) ? $totalCountCurrency[$key] : 0;
				$totalsdata[$i]['total']       =$totalsdata[$i]['total_count']+$totalsdata[$i]['sub_total'];
				$totalsdata[$i]['total_invoiced']=isset($clients['totals_invoiced'][$key]) ? $clients['totals_invoiced'][$key] : 0;
				$totalsdata[$i]['total_invoiced']+=isset($totalsInvoicedTransactions[$key]) ? $totalsInvoicedTransactions[$key] : 0;
			}
		}
		
		$totalsDataProvider=new CArrayDataProvider($totalsdata, array(
		    'id'=>'totals',
		    'sort'=>array(
		        'attributes'=>array(
		             'id','currency','total','sub_total','total_count','total_invoiced'
		        ),
		    ),
		    'pagination'=>array(
		        'pageSize'=>30,
		    ),
		));


		$this->render('clients',array(
			'model'        =>$model,
			'filtersForm'  =>$filtersForm,
			'dataProvider' =>$dataProvider,
			'clients'      =>$consolidated,
			'clients2'     =>$clients,
			'totals'       =>$totalsDataProvider,
			'month'        =>$month,
			'year'         =>$year,
			'stat'         =>$status,
			'entity'       =>$entity,
			'cat'          =>$cat,
		));
	}

	public function actionProviders()
	{
		$year        =isset($_GET['year']) ? $_GET['year'] : date('Y', strtotime('today'));
		$month       =isset($_GET['month']) ? $_GET['month'] : date('m', strtotime('today'));
		$entity      =isset($_GET['entity']) ? $_GET['entity'] : null;
		$model       =new Networks;
		$data  =$model->getProviders($month,$year);
		$this->render('providers',array(			
			'model'         =>$model,
			'year'          =>$year,
			'month'         =>$month,
			'arrayProvider' =>$data['arrayProvider'],
			'filtersForm'   =>$data['filtersForm'],
			'totals'        =>$data['totalsDataProvider']

		));
	}

	public function actionMultiRate()
	{
		$month =$_GET['month'];
		$year  =$_GET['year'];
		$id    =$_GET['id'];
		$op    =Opportunities::model()->findByPk($id);
		$data  =Ios::model()->getClientsMulti($month,$year,null,null,null,$id,null,null,false);
		$dataProvider=new CArrayDataProvider($data, array(
		    'id'=>'clients',
		    'sort'=>array(
		        'attributes'=>array(
		             'id', 'rate', 'conv','revenue','mobileBrand','country','product'
		        ),
		    ),
		    'pagination'=>array(
		        'pageSize'=>30,
		    ),
		));
		$this->renderPartial('_multiRate', array(
			'opportunitie'       => $op,
			'dataProvider' => $dataProvider,
		), false, false);
	}

	public function actionView($id)
	{
		$model = Ios::model()->findByPk($id);

		$this->renderPartial('_view',array(
			'model'=>$model,
		), false, true);
	}

	public function actionExcelReport()
	{
		if( isset($_POST['excel-clients-form']) ) {
			$this->renderPartial('excelReport', array(
				'model' => new DailyReport,
			));
		}

		$this->renderPartial('_excelReport', array(), false, true);
	}

	public function actionExcelReportProviders()
	{
		if( isset($_POST['excel-providers-form']) ) {
			$this->renderPartial('excelReportProviders', array(
				'model' => new Networks,
			));
		}

		$this->renderPartial('_excelReportProviders', array(), false, true);
	}	

	public function actionRevenueValidation()
	{
		$model             =new Ios;
		$transactionCount  =new TransactionCount;
		$year              =isset($_GET['year']) ? $_GET['year'] : date('Y', strtotime('today'));
		$month             =isset($_GET['month']) ? $_GET['month'] : date('m', strtotime('today'));
		$io                =isset($_GET['io']) ? $model->findByPk($_GET['io']) : null;
		$clients           =$model->getClients($month,$year,null,$io->id,null,null,null,null,'profile');
		$consolidated=array();
		$i=0;
		$aux=array();
		if($count=$transactionCount->getTotalsCarrier($io->id,$year.'-'.$month.'-01'))
		{
			foreach ($count as $value) {
			$sum=false;
				foreach ($clients['data'] as $key => $data) {
					if($sum)continue;
					if($data['country']==$value->getCountry() && $data['product']==$value->product && $data['carrier']==$value->carriers_id_carrier)
					{
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
						$sum=true;
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
		    'id'=>'clients',
		    'sort'=>array(
		        'attributes'=>array(
		             'id', 'name', 'model', 'entity', 'currency', 'rate', 'conv','revenue', 'carrier','country','product','mobileBrand'
		        ),
		    ),
		    'pagination'   =>array(
		        'pageSize' =>30,
		    ),
		));

		            
		if( isset($_POST['revenue-validation-form']) ) {
			$this->renderPartial('sendMail', array(
				'io_id'  => $_POST['ios_id'],
				'period' => $_POST['period'],
			));
		}

		$this->renderPartial('_revenueValidation',
		 array(
				'month'        =>$month,
				'year'         =>$year,
				'io'           =>$io,
				'dataProvider' =>$dataProvider,
				'clients' 	   =>$clients['data'],
				'totals'       =>$totals,
				'count'=>$count
		 	),
		  false, true);

	}

	public function actionOpportunitieValidation()
	{
		$year    =isset($_GET['year']) ? $_GET['year'] : date('Y', strtotime('today'));
		$month   =isset($_GET['month']) ? $_GET['month'] : date('m', strtotime('today'));
		$op      =isset($_GET['op']) ? $_GET['op'] : null;
		$model   =new Ios;
		$modelOp=new Opportunities;
		$opportunitie=$modelOp->findByPk($op);
		if(is_null($opportunitie->rate))
			$clients =$model->getClientsMulti($month,$year,null,null,null,$opportunitie->id,null,null,false);
		else
			$clients =$model->getClients($month,$year,null,null,null,$opportunitie->id,null,null,'otro')['data'];		
		$dataProvider=new CArrayDataProvider($clients, array(
		    'id'=>'clients',
		    'sort'=>array(
		        'attributes'=>array(
		             'id', 'name', 'model', 'entity', 'currency', 'rate', 'conv','revenue', 'carrier'
		        ),
		    ),
		    'pagination'=>array(
		        'pageSize'=>30,
		    ),
		));

		$this->renderPartial('_opportunitieValidation',
		 array(
				'month'        =>$month,
				'year'         =>$year,
				'op'           =>$op,
				'dataProvider' =>$dataProvider,
				'opportunitie' =>$opportunitie
		 	),
		  false, true);

	}

	public function actionValidateOpportunitie()
	{		
		$modelOp      =new Opportunities;
		$opportunitie =$modelOp->findByPk($_POST['opportunities_id']);
		$this->renderPartial('validateOpportunitie', array(
				'opportunities_id' => $_POST['opportunities_id'],
				'period'           => $_POST['period'],
				'opportunitie'     => $opportunitie
			));
	}
	
	public function actionInvoice()
	{
		$this->renderPartial('invoice',
		 array(
				'io_id'  => $_POST['io_id'],
				'period' => $_POST['period']
		 	)
		);
	}
	
	public function actionSendMail()
	{
		$this->renderPartial('sendMail',
		 array(
				'io_id'  => $_POST['io_id'],
				'period' => $_POST['period']
		 	)
		);
	}

	public function actionTransaction()
	{
		$period = isset($_GET['period']) ? $_GET['period'] : date('Y-m-d', strtotime('today'));
		$id     = isset($_GET['id']) ? $_GET['id'] : null;
		$model  = new TransactionCount;
		$carriers=array();
		$clients =Ios::model()->getClients(date('m', strtotime($period)),date('Y', strtotime($period)),null,$id,null,null,null,null,'profile');
		foreach ($clients['data'] as $value) {
			if($value['carrier'])
			{
				$carrier=Carriers::model()->findByPk($value['carrier']);
				$country=GeoLocation::model()->findByPk($carrier->id_country)->name;
				$carriers[$value['carrier']]=$carrier->mobile_brand.' - '.$country;
			}
			else
			{
				$carriers['multi']='Multi';
			}
		}
		$this->renderPartial('_form',array(
			'id'     => $id,
			'period' => $period,
			'model'  => $model,
			'carriers'=>$carriers
		), false, true);
	}

	public function actionAddTransaction()
	{
		if($_POST['TransactionCount']['carrier']!=='' && $_POST['country']!=='')
		{
			$transaction                      = new TransactionCount;
			$transaction->carriers_id_carrier = $_POST['TransactionCount']['carrier']=='multi' ? null : $_POST['TransactionCount']['carrier'];
			$transaction->product             = $_POST['product'];
			$transaction->country             = $_POST['country'];
			$transaction->period              = $_POST['TransactionCount']['period'];
			$transaction->volume              = $_POST['TransactionCount']['volume'];
			$transaction->rate                = $_POST['TransactionCount']['rate'];
			$transaction->users_id            = $_POST['TransactionCount']['users_id'];
			$transaction->ios_id              = $_POST['TransactionCount']['ios_id'];
			$transaction->date                = $_POST['TransactionCount']['date'];

			if(!$transaction->save())echo'<script>alert('.json_encode($transaction->getErrors()).')</script>';
		}
	}

	public function actionUpdateValidationStatus(){
		$list = IosValidation::model()->findAllByAttributes(array('status'=>array('Sent','Viewed')));
		foreach ($list as $key => $value) {
			//echo date('Y-m-d') . ' ';
			echo '#' . $value['id'] . ' - ';
			echo "Sent ".date('Y-m-d', strtotime($value['date'])) . ' - ';
			echo "Expiration ".Utilities::weekDaysSum(date('Y-m-d', strtotime($value['date'])),4);
			
			$expDay = strtotime( Utilities::weekDaysSum(date('Y-m-d', strtotime($value['date'])),4) );
			$today  = strtotime("today");
			if($today > $expDay){
				echo " (expired)";
				$model = IosValidation::model()->findByPk($value['id']);
				$model->status = "Expired";
				$model->save();
			}

			echo '<br/>';
		};


	}
	public function actionGetCarriers()
	{
		// comentado provisoriamente, generar permiso de admin
		//$ios = Ios::model()->findAll( "advertisers_id=:advertiser AND commercial_id=:c_id", array(':advertiser'=>$id, ':c_id'=>Yii::app()->user->id) );
		$criteria=new CDbCriteria;
		$id_country = isset($_GET['country']) ? $_GET['country'] : null;
			if($id_country)
				$criteria->compare('id_country', $id_country);
		$carriers =Carriers::model()->findAll($criteria);
		$response='';
		$response='<option value="">All Carriers</option>';
		foreach ($carriers as $carrier) {
			$response .= '<option value="' . $carrier->id_carrier . '">' . $carrier->mobile_brand . '</option>';
		}
		echo $response;
		Yii::app()->end();
	}
}
<?php

class FinanceController extends Controller
{
	public function actionIndex()
	{
		$this->render('index');
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
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('clients','view','excelReport','multiRate','providers','excelReportProviders','sendMail','opportunitieValidation','validateOpportunitie'),
				'roles'=>array('admin', 'finance', 'media'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	
	public function actionClients()
	{
			$year   =isset($_GET['year']) ? $_GET['year'] : date('Y', strtotime('today'));
		$month  =isset($_GET['month']) ? $_GET['month'] : date('m', strtotime('today'));
		$entity =isset($_GET['entity']) ? $_GET['entity'] : null;
		$cat    =isset($_GET['cat']) ? $_GET['cat'] : null;
		$status    =isset($_GET['status']) ? $_GET['status'] : null;
		$model  =new Ios;
		//$clients     =$model->getClients($month,$year,$entity);
		if(FilterManager::model()->isUserTotalAccess('finance.clients'))
			$clients =$model->getClients($month,$year,$entity,null,null,null,$cat,$status);
		else
			$clients =$model->getClients($month,$year,$entity,null,Yii::App()->user->getId(),null,$cat,$status);
		$consolidated=array();
		foreach ($clients['data'] as $client) {
			$client['total_revenue']=$clients['totals_io'][$client['id']];
			$consolidated[]=$client;
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
		if(isset($clients['totals']))
		{
			foreach ($clients['totals'] as $key => $value) {
				$i++;
				$totalsdata[$i]['id']       =$i;
				$totalsdata[$i]['currency'] =$key;
				$totalsdata[$i]['total']    =$value['revenue'];
			}
		}

		$totalsDataProvider=new CArrayDataProvider($totalsdata, array(
		    'id'=>'totals',
		    'sort'=>array(
		        'attributes'=>array(
		             'id','currency','total',
		        ),
		    ),
		    'pagination'=>array(
		        'pageSize'=>30,
		    ),
		));





		// foreach ($clients as $opportunities) {			
		// 	foreach ($opportunities as $data) {
		// 		isset($sum[$data['id']]) ?  : $sum[$data['id']]=0;
		// 		$sum[$data['id']]+=$data['revenue'];
		// 	}
		// }
		// $consolidated=array();
		// foreach ($clients as $opportunities) {			
		// 	foreach ($opportunities as $data) {
		// 		$data['total_revenue']=$sum[$data['id']];
		// 		$consolidated[]=$data;
		// 	}
		// }
		// $filtersForm =new FiltersForm;
		// if (isset($_GET['FiltersForm']))
		//     $filtersForm->filters=$_GET['FiltersForm'];
		//  foreach ($consolidated as $client) {
		// 	isset($totals[$client['currency']]['conv']) ? : $totals[$client['currency']]['conv']       =0;
		// 	isset($totals[$client['currency']]['rate']) ? : $totals[$client['currency']]['rate']       =0;
		// 	isset($totals[$client['currency']]['revenue']) ? : $totals[$client['currency']]['revenue'] =0;
		// 	$totals[$client['currency']]['rate']    +=$client['rate'];
		// 	$totals[$client['currency']]['conv']    +=$client['conv'];
		// 	$totals[$client['currency']]['revenue'] +=$client['revenue'];
		// }
		// $i=0;
			
		
		// if(isset($totals))
		// {
		// 	foreach ($totals as $key => $value) {
		// 		$totalsdata[$i]['id']       =$i;
		// 		$totalsdata[$i]['currency'] =$key;
		// 		$totalsdata[$i]['total']    =$value['revenue'];
		// 		$i++;
		// 	}
		// }
		// else
		// {
		// 	$totalsdata[0]['id']       =null;
		// 	$totalsdata[0]['currency'] =null;
		// 	$totalsdata[0]['total']    =null;
		// }
		// $totalsDataProvider=new CArrayDataProvider($totalsdata, array(
		//     'id'=>'totals',
		//     'sort'=>array(
		//         'attributes'=>array(
		//              'id','currency','total',
		//         ),
		//     ),
		//     'pagination'=>array(
		//         'pageSize'=>30,
		//     ),
		// ));
		// Get rawData and create dataProvider
		// $rawData=User::model()->findAll();
		// 
		// $filteredData=$filtersForm->filter($consolidated);
		// $dataProvider=new CArrayDataProvider($filteredData, array(
		//     'id'=>'clients',
		//     'sort'=>array(
		//         'attributes'=>array(
		//              'id', 'name', 'model', 'entity', 'currency', 'rate', 'conv','revenue', 'carrier','opportunitie','total_revenue','status_io'
		//         ),
		//     ),
		//     'pagination'=>array(
		//         'pageSize'=>30,
		//     ),
		// ));
		
		$this->render('clients',array(
			'model'        =>$model,
			'filtersForm'  =>$filtersForm,
			'dataProvider' =>$dataProvider,
			'clients'      =>$consolidated,
			'totals'       =>$totalsDataProvider,
		));
	}

	public function actionProviders()
	{
		$year        =isset($_GET['year']) ? $_GET['year'] : date('Y', strtotime('today'));
		$month       =isset($_GET['month']) ? $_GET['month'] : date('m', strtotime('today'));
		$entity      =isset($_GET['entity']) ? $_GET['entity'] : null;
		$model       =new Networks;
		$this->render('providers',array(			
			// 'dataProvider' =>$model->getProviders($month, $year),
			'model'			=>$model,
		));
	}

	public function actionMultiRate()
	{
		$month=$_GET['month'];
		$year=$_GET['year'];
		$id=$_GET['id'];
		$data=Ios::model()->getClientsByIo($month,$year,$id);
		$dataProvider=new CArrayDataProvider($data, array(
		    'id'=>'clients',
		    'sort'=>array(
		        'attributes'=>array(
		             'id', 'rate', 'conv','revenue','mobileBrand','country'
		        ),
		    ),
		    'pagination'=>array(
		        'pageSize'=>30,
		    ),
		));
		$this->renderPartial('_multiRate', array(
			'id'       => $id,
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
		$year              =isset($_GET['year']) ? $_GET['year'] : date('Y', strtotime('today'));
		$month             =isset($_GET['month']) ? $_GET['month'] : date('m', strtotime('today'));
		$io                =isset($_GET['io']) ? $model->findByPk($_GET['io']) : null;
		$clients           =$model->getClientsNew2($month,$year,null,$io->id);
		// echo json_encode($clients['data']);
		// return;
		// $totals['revenue'] =0;
		// $totals['conv']    =0;
		$consolidated=array();
		// foreach ($clients as $ios) {
		// 	foreach ($ios as $carriers) {
		// 		foreach ($carriers as $data) {
		// 			$consolidated[]=$data;
		// 			$totals['revenue']+=$data['revenue'];
		// 			$totals['conv']+=$data['conv'];
		// 		}
		// 	}
		// }
		$dataProvider=new CArrayDataProvider($clients['data'], array(
		    'id'=>'clients',
		    'sort'=>array(
		        'attributes'=>array(
		             'id', 'name', 'model', 'entity', 'currency', 'rate', 'conv','revenue', 'carrier','country','product','mobileBrand'
		        ),
		    ),
		    'pagination'=>array(
		        'pageSize'=>30,
		    ),
		));

		            
		if( isset($_POST['revenue-validation-form']) ) {
			$this->renderPartial('sendMail', array(
				'io_id' => $_POST['ios_id'],
				'period' => $_POST['period'],
			));
		}

		$this->renderPartial('_revenueValidation',
		 array(
				'month'        =>$month,
				'year'         =>$year,
				'io'           =>$io,
				'dataProvider' =>$dataProvider,
				'totals'       =>$clients['totals']
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
		$clients =$model->getClients($month,$year,null,null,null,$op);
		// foreach ($clients as $opportunities) {			
		// 	foreach ($opportunities as $data) {
		// 		$consolidated[]=$data;
		// 	}
		// }
		$dataProvider=new CArrayDataProvider($clients['data'], array(
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
				'opportunitie'=>$opportunitie
		 	),
		  false, true);

	}

	public function actionValidateOpportunitie()
	{		
		$modelOp=new Opportunities;
		$opportunitie=$modelOp->findByPk($_POST['opportunities_id']);
		$this->renderPartial('validateOpportunitie', array(
				'opportunities_id' => $_POST['opportunities_id'],
				'period' => $_POST['period'],
				'opportunitie'=>$opportunitie
			));
	}
	
	public function actionSendMail()
	{
		$this->renderPartial('sendMail',
		 array(
				'io_id'=> $_REQUEST['io_id'],
				'period'=> $_REQUEST['period']
		 	)
			);
	}
}
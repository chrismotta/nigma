<?php 
/* @var $this FinanceController */
/* @var $model Finance */

$path         = 'uploads/';
$name         = 'KickAds-Clients.xls';
// $year         =isset($_POST['year']) ? $_POST['year'] : date('Y', strtotime('today'));
// $month        =isset($_POST['month']) ? $_POST['month'] : date('m', strtotime('today'));
// $entity       =isset($_POST['entity']) ? $_POST['entity'] : null;
// $cat          =isset($_POST['cat']) ? $_POST['cat'] : null;
// $status       =isset($_POST['status']) ? $_POST['status'] : null;
// $clients      =Ios::getClients($month,$year,$entity,null,null,null,$cat,$status,null);

// $dataProvider =new CArrayDataProvider($clients['data'], array(
//     'id'=>'clients',
//     'pagination'=>array(
//         'pageSize'=>30,
//     ),
// ));
$this->widget('EExcelWriter', array(
    'dataProvider' => $dataProvider,
    'title'        => 'EExcelWriter',
    'stream'       => TRUE,
    'fileName'     => $name,
    'filePath'     => $path,
    'columns'      => array(
        array(
            'name'  =>  'id',
            'value' =>'$data["id"]',    
            ),  
        array(
            'name'   =>  'commercial_name',
            'value'  =>'$data["name"]',      
            'footer' =>'Totals:',
            ),
        array(
            'name'              => 'opportunitie',
            'value'             => '$data["opportunitie_id"]." - ".$data["opportunitie"]',  
            ),  
        array(
            'name'  =>  'model',
            'value' =>'$data["model"]',     
            ),
        array(
            'name'  =>  'entity',
            'value' =>'$data["entity"]',        
            ),  
        array(
            'name'  =>  'currency',
            'value' =>'$data["currency"]',      
            ),
        array(
            'name'  =>  'rate',
            'value' =>'$data["rate"]',      
        ),  
        array(
            'name'   =>  'conv',
            'header' =>'Clics/Imp/Conv',
            'value'  =>'$data["conv"]',      
        ),
        array(
            'name'  =>  'revenue',
            'header'            =>'Revenue',
            'value' =>'number_format($data["revenue"],2)',       
        ),
        array(
            'name'              =>'totalRevenue',
            'header'            =>'Total Revenue',
            'value'             =>'number_format($data["total_revenue"],2)',
        ),
        array(
            'name'              =>'totalTransaction',
            'header'            =>'Total Transaction',
            'value'             =>'number_format($data["total_transaction"],2)',
        ),
        array(
            'name'              =>'total',
            'header'            =>'Total',
            'value'             =>'number_format($data["total"],2)', 
        ),
        array(
            'name'  =>  'status',
            'value' =>'$data["status_io"]',       
        ),
        array(
            'name'  =>  'sentDate',
            'header'            =>'Send Date',
            'value' =>'$data["date"]',       
        ),
        array(
            'name'  =>  'expiredDate',
            'header'            =>'Expired Date',
            'value' =>'$data["date"]!="" ? Utilities::weekDaysSum(date("Y-m-d", strtotime($data["date"])),4) : ""',       
        ),
    ),
));

unlink($path . $name);

?>
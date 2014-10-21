<?php 
/* @var $this FinanceController */
/* @var $model Finance */

$path    = 'uploads/';
$name    = 'KickAds-Clients.xls';
$year    =isset($_POST['year']) ? $_POST['year'] : date('Y', strtotime('today'));
$month   =isset($_POST['month']) ? $_POST['month'] : date('m', strtotime('today'));
$entity   =isset($_POST['entity']) ? $_POST['entity'] : null;
//echo json_encode($model->getClients($month,$year));
$clients =Ios::getClients($month,$year,$entity);

$dataProvider=new CArrayDataProvider($clients, array(
    'id'=>'clients',
    // 'sort'=>array(
    //     'attributes'=>array(
    //          'id', 'username', 'email',
    //     ),
    // ),
    'pagination'=>array(
        'pageSize'=>30,
    ),
));
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
            'value' =>'$data["revenue"]',       
        ),
    ),
));

unlink($path . $name);

?>
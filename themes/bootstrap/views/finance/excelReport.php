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
if($closed_deal=='false')
{
    $columns=   array(
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
    );
}
else
{
    $columns=   array(
        array(
            'name'              => 'name',
            'value'             => '$data["id"] . " - " . $data["name"]',
            'htmlOptions'       => array('id'=>'alignLeft'),
            'headerHtmlOptions' => array('width' => '150'),
            'header'            => 'IO - Commercial Name',
            ),  
        array(
            'name'   =>  'commercial_name',
            'value'  =>'$data["name"]',      
            'footer' =>'Totals:',
            ),
        array(
            'name'              => 'opportunitie',
            'value'             => '$data["opportunitie_id"]." - ".$data["opportunitie"]',  
            'htmlOptions'       => array('id'=>'alignLeft'),
            'header'            => 'Opportunitie',                           
            ),  
        array(
                'name'  =>  'model',
                'value' =>'$data["model"]',     
            ),
        array(
            'name'              =>'entity',
            'value'             =>'$data["entity"]',
            'headerHtmlOptions' => array('width' => '80'),  
            'header'            =>'Entity',    
            ),  
        array(
            'name'              =>'currency',
            'value'             =>'$data["currency"]',
            'headerHtmlOptions' => array('width' => '80'),      
            'header'            =>'Currency',   
            ),
        array(
            'name'              =>'conv',
            'header'            =>'Imp/Clics/Conv',
            'value'             =>'number_format($data["conv"])',   
        ),
        array(
            'name'              =>'opportunitie',
            'header'            =>'Percent Agency Commission',
            'filter'            =>false,
            'value'             =>'number_format(Opportunities::model()->findByPk($data["opportunitie_id"])->agency_commission)."%"',
            'headerHtmlOptions' => array('width' => '80','style'=>'text-align:right;'),
            'htmlOptions'       => array('style'=>'text-align:right !important;'),  
        ),
        array(
            'name'              =>'agency_commission',
            'header'            =>'Agency Commission',
            'value'             =>'number_format(Opportunities::model()->findByPk($data["opportunitie_id"])->getTotalAgencyCommission(),2)',
        ),
        array(
            'name'              =>'sub_total',
            'header'            =>'Sub Total',
            'value'             =>'number_format(Opportunities::model()->findByPk($data["opportunitie_id"])->close_amount,2)',
        ),
        array(
            'name'              =>'total',
            'header'            =>'Total',
            'value'             =>'number_format(Opportunities::model()->findByPk($data["opportunitie_id"])->getTotalCloseDeal(),2)',  
        ),
        array(
            'name'              =>'status_opp',
            'header'            =>'Opportunitie Status',
            'value'             =>'$data["status_opp"] ? "Invoiced" : "To Invoiced"',  
        ),
        array(
            'name'              =>'end_date',
            'header'            =>'End Date',
            'value'             =>'date("Y-m-d",strtotime(Opportunities::model()->findByPk($data["opportunitie_id"])->endDate))',   
        ),
    );
}
$this->widget('EExcelWriter', array(
    'dataProvider' => $dataProvider,
    'title'        => 'EExcelWriter',
    'stream'       => TRUE,
    'fileName'     => $name,
    'filePath'     => $path,
    'columns'      => $columns,
));

unlink($path . $name);

?>
<?php 
/* @var $this FinanceController */
/* @var $model Finance */

$path         = 'uploads/';
$name         = 'TheMediaLab-Advertiser.xls';
$sum            = isset($_POST['sum']) ? $_POST['sum'] : 0;

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
            'name'              => 'date',
            'value'             => 'date("d-m-Y", strtotime($data->date))',
            'visible'     => !$sum,
        ),
        array(
            'name'  => 'product',
            'header'=> 'Name',
            'value' => '$data->campaigns->opportunities->product',
        ),
        array(
            'name'  => 'country',
            'header'=> 'Country',
            'value' => '$data->campaigns->opportunities->regions->country->name',
        ),
        array(
            'name'  => 'carrier',
            'header'=> 'Carrier',
            'value' => '$data->campaigns->opportunities->carriers_id ?
                        $data->campaigns->opportunities->carriers->mobile_brand:
                        "All Carriers"',
        ),
        array(
            'name'   => 'rate',
            'header' => 'Rate',
            'value'  => '"$ ".number_format($data->campaigns->opportunities->rate,2)',
        ),
        array(  
            'name'              => 'imp',
            'header'            => 'Impressions',
            'value'             => 'number_format($data->imp)',
            'visible'           => true,
        ),
        array(
            'name'              => 'clics',
            'value'             => 'number_format($data->clics)',
            'visible'           => false,
        ),
        array(
            'name'              => 'conv_api',
            'header'            => 'Conversions',
            'value'             => 'number_format($data->conv_api)',
            'visible'           => false,
        ),
        array(
            'name'              => 'revenue',
            'header'            => 'Spend',
            'value'             => '"$ ".number_format($data->revenue, 2)',
            'visible'           => true,
        ),
    ),
));

unlink($path . $name);

?>
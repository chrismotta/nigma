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
            'visible'           => $user_visibility->country,
        ),
        array(
            'name'  => 'carrier',
            'header'=> 'Carrier',
            'value' => '$data->campaigns->opportunities->carriers_id ?
                        $data->campaigns->opportunities->carriers->mobile_brand:
                        "All Carriers"',
            'visible'           => $user_visibility->carrier,
        ),
        array(
            'name'   => 'rate',
            'header' => 'Rate',
            'value'  => '"$ ".number_format($data->campaigns->opportunities->rate,2)',
            'visible'           => $user_visibility->rate,
        ),
        array(  
            'name'              => 'imp',
            'header'            => 'Impressions',
            'value'             => '$data->imp',
            'visible'           => $user_visibility->imp,
        ),
        array(
            'name'              => 'clics',
            'value'             => '$data->clics',
            'visible'           => $user_visibility->clicks,
        ),
        array(
            'name'              => 'conv_api',
            'header'            => 'Conversions',
            'value'             => '$data->conv_api',
            'visible'           => $user_visibility->conv,
        ),
        array(
            'name'              => 'revenue',
            'header'            => 'Spend',
            'value'             => '$data->revenue',
            'visible'           => $user_visibility->spend,
        ),
    ),
));

unlink($path . $name);

?>
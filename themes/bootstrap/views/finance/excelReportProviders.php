<?php 
/* @var $this FinanceController */
/* @var $model Finance */
$path    = 'uploads/';
$name    = 'KickAds-Providers.xls';
$year    =isset($_POST['year']) ? $_POST['year'] : date('Y', strtotime('today'));
$month   =isset($_POST['month']) ? $_POST['month'] : date('m', strtotime('today'));
//echo json_encode($model->getClients($month,$year));

$this->widget('EExcelWriter', array(
    'dataProvider' => $model->getProviders($month,$year)['dataProvider'],
    'title'        => 'EExcelWriter',
    'stream'       => TRUE,
    'fileName'     => $name,
    'filePath'     => $path,
    'columns'      => array(
        array(
            'name'  =>'id',
            'value' =>'$data->id',
        ),
        array(
            'name'  =>'network_name',
            'value' =>'$data->providers->name',
        ),
        array(
            'name'  =>'currency',
            'value' =>'$data->currency',
        ),
        array(
            'name'  =>'clics',
            'value' =>'$data->clics',
        ),
        array(
            'name'  =>'imp',
            'value' =>'$data->imp',
        ),
        array(
            'name'  =>'percent_off',
            'value' =>'is_null($data->percent_off) ? "0%" : number_format($data->percent_off*100,0)."%"',
        ),
        array(
            'name'   =>'spend',
            'header' =>'Subtotal',
            'value'  =>'$data->spend',
        ),
        array(
            'name'  =>'off',
            'value' =>'$data->off',
        ),
        array(
            'name'  =>'total',
            'value' =>'$data->total',
        ),
    ),
));

unlink($path . $name);

?>
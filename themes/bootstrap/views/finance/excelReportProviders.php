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
            'name'=>'id',
            'value'=>'$data->id',
            'htmlOptions'=>array('style' => 'width: 100px'),
        ),
        array(
            'name'=>'network_name',
            'value'=>'$data->network_name',
            'htmlOptions'=>array('style' => 'width: 100px'),
        ),
        array(
            'name'=>'currency',
            'value'=>'$data->currency',
            'htmlOptions'=>array('style' => 'width: 100px'),
        ),
        array(
            'name'=>'clics',
            'value'=>'$data->clics',
            'htmlOptions'=>array('style' => 'width: 100px'),
        ),
        array(
            'name'=>'imp',
            'value'=>'$data->imp',
            'htmlOptions'=>array('style' => 'width: 100px'),
        ),
        array(
            'name'=>'percent_off',
            'value'=>'is_null($data->percent_off) ? "0%" : number_format($data->percent_off*100,0)."%"',
            'htmlOptions'=>array('style' => 'width: 100px'),
        ),
        array(
            'name'=>'spend',
            'header'=>'Subtotal',
            'value'=>'$data->spend',
            'htmlOptions'=>array('style' => 'width: 100px'),
        ),
        array(
            'name'=>'off',
            'value'=>'$data->off',
            'htmlOptions'=>array('style' => 'width: 100px'),
        ),
        array(
            'name'=>'total',
            'value'=>'$data->total',
            'htmlOptions'=>array('style' => 'width: 100px'),
        ),
    ),
));

unlink($path . $name);

?>
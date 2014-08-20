<?php
/* @var $this DailyReportController */
/* @var $model DailyReport */

$path = 'uploads/';
$name = 'KickAds-DailyReport.xls';

$this->widget('EExcelWriter', array(
    'dataProvider' => $model->excel(),
    'title'        => 'EExcelWriter',
    'stream'       => TRUE,
    'fileName'     => $name,
    'filePath'     => $path,
    'columns'      => array(
        array(
        	'name' => 'campaign_name',
        	'value' => '$data->campaigns->name',
        ),
        array(
        	'name' => 'network_name',
        	'value' => '$data->networks->name',
        ),
        'imp',
        'conv_api',
        'conv_adv',
        'spend',
        'model',
        'value',
        array(
            'name' => 'date',
            'value' => 'date("d-m-Y", strtotime($data->date))',
        ),
    ),
));

unlink($path . $name);

?>
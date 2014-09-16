<?php 
/* @var $this DailyReportController */
/* @var $model DailyReport */

$path = 'uploads/';
$name = 'KickAds-TrafficRepor.xls';

$dateStart = isset($_POST['excel-dateStart']) ? $_POST['excel-dateStart'] : 'today' ;
$dateEnd   = isset($_POST['excel-dateEnd']) ? $_POST['excel-dateEnd'] : 'today';

$dateStart = date('Y-m-d', strtotime($dateStart));
$dateEnd = date('Y-m-d', strtotime($dateEnd));

$this->widget('EExcelWriter', array(
    'dataProvider' => $model->excel($dateStart, $dateEnd),
    'title'        => 'EExcelWriter',
    'stream'       => TRUE,
    'fileName'     => $name,
    'filePath'     => $path,
    'columns'      => array(
        array(
            'name'              => 'advertisers_name',
            'value'             => '$data->opportunities->ios->advertisers->name',
        ),
        array(
            'name'              => 'ios_name',
            'value'             => '$data->opportunities->ios->name',
        ),      
        array(
            'name'              => 'name',
            'value'             => '$data->name',
        ),
        array(
            'name'              => 'clicks',
            'value'             => '$data->countClicks()',
        ),
        array(
            'name'              => 'conv',
            'value'             => '$data->countConv()',
        ),
    ),
));

unlink($path . $name);

?>
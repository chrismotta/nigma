<?php 
/* @var $this DailyReportController */
/* @var $model DailyReport */

$path = 'uploads/';
$name = 'KickAds-ConversionsReport.xls';
$dateStart = isset($_POST['excel-dateStart']) ? $_POST['excel-dateStart'] : 'today' ;
$dateEnd   = isset($_POST['excel-dateEnd']) ? $_POST['excel-dateEnd'] : 'today';
$dateStart = date('Y-m-d', strtotime($dateStart));
$dateEnd = date('Y-m-d', strtotime($dateEnd));

$this->widget('EExcelWriter', array(
    'dataProvider' => $model->excel(),
    'title'        => 'EExcelWriter',
    'stream'       => TRUE,
    'fileName'     => $name,
    'filePath'     => $path,
    'columns'      => array(
        array(
            'name'   => 'id',
            'value'  => '$data->id',            
        ),
        array(
            'name'   => 'Campaign',
            'value'  => 'Campaigns::model()->getExternalName($data->campaign_id)',           
        ),
        array(
            'name'   => 'IP',
            'value'  => '$data->clicksLog->server_ip',            
        ),
        array(
            'name'   => 'Country',
            'value'  => '$data->clicksLog->country',            
        ),
        array(
            'name'   => 'City',
            'value'  => '$data->clicksLog->city',            
        ),
        array(
            'name'   => 'Carrier',
            'value'  => '$data->clicksLog->carrier',            
        ),
        array(
            'name'   => 'Browser',
            'value'  => '$data->clicksLog->browser',            
        ),
        array(
            'name'   => 'OS',
            'value'  => '$data->clicksLog->os',            
        ),
        array(
            'name'   => 'Device',
            'value'  => '$data->clicksLog->device',            
        ),
        array(
            'name'   => 'Device Type',
            'value'  => '$data->clicksLog->device_type',            
        ),
        array(
            'name'   => 'Referer URL',
            'value'  => '$data->clicksLog->referer',            
        ),
        array(
            'name'   => 'APP',
            'value'  => '$data->clicksLog->app',            
        ),
        array(
            'name'   => 'Date',
            'value'  => '$data->date',            
        ),
    ),
));

unlink($path . $name);

?>
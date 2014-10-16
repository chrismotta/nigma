<?php 
/* @var $this DailyReportController */
/* @var $model DailyReport */

$path = 'uploads/';
$name = 'KickAds-ConversionsReport.xls';
$dateStart = isset($_POST['excel-dateStart']) ? $_POST['excel-dateStart'] : 'today' ;
$dateEnd   = isset($_POST['excel-dateEnd']) ? $_POST['excel-dateEnd'] : 'today';
$dateStart = date('Y-m-d', strtotime($dateStart));
$dateEnd = date('Y-m-d', strtotime($dateEnd));
$id=$_POST['id'];
$this->widget('EExcelWriter', array(
    'dataProvider' => $model->excel($dateStart,$dateEnd,$id),
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
            'value'  => '!isset($data->clicksLog->server_ip) ? "-" : $data->clicksLog->server_ip',            
        ),
        array(
            'name'   => 'Country',
            'value'  => '!isset($data->clicksLog->country) ? "-" : $data->clicksLog->country',            
        ),
        array(
            'name'   => 'City',
            'value'  => '!isset($data->clicksLog->city) ? "-" : $data->clicksLog->city',            
        ),
        array(
            'name'   => 'Carrier',
            'value'  => '!isset($data->clicksLog->carrier) ? "-" : $data->clicksLog->carrier',            
        ),
        array(
            'name'   => 'Browser',
            'value'  => '!isset($data->clicksLog->browser) ? "-" $data->clicksLog->browser: ',            
        ),
        array(
            'name'   => 'OS',
            'value'  => '!isset($data->clicksLog->os) ? "-" : $data->clicksLog->os',            
        ),
        array(
            'name'   => 'Device',
            'value'  => '!isset($data->clicksLog->device) ? "-" : $data->clicksLog->device',            
        ),
        array(
            'name'   => 'Device Type',
            'value'  => '!isset($data->clicksLog->device_type) ? "-" : $data->clicksLog->device_type',   
        ),
        array(
            'name'   => 'Referer URL',
            'type'   => 'html',
            'value'  => '!isset($data->clicksLog->referer) ? "-" : " ".htmlspecialchars($data->clicksLog->referer)',  
        ),
        array(
            'name'   => 'APP',
            'value'  => '!isset($data->clicksLog->app) ? "-" : $data->clicksLog->app',            
        ),
        array(
            'name'   => 'Date',
            'value'  => '$data->date',            
        ),
    ),
));

unlink($path . $name);

?>
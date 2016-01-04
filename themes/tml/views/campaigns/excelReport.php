<?php 
/* @var $this DailyReportController */
/* @var $model DailyReport */

$path = 'uploads/';
$name = 'TheMediaLab-ConversionsReport.xls';
$dateStart = isset($_POST['excel-dateStart']) ? $_POST['excel-dateStart'] : 'today' ;
$dateEnd   = isset($_POST['excel-dateEnd']) ? $_POST['excel-dateEnd'] : 'today';
$dateStart = date('Y-m-d', strtotime($dateStart));
$dateEnd = date('Y-m-d', strtotime($dateEnd));
$id= isset($_POST['id']) ? $_POST['id'] : null;
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
            'name'   => 'Transaction ID',
            'value'  => '$data->tid',
        ),
        array(
            'name'   => 'Campaign ID',
            'value'  => '$data->campaigns_id',            
        ),
        array(
            'name'   => 'Advertiser',
            'value'  => '$data->clicksLog->campaigns->opportunities->regions->financeEntities->advertisers->name',           
        ),
        array(
            'name'   => 'Campaign Name',
            'value'  => 'Campaigns::model()->getExternalName($data->campaigns_id)',           
        ),
        array(
            'name'   => 'Traffic Source',
            'value'  => '$data->clicksLog->campaigns->providers->name',           
        ),
        array(
            'name'   => 'Status',
            'value'  => '$data->clicksLog->providers->id == 29 ? "Test" : "Valid"',           
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
            'name'   => 'Browser Type',
            'value'  => '!isset($data->clicksLog->browser) ? "-" : $data->clicksLog->browser ',
        ),
        array(
            'name'   => 'Browser Version',
            'value'  => '!isset($data->clicksLog->browser_version) ? "-" : $data->clicksLog->browser_version',            
        ),
        array(
            'name'   => 'OS Type',
            'value'  => '!isset($data->clicksLog->os) ? "-" : $data->clicksLog->os',            
        ),
        array(
            'name'   => 'OS Version',
            'value'  => '!isset($data->clicksLog->os_version) ? "-" : $data->clicksLog->os_version',            
        ),
        // array(
        //     'name'   => 'Device Type',
        //     'value'  => '!isset($data->clicksLog->device_type) ? "-" : $data->clicksLog->device_type',   
        // ),
        array(
            'name'   => 'Device Brand',
            'value'  => '!isset($data->clicksLog->device) ? "-" : $data->clicksLog->device',            
        ),
        array(
            'name'   => 'Device Model',
            'value'  => '!isset($data->clicksLog->device_model) ? "-" : $data->clicksLog->device_model',            
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
<?php 
/* @var $this SemController */
/* @var $model ClicksLog */

set_time_limit(1000);

$path = 'uploads/';
$name = 'KickAds-SEM-' . $report_type . '.xls';

$report_type  = $_POST['excel-report'];
$campaignName = $_POST['excel-campaign'] != '' ? $_POST['excel-campaign'] : NULL;
    
if ($campaignName != NULL) { // get campaigns ID from campaign external name
    $end        = strpos($campaignName, "-");
    $campaignID = substr($campaignName, 0,  $end);
} else {
    $campaignID = NULL;
}

$tmp       = new DateTime('today');
$tmp       = $tmp->sub(new DateInterval('P1W'));
$dateStart = isset($_POST['excel-dateStart']) ? $_POST['excel-dateStart'] : $tmp->format('Y-m-d') ;
$dateEnd   = isset($_POST['excel-dateEnd']) ? $_POST['excel-dateEnd'] : 'today' ;

$dateStart = date('Y-m-d', strtotime($dateStart));
$dateEnd   = date('Y-m-d', strtotime($dateEnd));

$this->widget('EExcelWriter', array(
    'dataProvider' => $model->searchSem($report_type, $dateStart, $dateEnd, $campaignID),
    'title'        => 'EExcelWriter',
    'stream'       => TRUE,
    'fileName'     => $name,
    'filePath'     => $path,
    'columns'      => array(
        array(
            'name'        => 'campaigns_id',
            'value'       => 'Campaigns::model()->getExternalName($data->campaigns_id)',
            'htmlOptions' => array('style'=>'width: 500px;'),
        ),
        array(
            'name'        => $report_type,
            'htmlOptions' => array('style'=>'width: 400px;'),
        ),
        array(
            'name'        => 'match_type',
            'value'       => '$data->getMatchType()',
            'htmlOptions' => array('style'=>'width: 100px;'),
            'visible'     => $report_type == 'keyword' ? true : false,
        ),
        array(
            'name'        => 'totalClicks',
            'htmlOptions' => array('style'=>'text-align:right; width: 100px;'),
        ),
        array(
            'name'        => 'totalConv',
            'htmlOptions' => array('style'=>'text-align:right; width: 100px;'),
        ),
        array(
            'name'        => 'CTR',
            'value'       => '$data->CTR',
            'htmlOptions' => array('style'=>'text-align:right; width: 100px;'),
        ),
    ),
));

unlink($path . $name);

?>
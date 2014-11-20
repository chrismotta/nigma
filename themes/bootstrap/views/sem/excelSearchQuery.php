<?php 
/* @var $this SemController */
/* @var $model ClicksLog */

set_time_limit(1000);

$path = 'uploads/';
$name = 'KickAds-SEM-query.xls';

$onlyConversions = isset($_GET['only-conv']) ? $_GET['only-conv'] : false ;
$searchCriteria  = isset($_GET['criteria']) ? $_GET['criteria'] : NULL ;
$dateStart       = isset($_GET['dateStart']) ? $_GET['dateStart'] : 'yesterday' ;
$dateEnd         = isset($_GET['dateEnd']) ? $_GET['dateEnd'] : 'yesterday' ;

$dateStart = date('Y-m-d', strtotime($dateStart));
$dateEnd   = date('Y-m-d', strtotime($dateEnd));

$campaignName = isset($_GET['campaign']) ? $_GET['campaign'] : NULL;

if ($campaignName != NULL) { // get campaigns ID from campaign external name
	$end        = strpos($campaignName, "-");
	$campaignID = substr($campaignName, 0,  $end);
} else {
	$campaignID = NULL;
}

$this->widget('EExcelWriter', array(
	'dataProvider' => $model->searchQuery($dateStart, $dateEnd, $campaignID, $searchCriteria, $onlyConversions),
	'title'        => 'EExcelWriter',
	'stream'       => TRUE,
	'fileName'     => $name,
	'filePath'     => $path,
	'columns'      => array(
		array(
			'name'        => 'query',
			'value'       => 'str_replace("http://", "", $data->query)',
			'htmlOptions' => array('style'=>'width: 800px;'),
		),
		array(
			'name'        => 'totalClicks',
			'htmlOptions' => array('style'=>'width: 100px;'),
		),
		array(
			'name'        => 'totalConv',
			'htmlOptions' => array('style'=>'width: 100px;'),
		),
		array(
			'name'        => 'CTR',
			'value'       => '$data->CTR',
			'htmlOptions' => array('style'=>'width: 100px;'),
		),
	),
));

unlink($path . $name);
// $this->redirect('searchCriteria', false);
// $this->refresh(false);

?>
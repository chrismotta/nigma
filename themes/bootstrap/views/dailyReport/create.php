<?php
/* @var $this DailyReportController */
/* @var $model DailyReport */

$this->breadcrumbs=array(
	'Daily Reports'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List DailyReport', 'url'=>array('index')),
	array('label'=>'Manage DailyReport', 'url'=>array('admin')),
);
?>

<h1>Create DailyReport</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>
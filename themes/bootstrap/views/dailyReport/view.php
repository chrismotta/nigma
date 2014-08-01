<?php
/* @var $this DailyReportController */
/* @var $model DailyReport */

$this->breadcrumbs=array(
	'Daily Reports'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List DailyReport', 'url'=>array('index')),
	array('label'=>'Create DailyReport', 'url'=>array('create')),
	array('label'=>'Update DailyReport', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete DailyReport', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage DailyReport', 'url'=>array('admin')),
);
?>

<h1>View DailyReport #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'campaigns_id',
		'networks_id',
		'imp',
		'clics',
		'conv_api',
		'conv_adv',
		'spend',
		'model',
		'value',
		'date',
	),
)); ?>

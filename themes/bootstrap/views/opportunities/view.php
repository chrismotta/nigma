<?php
/* @var $this OpportunitiesController */
/* @var $model Opportunities */

$this->breadcrumbs=array(
	'Opportunities'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Opportunities', 'url'=>array('index')),
	array('label'=>'Create Opportunities', 'url'=>array('create')),
	array('label'=>'Update Opportunities', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Opportunities', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Opportunities', 'url'=>array('admin')),
);
?>

<h1>View Opportunities #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'carriers_id',
		'rate',
		'model_adv',
		'product',
		'account_manager_id',
		'comment',
		'country_id',
		'wifi',
		'budget',
		'server_to_server',
		'startDate',
		'endDate',
		'ios_id',
	),
)); ?>

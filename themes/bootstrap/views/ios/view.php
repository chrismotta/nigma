<?php
/* @var $this IosController */
/* @var $model Ios */

$this->breadcrumbs=array(
	'Ioses'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List Ios', 'url'=>array('index')),
	array('label'=>'Create Ios', 'url'=>array('create')),
	array('label'=>'Update Ios', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Ios', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Ios', 'url'=>array('admin')),
);
?>

<h1>View Ios #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'status',
		'name',
		'address',
		'country',
		'state',
		'zip_code',
		'phone',
		'email',
		'contact_adm',
		'currency',
		'ret',
		'tax_id',
		'commercial_id',
		'entity',
		'net_payment',
		'advertisers_id',
	),
)); ?>

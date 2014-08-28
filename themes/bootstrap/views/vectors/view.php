<?php
$this->breadcrumbs=array(
	'Vectors'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List Vectors','url'=>array('index')),
	array('label'=>'Create Vectors','url'=>array('create')),
	array('label'=>'Update Vectors','url'=>array('update','id'=>$model->id)),
	array('label'=>'Delete Vectors','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Vectors','url'=>array('admin')),
);
?>

<h1>View Vectors #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
	),
)); ?>

<?php
$this->breadcrumbs=array(
	'Landing Images'=>array('index'),
	$model->id,
);

$this->menu=array(
array('label'=>'List LandingImages','url'=>array('index')),
array('label'=>'Create LandingImages','url'=>array('create')),
array('label'=>'Update LandingImages','url'=>array('update','id'=>$model->id)),
array('label'=>'Delete LandingImages','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
array('label'=>'Manage LandingImages','url'=>array('admin')),
);
?>

<h1>View LandingImages #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
'data'=>$model,
'attributes'=>array(
		'id',
		'file_name',
		'type',
),
)); ?>

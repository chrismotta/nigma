<?php
$this->breadcrumbs=array(
	'Api Logs'=>array('index'),
	$model->id,
);

$this->menu=array(
array('label'=>'List ApiLog','url'=>array('index')),
array('label'=>'Create ApiLog','url'=>array('create')),
array('label'=>'Update ApiLog','url'=>array('update','id'=>$model->id)),
array('label'=>'Delete ApiLog','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
array('label'=>'Manage ApiLog','url'=>array('admin')),
);
?>

<h1>View ApiLog #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
'data'=>$model,
'attributes'=>array(
		'id',
		'providers_id',
		'exchanges_id',
		'status',
		'start_time',
		'end_time',
		'data_date',
		'message',
),
)); ?>

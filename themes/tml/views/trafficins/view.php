<?php
$this->breadcrumbs=array(
	'Traffic Inspectors'=>array('index'),
	$model->id,
);

$this->menu=array(
array('label'=>'List TrafficInspector','url'=>array('index')),
array('label'=>'Create TrafficInspector','url'=>array('create')),
array('label'=>'Update TrafficInspector','url'=>array('update','id'=>$model->id)),
array('label'=>'Delete TrafficInspector','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
array('label'=>'Manage TrafficInspector','url'=>array('admin')),
);
?>

<h1>View TrafficInspector #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
'data'=>$model,
'attributes'=>array(
		'id',
		'tag_id',
		'server_data',
),
)); ?>

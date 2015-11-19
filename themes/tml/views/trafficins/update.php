<?php
$this->breadcrumbs=array(
	'Traffic Inspectors'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

	$this->menu=array(
	array('label'=>'List TrafficInspector','url'=>array('index')),
	array('label'=>'Create TrafficInspector','url'=>array('create')),
	array('label'=>'View TrafficInspector','url'=>array('view','id'=>$model->id)),
	array('label'=>'Manage TrafficInspector','url'=>array('admin')),
	);
	?>

	<h1>Update TrafficInspector <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>
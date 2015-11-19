<?php
$this->breadcrumbs=array(
	'Traffic Inspectors'=>array('index'),
	'Create',
);

$this->menu=array(
array('label'=>'List TrafficInspector','url'=>array('index')),
array('label'=>'Manage TrafficInspector','url'=>array('admin')),
);
?>

<h1>Create TrafficInspector</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
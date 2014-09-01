<?php
$this->breadcrumbs=array(
	'Vectors'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Vectors','url'=>array('index')),
	array('label'=>'Manage Vectors','url'=>array('admin')),
);
?>

<h1>Create Vectors</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
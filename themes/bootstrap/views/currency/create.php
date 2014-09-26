<?php
$this->breadcrumbs=array(
	'Currency'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Currency','url'=>array('index')),
	array('label'=>'Manage Currency','url'=>array('admin')),
);
?>

<h1>Create Vectors</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
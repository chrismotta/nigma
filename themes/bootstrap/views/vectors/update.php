<?php
$this->breadcrumbs=array(
	'Vectors'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Vectors','url'=>array('index')),
	array('label'=>'Create Vectors','url'=>array('create')),
	array('label'=>'View Vectors','url'=>array('view','id'=>$model->id)),
	array('label'=>'Manage Vectors','url'=>array('admin')),
);
?>

<h1>Update Vectors <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>
<?php
$this->breadcrumbs=array(
	'Campaigns'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Campaigns','url'=>array('index')),
	array('label'=>'Create Campaigns','url'=>array('create')),
	array('label'=>'View Campaigns','url'=>array('view','id'=>$model->id)),
	array('label'=>'Manage Campaigns','url'=>array('admin')),
);
?>

<h1>Update Campaigns <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>
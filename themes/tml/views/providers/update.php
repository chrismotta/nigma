<?php
$this->breadcrumbs=array(
	'Providers'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

	$this->menu=array(
	array('label'=>'List Providers','url'=>array('index')),
	array('label'=>'Create Providers','url'=>array('create')),
	array('label'=>'View Providers','url'=>array('view','id'=>$model->id)),
	array('label'=>'Manage Providers','url'=>array('admin')),
	);
	?>

	<h1>Update Providers <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>
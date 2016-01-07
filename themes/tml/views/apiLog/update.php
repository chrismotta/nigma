<?php
$this->breadcrumbs=array(
	'Api Logs'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

	$this->menu=array(
	array('label'=>'List ApiLog','url'=>array('index')),
	array('label'=>'Create ApiLog','url'=>array('create')),
	array('label'=>'View ApiLog','url'=>array('view','id'=>$model->id)),
	array('label'=>'Manage ApiLog','url'=>array('admin')),
	);
	?>

	<h1>Update ApiLog <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>
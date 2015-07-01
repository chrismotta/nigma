<?php
$this->breadcrumbs=array(
	'Daily Publishers'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

	$this->menu=array(
	array('label'=>'List DailyPublishers','url'=>array('index')),
	array('label'=>'Create DailyPublishers','url'=>array('create')),
	array('label'=>'View DailyPublishers','url'=>array('view','id'=>$model->id)),
	array('label'=>'Manage DailyPublishers','url'=>array('admin')),
	);
	?>

	<h1>Update DailyPublishers <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>
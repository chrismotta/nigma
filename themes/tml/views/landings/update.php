<?php
$this->breadcrumbs=array(
	'Landings'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

	$this->menu=array(
	array('label'=>'List Landings','url'=>array('index')),
	array('label'=>'Create Landings','url'=>array('create')),
	array('label'=>'View Landings','url'=>array('view','id'=>$model->id)),
	array('label'=>'Manage Landings','url'=>array('admin')),
	);
	?>

	<h1>Update Landings <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form',
	array(
		'model'=>$model,
		'background_images_id' => $background_images_id,
		'headline_images_id' => $headline_images_id,
		'byline_images_id' => $byline_images_id,
		)); ?>
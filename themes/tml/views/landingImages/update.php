<?php
$this->breadcrumbs=array(
	'Landing Images'=>array('thumbnails'),
	// $model->id=>array('view','id'=>$model->id),
	'Update',
);

	$this->menu=array(
	array('label'=>'List LandingImages','url'=>array('index')),
	array('label'=>'Create LandingImages','url'=>array('create')),
	array('label'=>'View LandingImages','url'=>array('view','id'=>$model->id)),
	array('label'=>'Manage LandingImages','url'=>array('admin')),
	);
	?>

	<h1>View / Update Images <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>
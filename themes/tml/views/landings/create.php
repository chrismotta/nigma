<?php
$this->breadcrumbs=array(
	'Landings'=>array('index'),
	'Create',
);

$this->menu=array(
array('label'=>'List Landings','url'=>array('index')),
array('label'=>'Manage Landings','url'=>array('admin')),
);
?>

<h1>Create Landings</h1>

<?php echo $this->renderPartial('_form', 
	array(
		'model'=>$model,
		'background_images_id' => $background_images_id,
		'headline_images_id' => $headline_images_id,
		'byline_images_id' => $byline_images_id,
		)); ?>
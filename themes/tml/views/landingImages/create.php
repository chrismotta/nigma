<?php
$this->breadcrumbs=array(
	'Landing Images'=>array('thumbnails'),
	'Create',
);

$this->menu=array(
array('label'=>'List LandingImages','url'=>array('index')),
array('label'=>'Manage LandingImages','url'=>array('admin')),
);
?>

<h1>Upload Image</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
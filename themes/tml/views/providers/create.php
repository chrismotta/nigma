<?php
$this->breadcrumbs=array(
	'Providers'=>array('index'),
	'Create',
);

$this->menu=array(
array('label'=>'List Providers','url'=>array('index')),
array('label'=>'Manage Providers','url'=>array('admin')),
);
?>

<h1>Create Providers</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
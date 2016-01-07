<?php
$this->breadcrumbs=array(
	'Api Logs'=>array('index'),
	'Create',
);

$this->menu=array(
array('label'=>'List ApiLog','url'=>array('index')),
array('label'=>'Manage ApiLog','url'=>array('admin')),
);
?>

<h1>Create ApiLog</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
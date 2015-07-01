<?php
$this->breadcrumbs=array(
	'Daily Publishers'=>array('index'),
	'Create',
);

$this->menu=array(
array('label'=>'List DailyPublishers','url'=>array('index')),
array('label'=>'Manage DailyPublishers','url'=>array('admin')),
);
?>

<h1>Create DailyPublishers</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
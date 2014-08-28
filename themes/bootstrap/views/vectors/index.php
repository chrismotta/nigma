<?php
$this->breadcrumbs=array(
	'Vectors',
);

$this->menu=array(
	array('label'=>'Create Vectors','url'=>array('create')),
	array('label'=>'Manage Vectors','url'=>array('admin')),
);
?>

<h1>Vectors</h1>

<?php $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>

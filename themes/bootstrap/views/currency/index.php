<?php
$this->breadcrumbs=array(
	'Currency',
);

$this->menu=array(
	array('label'=>'Create Currency','url'=>array('create')),
	array('label'=>'Manage Currency','url'=>array('admin')),
);
?>

<h1>Vectors</h1>

<?php $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>

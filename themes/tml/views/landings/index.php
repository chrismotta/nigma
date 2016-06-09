<?php
$this->breadcrumbs=array(
	'Landings',
);

$this->menu=array(
array('label'=>'Create Landings','url'=>array('create')),
array('label'=>'Manage Landings','url'=>array('admin')),
);
?>

<h1>Landings</h1>

<?php $this->widget('bootstrap.widgets.TbListView',array(
'dataProvider'=>$dataProvider,
'itemView'=>'_view',
)); ?>

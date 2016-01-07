<?php
$this->breadcrumbs=array(
	'Api Logs',
);

$this->menu=array(
array('label'=>'Create ApiLog','url'=>array('create')),
array('label'=>'Manage ApiLog','url'=>array('admin')),
);
?>

<h1>Api Logs</h1>

<?php $this->widget('bootstrap.widgets.TbListView',array(
'dataProvider'=>$dataProvider,
'itemView'=>'_view',
)); ?>

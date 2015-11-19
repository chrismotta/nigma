<?php
$this->breadcrumbs=array(
	'Traffic Inspectors',
);

$this->menu=array(
array('label'=>'Create TrafficInspector','url'=>array('create')),
array('label'=>'Manage TrafficInspector','url'=>array('admin')),
);
?>

<h1>Traffic Inspectors</h1>

<?php $this->widget('bootstrap.widgets.TbListView',array(
'dataProvider'=>$dataProvider,
'itemView'=>'_view',
)); ?>

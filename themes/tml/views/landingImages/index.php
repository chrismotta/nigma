<?php
$this->breadcrumbs=array(
	'Landing Images',
);

$this->menu=array(
array('label'=>'Create LandingImages','url'=>array('create')),
array('label'=>'Manage LandingImages','url'=>array('admin')),
);
?>

<h1>Landing Images</h1>

<?php $this->widget('bootstrap.widgets.TbListView',array(
'dataProvider'=>$dataProvider,
'itemView'=>'_view',
)); ?>

<?php
$this->breadcrumbs=array(
	'Sites',
);

$this->menu=array(
array('label'=>'Create Sites','url'=>array('create')),
array('label'=>'Manage Sites','url'=>array('admin')),
);
?>

<h1>Sites</h1>

<?php $this->widget('bootstrap.widgets.TbListView',array(
'dataProvider'=>$dataProvider,
'itemView'=>'_view',
)); ?>

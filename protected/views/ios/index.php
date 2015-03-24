<?php
/* @var $this IosController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Ioses',
);

$this->menu=array(
	array('label'=>'Create Ios', 'url'=>array('create')),
	array('label'=>'Manage Ios', 'url'=>array('admin')),
);
?>

<h1>Ioses</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>

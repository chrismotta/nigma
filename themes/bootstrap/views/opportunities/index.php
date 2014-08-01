<?php
/* @var $this OpportunitiesController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Opportunities',
);

$this->menu=array(
	array('label'=>'Create Opportunities', 'url'=>array('create')),
	array('label'=>'Manage Opportunities', 'url'=>array('admin')),
);
?>

<h1>Opportunities</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>

<?php
/* @var $this OpportunitiesController */
/* @var $model Opportunities */

$this->breadcrumbs=array(
	'Opportunities'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Opportunities', 'url'=>array('index')),
	array('label'=>'Manage Opportunities', 'url'=>array('admin')),
);
?>

<h1>Create Opportunities</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>
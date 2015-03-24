<?php
/* @var $this IosController */
/* @var $model Ios */

$this->breadcrumbs=array(
	'Ioses'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Ios', 'url'=>array('index')),
	array('label'=>'Manage Ios', 'url'=>array('admin')),
);
?>

<h1>Create Ios</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>
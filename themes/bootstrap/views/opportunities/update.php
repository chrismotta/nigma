<?php
/* @var $this OpportunitiesController */
/* @var $model Opportunities */

$this->breadcrumbs=array(
	'Opportunities'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Opportunities', 'url'=>array('index')),
	array('label'=>'Create Opportunities', 'url'=>array('create')),
	array('label'=>'View Opportunities', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Opportunities', 'url'=>array('admin')),
);
?>

<h1>Update Opportunities <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>
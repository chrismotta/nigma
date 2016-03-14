<?php
$this->breadcrumbs=array(
	'Tags'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

	$this->menu=array(
	array('label'=>'List Tags','url'=>array('index')),
	array('label'=>'Create Tags','url'=>array('create')),
	array('label'=>'View Tags','url'=>array('view','id'=>$model->id)),
	array('label'=>'Manage Tags','url'=>array('admin')),
	);
	?>

<div class="alert alert-info">
	<?php if($parent=='c') echo '<h4 class="line-bottom">Update Tag</h4>'; ?>

<?php echo $this->renderPartial('_form',array(
	'model'=>$model,
	'bannerSizes'=>$bannerSizes,
	)); ?>

</div>

<?php if($parent=='c') echo CHtml::link('<- Back to list',array('adminByCampaign','id'=>$model->campaigns_id)); ?>
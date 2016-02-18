<?php
$this->breadcrumbs=array(
	'Tags'=>array('index'),
	'Create',
);

$this->menu=array(
array('label'=>'List Tags','url'=>array('index')),
array('label'=>'Manage Tags','url'=>array('admin')),
);
?>

<div class="alert alert-info">
	<h4 class="line-bottom">Create Tag</h4>

<?php echo $this->renderPartial('_form', array(
	'model'=>$model,
	'bannerSizes'=>$bannerSizes,
	)); ?>

</div>
<?php echo CHtml::link('<- Back to list',array('adminByCampaign','id'=>$model->campaigns_id)); ?>
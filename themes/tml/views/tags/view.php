<?php
$this->breadcrumbs=array(
	'Tags'=>array('index'),
	$model->id,
);

$this->menu=array(
array('label'=>'List Tags','url'=>array('index')),
array('label'=>'Create Tags','url'=>array('create')),
array('label'=>'Update Tags','url'=>array('update','id'=>$model->id)),
array('label'=>'Delete Tags','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
array('label'=>'Manage Tags','url'=>array('admin')),
);
?>

	<label for="comment"><strong>Tag detail</strong></label>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
'data'=>$model,
'attributes'=>array(
		'id',
		'campaigns_id',
		'banner_sizes_id',
		'code',
		'comment',
		'freq_cap',
		'analyze',
),
)); ?>

<?php echo CHtml::link('<- Back to list',array('adminByCampaign','id'=>$model->campaigns_id)); ?>
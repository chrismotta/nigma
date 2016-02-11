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

<h1>View Tags #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
'data'=>$model,
'attributes'=>array(
		'id',
		'campaigns_id',
		'banner_sizes_id',
		'type',
		'code',
		'comment',
		'analyze',
),
)); ?>

<?php 
$width = $model->bannerSizes->width;
$height = $model->bannerSizes->height;
$id = $model->id;
?>
<div class="alert alert-info">
	<div class="form-group">
		<label for="comment"><strong>Get Tag</strong></label>
		<textarea class="form-control" rows="2" id="comment" style="width:100%"><iframe src="http://tmlbox.co/tag/<?php echo $id ?>?pid=<placementID>&pubid=<INSERT_PUBID_MACRO_HERE>" width="<?php echo $width ?>" height="<?php echo $height ?>" frameborder="0" scrolling="no" ></iframe></textarea>
	</div>
</div>
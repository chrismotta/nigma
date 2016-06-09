<?php
$this->breadcrumbs=array(
	'Landings'=>array('index'),
	$model->id,
);

$this->menu=array(
array('label'=>'List Landings','url'=>array('index')),
array('label'=>'Create Landings','url'=>array('create')),
array('label'=>'Update Landings','url'=>array('update','id'=>$model->id)),
array('label'=>'Delete Landings','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
array('label'=>'Manage Landings','url'=>array('admin')),
);
?>

<h1>View Landings #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
'data'=>$model,
'attributes'=>array(
		'id',
		'default_color',
		'highlight_color',
		'background_color',
		'background_images_id',
		'headline',
		'byline',
		'input_legend',
		'input_label',
		'input_eg',
		'select_label',
		'select_options',
		'tyc_headline',
		'tyc_body',
		'checkbox_label',
		'button_label',
),
)); ?>

<?php
$this->breadcrumbs=array(
	'Landings'=>array('index'),
	'Manage',
);

$this->menu=array(
array('label'=>'List Landings','url'=>array('index')),
array('label'=>'Create Landings','url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
$('.search-form').toggle();
return false;
});
$('.search-form form').submit(function(){
$.fn.yiiGridView.update('landings-grid', {
data: $(this).serialize()
});
return false;
});
");
?>

<h1>Manage Landings</h1>

<p>
	You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>
		&lt;&gt;</b>
	or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button btn')); ?>
<div class="search-form" style="display:none">
	<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('bootstrap.widgets.TbGridView',array(
'id'=>'landings-grid',
'dataProvider'=>$model->search(),
'filter'=>$model,
'columns'=>array(
		'id',
		'default_color',
		'highlight_color',
		'background_color',
		'background_images_id',
		'headline',
		/*
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
		*/
array(
'class'=>'bootstrap.widgets.TbButtonColumn',
),
),
)); ?>

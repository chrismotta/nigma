<?php
$this->breadcrumbs=array(
	'Landing Images'=>array('index'),
	'Manage',
);

$this->menu=array(
array('label'=>'List LandingImages','url'=>array('index')),
array('label'=>'Create LandingImages','url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
$('.search-form').toggle();
return false;
});
$('.search-form form').submit(function(){
$.fn.yiiGridView.update('landing-images-grid', {
data: $(this).serialize()
});
return false;
});
");
?>

<h1>Manage Landing Images</h1>

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

<?php $this->widget('bootstrap.widgets.TbExtendedGridView',array(
	'id'=>'landing-images-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
			'id',
			'file_name',
			'type',
	array(
		'class'=>'bootstrap.widgets.TbImageColumn',
		'imagePathExpression'=>'$data->getImagePath($data->file_name)',
		'imageOptions' => array('class'=>'grid-images img-polaroid'),
		),
	array(
		'class'=>'bootstrap.widgets.TbButtonColumn',
		),
	),
)); ?>

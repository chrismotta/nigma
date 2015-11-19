<?php
$this->breadcrumbs=array(
	'Traffic Inspectors'=>array('index'),
	'Manage',
);

$this->menu=array(
array('label'=>'List TrafficInspector','url'=>array('index')),
array('label'=>'Create TrafficInspector','url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
$('.search-form').toggle();
return false;
});
$('.search-form form').submit(function(){
$.fn.yiiGridView.update('traffic-inspector-grid', {
data: $(this).serialize()
});
return false;
});
");
?>

<h1>Manage Traffic Inspectors</h1>

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
'id'=>'traffic-inspector-grid',
'dataProvider'=>$model->search(),
'filter'=>$model,
'columns'=>array(
	'id',
	'tag_id',
	array(
		'name'=>'server_data',
		'htmlOptions'=>array('style'=>'width:900px;word-wrap:break-word;word-break:break-all;'),
		),
	// array(
	// 'class'=>'bootstrap.widgets.TbButtonColumn',
	// ),
),
)); ?>

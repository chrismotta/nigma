<?php
$this->breadcrumbs=array(
	'Sites'=>array('index'),
	'Manage',
);
?>

<?php 
/*
$this->menu=array(
	array('label'=>'List Sites','url'=>array('index')),
	array('label'=>'Create Sites','url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
	$('.search-button').click(function(){
		$('.search-form').toggle();
		return false;
	});
	$('.search-form form').submit(function(){
		$.fn.yiiGridView.update('sites-grid', {
			data: $(this).serialize()
		});
		return false;
	});
");

echo '
<h1>Manage Sites</h1>

<p>
	You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>
		&lt;&gt;</b>
	or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>
'

echo CHtml::link('Advanced Search','#',array('class'=>'search-button btn')); ?>
<div class="search-form" style="display:none">
	<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); 
echo '</div><!-- search-form -->'
*/
?>

<?php BuildGridView::createButton($this, 'modalSites', 'Create Site'); ?>

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'sites-grid',
	'dataProvider' => $model->search(),
	'filter'       => $model,
	'type'                     => 'striped condensed',
	'rowHtmlOptionsExpression' => 'array("data-row-id" => $data->id)',
	'template'                 => '{items} {pager} {summary}',
	'columns'    => array(
		array(
			'name'              =>'id',
			'headerHtmlOptions' => array('style'=>'width:100px'),
		),
		'name',
		array( 
			'name'  => 'publishers_name',
			'value' => '$data->publishersProviders->providers->name',
		),
		BuildGridView::buttonColumn('modalSites'),
	),
)); ?>

<?php BuildGridView::printModal($this, 'modalSites', 'Sites'); ?>


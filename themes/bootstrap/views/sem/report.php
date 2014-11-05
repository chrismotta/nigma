<?php
/* @var $this SemController */
/* @var $model Users */

$this->breadcrumbs=array(
	'Sem'=>array('index'),
	'Sem Users',
);

$this->menu=array(
	// array('label'=>'List Users', 'url'=>array('index')),
	// array('label'=>'Create Users', 'url'=>array('create')),
);
?>

<?php $this->widget('bootstrap.widgets.TbGroupGridView', array(
	'filter'              => $model,
	'type'                => 'striped bordered',
	'dataProvider'        => $model->searchSem($report),
	'template'            => "{items}",
	// 'extraRowColumns'     => array('firstLetter'),
	// 'extraRowExpression'  => '"<b style=\"font-size: 3em; color: #333;\">".substr($data->firstName, 0, 1)."</b>"',
	// 'extraRowHtmlOptions' => array('style'=>'padding:10px'),
	'columns'             => array(
		'campaigns_id',
		'keyword',
		array(
			'header'  => 'Clicks',
			'value' => '$data->totalClicks',
		),
		array(
			'header'  => 'Conversions',
			'value' => '$data->totalConv',
		),
	),
	'mergeColumns' => array('campaigns_id')
)); ?> 
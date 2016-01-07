<?php
$this->breadcrumbs=array(
	'Api Log',
	);

?>

<?php $this->widget('application.components.NiExtendedGridView',array(
	'id'           => 'api-log-grid',
	'dataProvider' => $model->search(),
	'filter'       => $model,
	'template'     => '{items} {pagerExt} {summary}',
	'columns'=>array(
		'id',
		'providers_name',
		'exchanges_name',
		array(
			'name' => 'status',
			'htmlOptions' => array('style'=>'width:60px'),
			),
		'start_time',
		'end_time',
		array(
			'name' => 'elapsed_time',
			'htmlOptions' => array('style'=>'width:50px'),
			),
		'data_date',
		array(
			'name' => 'message',
			'htmlOptions' => array('style'=>'width:250px'),
			)
		// array(
		// 	'class'=>'bootstrap.widgets.TbButtonColumn',
		// 	),
		),
		)); ?>

<?php
$this->breadcrumbs=array(
	'Traffic Inspectors',
);

$this->widget('bootstrap.widgets.TbGridView',array(
	'id'           => 'traffic-inspector-grid',
	'dataProvider' => $model->search(),
	'filter'       => $model,
	'type'         => 'striped condensed',
	'template'     => '{items} {pager} {summary}',
	'columns'      => array(
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
)); 

?>
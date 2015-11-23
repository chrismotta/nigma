<?php
$this->breadcrumbs=array(
	'Traffic Inspectors'=>array('admin'),
	$model->id,
);
?>


<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'date_time',
		'tag_id',
		'pub_id',
		'href',
		array(
			'name'=>'server_data',
			'cssClass'=>'detail-data',
			),
	),
)); ?>

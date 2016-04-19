<?php 

$this->widget('application.components.NiExtendedGridView', array(
	'id'              => 'imp-log-grid',
	'dataProvider'    => $model->search(),
	'filter'          => null,
	'type'            => 'condensed',
	'columns'         => array(
		'advertiser',
		'trafficSource',
		'connectionType',
		'country',
		'osType',
		'osVersion',
		'revenue',
		'cost',
		)
));



?>
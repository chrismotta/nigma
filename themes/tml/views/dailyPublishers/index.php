<?php
	$this->breadcrumbs=array(
		'Daily Reports'=>array('index'),
		'Manage',
	);
	
	$dataProvider=$model->search();
	// $dataProvider=$model->search($dateStart, $dateEnd, $accountManager, $opportunities, $providers, $sum, $adv_categories);
	// $totals=$model->searchTotals($dateStart, $dateEnd, $accountManager, $opportunities, $providers, $sum, $adv_categories);

	$this->widget('bootstrap.widgets.TbExtendedGridView', array(
		'id'                       => 'daily-report-grid',
		// 'fixedHeader'              => true,
		'headerOffset'             => 50,
		'dataProvider'             => $dataProvider,
		'filter'                   => $model,
		'type'                     => 'striped condensed',
		'rowHtmlOptionsExpression' => 'array("data-row-id" => $data->id)',
		'template'                 => '{items} {pager} {summary}',
		'columns' => array(
			array(
				'name'        => 'id',
				'htmlOptions' => array('style'=>'width:40px'),
				),
			array(
				'name'        => 'date',
				'htmlOptions' => array('style'=>'width:70px'),
				),
			array(
				'name'  => 'exchanges_id',
				'value' => '$data->exchanges->name',
				'htmlOptions' => array('style'=>'width:60px'),
				),
			array(
				'name'  => 'placements_id',
				'value' => '"#".$data->placements_id . ": " . $data->placements->name',
				),
			array(
				'header'=> 'Sites',
				'name'  => 'placements_id',
				'value' => '$data->placements->sites->name',
				),
			array(
				'header'=> 'Publishers',
				'name'  => 'placements_id',
				'value' => '$data->placements->sites->publishersProviders->providers->name',
				),
			// 'country_id',
			// 'devices_id',
			array(
				'name'        => 'ad_request',
				'htmlOptions' => array('style'=>'width:70px; text-align:right;'),
				),
			array(
				'name'        => 'imp_publishers',
				'htmlOptions' => array('style'=>'width:70px; text-align:right;'),
				),
			array(
				'name'        => 'imp_passback',
				'htmlOptions' => array('style'=>'width:70px; text-align:right;'),
				),
			array(
				'name'        => 'revenue',
				'htmlOptions' => array('style'=>'width:70px; text-align:right;'),
				),
			)
		));
?>
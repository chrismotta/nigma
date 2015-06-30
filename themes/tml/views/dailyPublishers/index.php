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
		'fixedHeader'              => true,
		'headerOffset'             => 50,
		'dataProvider'             => $dataProvider,
		'filter'                   => $model,
		'type'                     => 'striped condensed',
		'rowHtmlOptionsExpression' => 'array("data-row-id" => $data->id)',
		'template'                 => '{items} {pager} {summary}',
		));
?>
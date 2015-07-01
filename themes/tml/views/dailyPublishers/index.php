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
        array(
			'class'             => 'bootstrap.widgets.TbButtonColumn',
			'headerHtmlOptions' => array('style' => "width: 20px"),
			'template' => '{updateAjax}',
			'buttons'           => array(
				'updateAjax' => array(
					'label'   => 'Update',
					'icon'    => 'pencil',
					// 'visible' => '!$data->is_from_api',
					'click'   => '
				    function(){
				    	// get row id from data-row-id attribute
				    	var id = $(this).parents("tr").attr("data-row-id");

				    	var dataInicial = "<div class=\"modal-header\"></div><div class=\"modal-body\" style=\"padding:100px 0px;text-align:center;\"><img src=\"'.  Yii::app()->theme->baseUrl .'/img/loading.gif\" width=\"40\" /></div><div class=\"modal-footer\"></div>";
						$("#modalDailyReport").html(dataInicial);
						$("#modalDailyReport").modal("toggle");

				    	
				    	// use jquery post method to get updateAjax view in a modal window
				    	$.post(
						"dailyPublishers/update/"+id,
						"",
						function(data)
							{
								//alert(data);
								$("#modalDailyReport").html(data);
							}
						)
					return false;
				    }
				    ',
					),
				),
			),
		)
	));
?>

<?php $this->beginWidget('bootstrap.widgets.TbModal', array('id'=>'modalDailyReport')); ?>

		<div class="modal-header"></div>
        <div class="modal-body"></div>
        <div class="modal-footer"></div>

<?php $this->endWidget(); ?>

<div class="row" id="blank-row">
</div>
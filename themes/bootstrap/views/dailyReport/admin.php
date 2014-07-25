<?php
/* @var $this DailyReportController */
/* @var $model DailyReport */

$this->breadcrumbs=array(
	'Daily Reports'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List DailyReport', 'url'=>array('index')),
	array('label'=>'Create DailyReport', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#daily-report-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h2>Manage Daily Reports</h2>

<div class="row">
	<div id="container-highchart" class="span12">
	<?php
	$this->Widget('ext.highcharts.HighstockWidget', array(
		'options'=>array(
			'chart'         => array( 'type' => 'area' ),
			'title'         => array( 'text' => ''),
			'rangeSelector' => array( 'enabled' => false ),
			'navigator'     => array( 'enabled' => false ),
			'scrollbar'     => array( 'enabled' => false ),
			'tooltip'       => array( 'crosshairs'=>'true', 'shared'=>'true' ),
			'legend'        => array(
				'align'           =>  'left',
				'borderWidth'     =>  1,
				'backgroundColor' => '#FFFFFF',
				'enabled'         =>  true,
				'floating'        =>  true,
				'layout'          => 'horizontal',
				'verticalAlign'   =>  'top',
	        	),
			
			'xAxis' => array( 
				'title' => array('text' => ''), 
				'categories' => array('14-07-2014', '15-07-2014', '16-07-2014', '17-07-2014', '18-07-2014', '19-07-2014', '20-07-2014', '21-07-2014')
				),
			'yAxis' => array( 'title' => array('text' => '') ),
			'series' => array(
				array(
					'name' => 'Spend',
					'data' => array( 10.22, 22.2 , 0, 10, 34, 45, 20, 15 ),
					),
				array(
					'name' => 'Conv',
					'data' => array( 02, 94, 124, 5, 82, 82, 82, 82 ),
					),
				array(
					'name' => 'Impressions', 
					'data' => array( 1022, 9993, 1012, 1000, 1498, 2498, 1298, 2698 ),
					),
				array(
					'name' => 'Clicks', 
					'data' => array(422, 393, 612, 500, 298, 398, 198, 408 ),
					),
				),
			)
		)
	);
	?>
	</div>
</div>


<?php $this->widget('bootstrap.widgets.TbGridView', array(
	'id'                    => 'daily-report-grid',
	'dataProvider'          => $model->search(),
	'filter'                => $model,
	'selectionChanged'      => 'js:selectionChangedDailyReport',
	'template'                 => '{items} {pager} {summary}',
	'columns'                  => array(
		array(
			'name'  =>	'id',
        	'headerHtmlOptions' => array('style' => 'width: 70px'),
        	'htmlOptions'	=> array( 'class' =>  'id'),
		),
		array(
			'name'  =>	'campaigns_id',
			'headerHtmlOptions' => array('style' => 'width: 80px'),
			'htmlOptions'	=> array( 'class' =>  'campaign_id'),
		),
		array(
			'name'  =>	'networks_id',
			'htmlOptions'	=> array( 'class' =>  'network_id', 'style' => 'width:65px;'),
		),
		array(
			'name'  =>	'network_name',
			'value'	=>	'$data->networks->name',
		),
		array(	
			'name'	=>	'imp',
        ),
        array(
        	'name'	=>	'clics',
        ),
		array(
        	'name'	=>	'conv_api',
        	'htmlOptions'=>array('style'=>'width: 70px'),
        ),
		array(
        	'name'	=>	'conv_adv',
        	'type'	=>	'raw',
			'htmlOptions'=>array('style'=>'width: 70px'),
        	'value' =>	'
        			CHtml::textField("row-" . $row, $data->conv_adv, array(
        				"style" => "width:20px;", 
        				"onkeydown" => "$( \"#row-\" + $row ).parents( \"tr\" ).addClass( \"control-group error\" );" 
        				)) . " " .
        			CHtml::ajaxLink(
            				"<i class=\"icon-pencil\"></i>",
	            			Yii::app()->controller->createUrl("updateColumn"),
	        				array(
								"type"     => "POST",
								"dataType" => "json",
								"data"     => array( "id" => "js:$.fn.yiiGridView.getKey(\"daily-report-grid\", $row)",	 "newValue" => "js:$(\"#row-\" + $row).val()" ) ,
								"success"  => "function( data )
									{
										// change css properties
										$( \"#row-\" + $row ).parents( \"tr\" ).removeClass( \"control-group error\" );
										$( \"#row-\" + $row ).parents( \"tr\" ).addClass( \"control-group success\" );
									}",
								),
							array(
								"style"               => "width: 20px",
								"rel"                 => "tooltip",
								"data-original-title" => "Update"
								)
						)
					',
        ),
		array(
        	'name'	=>	'spend',
        	'value'	=>	'"$ " . $data->spend',
        ),
		array(
        	'name'	=>	'model',
        ),
		array(
        	'name'	=>	'value',
        ),
		array(
        	'name'	=>	'date',
        	'value'	=>	'date("d-m-Y", strtotime($data->date))',
        	'htmlOptions'	=> array( 'class' =>  'date'),
        ),
	),
)); ?>

<!-- <p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>
-->

<div class="row" id="blank-row">
</div>
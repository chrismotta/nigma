<?php
Yii::app()->clientScript->registerScript('getGraphData', "
	var data = function(id) {
		var obj-id = $.fn.yiiGridView.getSelection(id);
		$.ajax({
			url:
			data: obj-id
		});
	}
");
?>

<div class="container">
	<div class="span12">
	<?php
	$graph = $this->Widget('ext.highcharts.HighstockWidget', array(
		'options'=>array(
			'chart' => array('type' => 'area'),
			'title' => array('text' => ''),
			'rangeSelector'	=>	array(
				'buttons'	=>	array(),
				'inputDateFormat'	=>	'%d-%m-%Y',
				'inputEditDateFormat'	=>	'%d-%m-%Y',
				),
			'navigator'	=>	array(
				'enabled'	=> false,
				),
			'scrollbar'	=>	array(
				'enabled'	=>	false,
				),
			'tooltip' => array('crosshairs'=>'true', 'shared'=>'true'),
	        'legend' => array(
	            'align' =>  'left',
	            'borderWidth' =>  1,
	            'backgroundColor' => '#FFFFFF',
	            'enabled'	=>	true,
	            'floating' =>  true,
	            'layout' => 'horizontal',
	            'verticalAlign' =>  'top',
	        	),
			
			'xAxis' => array(
				'range'	=>	7 * 24 * 3600 * 1000, // 1 week
				'event'	=>	array(
					'afterSetExtremes'	=> 'function(event) {
						var rowId = $.fn.yiiGridView.getSelection(id);

						var currentExtremes = this.getExtremes(),
						range = e.max - e.min,
						chart = $("#container").highcharts();
						
						chart.showLoading("Loading data from server...");

						$.post(
							"graphic",
							"c_id="+rowId+"&startDate="+e.min+"&endDate="+e.max,
							function(data)
								{
									alert(data);
								},
							"json"
						)

						// chart.series[0].setData(data);
						// chart.hideLoading();
					}'
					),
				),
			'yAxis' => array(
				'title' => array('text' => ''),
				),
			'series' => array(
				// ['date'=>'2003,8,24','impression'=>8709],
				// ['date'=>'2003,8,25','impression'=>872],
				// ['date'=>'2003,8,26','impression'=>8714],
				// ['date'=>'2003,8,29','impression'=>8638],
				// ['date'=>'2003,8,30','impression'=>8567],

				array('name' => 'categories', 'data' => array('8-5-2014','9-5-2014','10-5-2014','11-5-2014','12-5-2014','13-5-2014','14-5-2014','8-5-2014','9-5-2014','10-5-2014','11-5-2014','12-5-2014','13-5-2014','14-5-2014') ),
				array('name' => 'Spend', 'data' => array(1022, 934, 1124, 1005, 982, 1348, 1298, 1022, 934, 1124, 1005, 982, 1348, 1298) ),
				array('name' => 'Conv', 'data' => array(22, 34, 24, 15, 82, 48, 98, 22, 34, 24, 15, 82, 38, 98) ),
				),
			),
		)
	);
	?>
	</div>
</div>

$("#container-highstock").highcharts("StockChart", {
	chart : { type : area },
	title : { text : "" },
	rangeSelector	:	{
		buttons	: [],
		inputDateFormat	:	"%d-%m-%Y",
		inputEditDateFormat	:	"%d-%m-%Y",
		},
	navigator	:	{
		enabled	: false,
		},
	scrollbar	:	{
		enabled	:	false,
		},
	tooltip : { crosshairs:true, shared:true },
	legend : {
		align :	left,
		borderWidth : 1,
		backgroundColor : #FFFFFF,
		enabled	:	true,
		floating: true,
		layout:horizontal,
		verticalAlign:top,
		},
	series : [{
    	data: [ [Date.UTC(2003,8,24),0.8709],
			[Date.UTC(2003,8,25),0.872],
			[Date.UTC(2003,8,26),0.8714],
			[Date.UTC(2003,8,29),0.8638],
			[Date.UTC(2003,8,30),0.8567] ]
    }],
});
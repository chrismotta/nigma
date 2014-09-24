<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
$dateStart="-2 week 2 day";
$dateEnd="yesterday";
?>

<div class="row">
	<div class="span12">
	<?php
	
	$this->Widget('ext.highcharts.HighchartsWidget', array(
		'options'=>array(
			'chart' => array('type' => 'area'),
			'title' => array('text' => ''),
			'xAxis' => array(
				'categories' => $dataHighchart['dates']
				),
			'tooltip' => array('crosshairs'=>'true', 'shared'=>'true'),
			'yAxis' => array(
				'title' => array('text' => '')
				),
			'series' => array(
				array('name' => 'Spend', 'data' =>$dataHighchart['spends']),
				array('name' => 'Revenue', 'data' =>$dataHighchart['revenues']),
				array('name' => 'Profit', 'data' =>$dataHighchart['profits']),
				),
	        'legend' => array(
	            'layout' => 'vertical',
	            'align' =>  'left',
	            'verticalAlign' =>  'top',
	            'x' =>  40,
	            'y' =>  3,
	            'floating' =>  true,
	            'borderWidth' =>  1,
	            'backgroundColor' => '#FFFFFF'
	        	)
			),
		)
	);
	
	?>
	
	</div>
</div>

<div class="row" id="top">
	<div class="span6">
		<h4>Top Conversions</h4>
	</div>
	<div class="span6">
		<h4>Top Conversion Rate</h4>
	</div>
</div>
<div class="row" id="top">
	<div class="span4">
		<?php
		$this->widget('bootstrap.widgets.TbGridView', array(
			'id'=>'topcampaigns-grid',
			'type'=>'striped condensed',
			'dataProvider'=>$dataTopConversions['dataProvider'],
			'template'                 =>'{items}',
			'columns'=>array(
				array(
					'name'   => 'id',
		        	'value'  => '$data->campaigns->id',
		        ),
				array(
					'name'   => 'name',
		        	'value'  => '$data->campaigns->name',
		        ),
				array(
					'name'   => 'Conversions',
		        	'value'  => '$data->conversions',
		        ),
			),
		));
		?>
	</div>
	<div class="span2">
		<?php
		$this->Widget('ext.highcharts.HighchartsWidget', array(
			'options'=>array(
				'chart' => array('type' => 'column', 'height' => 200),
				'title' => array('text' => ''),
				'tooltip' => array('crosshairs'=>'true', 'shared'=>'true'),
				'xAxis' => array(
					'categories' => $dataTopConversions['array']['campaigns_id'],
					'labels' => array(
	                    'rotation' => -45,
	                    'align' => 'right',
	                    'style' => array(
	                        'fontSize' => '9px',
	                        'fontFamily' => 'Verdana, sans-serif'
	                    	)
	                    )
					),
				/*'tooltip' => array('crosshairs'=>'true', 'shared'=>'true'),*/
				'yAxis' => array(
					'title' => array('text' => '')
					),
				'series' => array(
					array('name' => 'Conversions', 'data' => $dataTopConversions['array']['conversions']),
					//array('name' => 'Clicks', 'data' => array(205, 189, 215, 133, 192)),
					//array('name' => 'Conversions', 'data' => array(12, 21, 29, 19, 12))
					),
		        'legend' => array(
		            'enabled' => false
		        	),
		            'plotOptions' => array(
		                'column' => array(
		                	'groupPadding' => 0.05,
		                    'stacking' => 'normal',
		                    'dataLabels' => array(
		                        'enabled' => false,
		                        /*'color' => (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white',
		                        'style' => array(
		                            'textShadow' => '0 0 3px black, 0 0 3px black'
		                        )*/
		                    )
		                )
		            ),
				),
			)
		);
		?>
	</div>
	<div class="span4" id="top">
		<?php
		$this->widget('bootstrap.widgets.TbGridView', array(
			'id'=>'campaigns-grid',
			'type'=>'striped condensed',
			'dataProvider'=>$dataTopConversionsRate['dataProvider'],
			'template'                 =>'{items}', 
			'columns'=>array(
				array(
					'name'   => 'id',
		        	'value'  => '$data->campaigns->id',
		        ),
				array(
					'name'   => 'name',
		        	'value'  => '$data->campaigns->name',
		        ),
				array(
					'name'   => 'Conversions Rate',
		        	'value'  => '$data->convrate."%"',
		        ),
			),
		));
		?>
	</div>
	<div class="span2">
		<?php
		$this->Widget('ext.highcharts.HighchartsWidget', array(
			'options'=>array(
				'chart' => array('type' => 'column', 'height' => 200),
				'title' => array('text' => ''),
				'tooltip' => array('crosshairs'=>'true', 'shared'=>'true'),
				'xAxis' => array(
					'categories' => $dataTopConversionsRate['array']['campaigns_id'],
					'labels' => array(
	                    'rotation' => -45,
	                    'align' => 'right',
	                    'style' => array(
	                        'fontSize' => '9px',
	                        'fontFamily' => 'Verdana, sans-serif'
	                    	)
	                    )
					),
				/*'tooltip' => array('crosshairs'=>'true', 'shared'=>'true'),*/
				'yAxis' => array(
					'title' => array('text' => '')
					),
				'series' => array(
					array('name' => 'Conversions Rate', 'data' => $dataTopConversionsRate['array']['conversions_rate']),
					//array('name' => 'Clicks', 'data' => array(205, 189, 215, 133, 192)),
					//array('name' => 'Conversions', 'data' => array(12, 21, 29, 19, 12))
					),
		        'legend' => array(
		            'enabled' => false
		        	),
		            'plotOptions' => array(
		                'column' => array(
		                	'groupPadding' => 0.05,
		                    'stacking' => 'normal',
		                    'dataLabels' => array(
		                        'enabled' => false,
		                        /*'color' => (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white',
		                        'style' => array(
		                            'textShadow' => '0 0 3px black, 0 0 3px black'
		                        )*/
		                    )
		                )
		            ),
				),
			)
		);
		?>
	</div>
</div>



<div class="row" id="top">
	<div class="span6">
		<h4>Top Spend</h4>
	</div>
	<div class="span6">
		<h4>Top Profit</h4>
	</div>
</div>
<div class="row" id="top">
	<div class="span4">
		<?php
		$this->widget('bootstrap.widgets.TbGridView', array(
			'id'=>'campaigns-grid',
			'type'=>'striped condensed',
			'dataProvider'=>$dataTops['dataProvider'],
			'columns'=>array(
				array(
					'name'   => 'id',
		        	'value'  => '$data->campaigns->id',
		        ),
				array(
					'name'   => 'name',
		        	'value'  => '$data->campaigns->name',
		        ),
				array(
					'name'   => 'Spends',
		        	'value'  => '$data->getSpendUSD()',
		        ),
		    ),
		));
		?>
	</div>
	<div class="span2">
		<?php
		$this->Widget('ext.highcharts.HighchartsWidget', array(
			'options'=>array(
				'chart' => array('type' => 'column', 'height' => 200),
				'title' => array('text' => ''),
				'tooltip' => array('crosshairs'=>'true', 'shared'=>'true'),
				'xAxis' => array(
					'categories' => $dataTops['array']['campaigns_id'],
					'labels' => array(
	                    'rotation' => -45,
	                    'align' => 'right',
	                    'style' => array(
	                        'fontSize' => '9px',
	                        'fontFamily' => 'Verdana, sans-serif'
	                    	)
	                    )
					),
				//'tooltip' => array('crosshairs'=>'true', 'shared'=>'true'),
				'yAxis' => array(
					'title' => array('text' => '')
					),
				'series' => array(
					array('name' => 'Spends', 'data' => $dataTops['array']['spends']),
					//array('name' => 'Clicks', 'data' => array(205, 189, 215, 133, 192)),
					//array('name' => 'Conversions', 'data' => array(12, 21, 29, 19, 12))
					),
		        'legend' => array(
		            'enabled' => false
		        	),
		            'plotOptions' => array(
		                'column' => array(
		                	'groupPadding' => 0.05,
		                    'stacking' => 'normal',
		                    'dataLabels' => array(
		                        'enabled' => false,
		                        //'color' => (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white',
		                        //'style' => array(
		                        //    'textShadow' => '0 0 3px black, 0 0 3px black'
		                        //)
		                    )
		                )
		            ),
				),
			)
		);
		?>

</div><div class="row" id="top">
	<div class="span4">
		<?php
		$this->widget('bootstrap.widgets.TbGridView', array(
			'id'=>'campaigns-grid',
			'type'=>'striped condensed',
			'dataProvider'=>$dataTops['dataProvider'],
			'columns'=>array(
				array(
					'name'   => 'id',
		        	'value'  => '$data->campaigns->id',
		        ),
				array(
					'name'   => 'name',
		        	'value'  => '$data->campaigns->name',
		        ),
				array(
					'name'   => 'Profits',
		        	'value'  => '$data->getSpendUSD()',
		        ),
		    ),
		));
		?>
	</div>
	<div class="span2">
		<?php
		$this->Widget('ext.highcharts.HighchartsWidget', array(
			'options'=>array(
				'chart' => array('type' => 'column', 'height' => 200),
				'title' => array('text' => ''),
				'tooltip' => array('crosshairs'=>'true', 'shared'=>'true'),
				'xAxis' => array(
					'categories' => $dataTops['array']['campaigns_id'],
					'labels' => array(
	                    'rotation' => -45,
	                    'align' => 'right',
	                    'style' => array(
	                        'fontSize' => '9px',
	                        'fontFamily' => 'Verdana, sans-serif'
	                    	)
	                    )
					),
				//'tooltip' => array('crosshairs'=>'true', 'shared'=>'true'),
				'yAxis' => array(
					'title' => array('text' => '')
					),
				'series' => array(
					array('name' => 'Profit', 'data' => $dataTops['array']['profits']),
					//array('name' => 'Clicks', 'data' => array(205, 189, 215, 133, 192)),
					//array('name' => 'Conversions', 'data' => array(12, 21, 29, 19, 12))
					),
		        'legend' => array(
		            'enabled' => false
		        	),
		            'plotOptions' => array(
		                'column' => array(
		                	'groupPadding' => 0.05,
		                    'stacking' => 'normal',
		                    'dataLabels' => array(
		                        'enabled' => false,
		                        //'color' => (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white',
		                        //'style' => array(
		                        //    'textShadow' => '0 0 3px black, 0 0 3px black'
		                        //)
		                    )
		                )
		            ),
				),
			)
		);
		?>
</div>
<div class="row" id="blank-row">
</div>

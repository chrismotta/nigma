<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
$dateStart="-2 week";
$dateEnd="yesterday";
?>
<div class="row">
	<div class="span12">
	<?php
	$this->Widget('ext.highcharts.HighchartsWidget', array(
		'options'=>array(
			'dataProvider'=>$model->getTotals($dateStart,$dateEnd),
			'chart' => array('type' => 'area'),
			'title' => array('text' => ''),
			'xAxis' => array(
				'categories' => $model->getTotals($dateStart,$dateEnd)['dates']
				),
			'tooltip' => array('crosshairs'=>'true', 'shared'=>'true'),
			'yAxis' => array(
				'title' => array('text' => '')
				),
			'series' => array(
				array('name' => 'Spend', 'data' =>$model->getTotals($dateStart,$dateEnd)['spends']),
				array('name' => 'Revenue', 'data' =>$model->getTotals($dateStart,$dateEnd)['revenues']),
				array('name' => 'Profit', 'data' =>$model->getTotals($dateStart,$dateEnd)['profits']),
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
			'dataProvider'=>$dataTopConversions,
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
					'categories' => $model->getTopConversion($dateStart,$dateEnd,'conversions')['campaigns'],
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
					array('name' => 'Conversions', 'data' => $model->getTopConversion($dateStart,$dateEnd,'conversions')['conversions']),
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
			'dataProvider'=>$dataTopConversionsRate,
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
					'categories' => $model->getTopConversion($dateStart,$dateEnd,'convrate')['campaigns'],
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
					array('name' => 'Conversions Rate', 'data' => $model->getTopConversion($dateStart,$dateEnd,'convrate')['conversions_rate']),
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
<!--
<div class="row" id="top">
	<div class="span6">
		<h4>Top Daily Cap</h4>
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
			'dataProvider'=>$dataProvider,
			'columns'=>array(
				'id',
				'name',
				array(
					'name'   => 'status',
		        	'value'  => '$data->status == 0 ? "Active" : "Paused"',
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
					'categories' => array('#123','#123','#123','#123','#123'),
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
					array('name' => 'Impressions', 'data' => array(1022, 934, 687, 445, 356)),
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
			'dataProvider'=>$dataProvider,
			'columns'=>array(
				'id',
				'name',
				array(
					'name'   => 'status',
		        	'value'  => '$data->status == 0 ? "Active" : "Paused"',
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
					'categories' => array('#123','#123','#123','#123','#123'),
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
					array('name' => 'Impressions', 'data' => array(1022, 934, 687, 445, 356)),
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
-->
</div>

<div class="row" id="blank-row">
</div>

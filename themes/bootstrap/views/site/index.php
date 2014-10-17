<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
?>

<div class="row">
	<div class="span12">
		<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id'=>'date-filter-form',
        'type'=>'search',
        'htmlOptions'=>array('class'=>'well'),
        // to enable ajax validation
        'enableAjaxValidation'=>true,
        'action' => Yii::app()->getBaseUrl(),
        'method' => 'GET',
        'clientOptions'=>array('validateOnSubmit'=>true, 'validateOnChange'=>true),
    )); ?> 

	<fieldset>
	From: 
	<div class="input-append">
		<?php 
		    $this->widget('bootstrap.widgets.TbDatePicker',array(
			'name'  => 'dateStart',
			'value' => date('d-m-Y', strtotime('-1 week')),
			'htmlOptions' => array(
				'style' => 'width: 80px',
			),
		    'options' => array(
				'autoclose'  => true,
				'todayHighlight' => true,
				'format'     => 'dd-mm-yyyy',
				'viewformat' => 'dd-mm-yyyy',
				'placement'  => 'right',
		    ),
		));
		?>
		<span class="add-on"><i class="icon-calendar"></i></span>
	</div>
	To:
	<div class="input-append">
		<?php 
		    $this->widget('bootstrap.widgets.TbDatePicker',array(
			'name'        => 'dateEnd',
			'value'       => date('d-m-Y', strtotime('today')),
			'htmlOptions' => array(
				'style' => 'width: 80px',
			),
			'options'     => array(
				'autoclose'      => true,
				'todayHighlight' => true,
				'format'         => 'dd-mm-yyyy',
				'viewformat'     => 'dd-mm-yyyy',
				'placement'      => 'right',
		    ),
		));
		?>
		<span class="add-on"><i class="icon-calendar"></i></span>
	</div>
	<?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'label'=>'Filter')); ?>

    </fieldset>

<?php $this->endWidget(); ?>
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
				array('name' => 'Revenue', 'data' =>$dataHighchart['revenues']),
				array('name' => 'Spend', 'data' =>$dataHighchart['spends']),
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
	<div class="span6">
		<div class="span4">
			<?php
			$this->widget('bootstrap.widgets.TbGridView', array(
				'id'=>'topconversions-grid',
				'type'=>'striped condensed',
				'dataProvider'=>$dataTopConversions['dataProvider'],
				'template'                 =>'{items}',
				'columns'=>array(
					array(
						'name'   => 'name',
			        	'value'  => 'Campaigns::model()->getExternalName($data->campaigns->id)',        	
			        ),
					array(
						'name'   => 'Conv',
			        	'value'  => '$data->conversions',
						'htmlOptions' => array('style' => 'width: 50px'),
			        ),
				),
			));
			?>
		</div>
		<div class="span2 top-chart">
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
	</div>
	<div class="span6">
		<div class="span4" id="top">
			<?php
			$this->widget('bootstrap.widgets.TbGridView', array(
				'id'=>'conversionsrate-grid',
				'type'=>'striped condensed',
				'dataProvider'=>$dataTopConversionsRate['dataProvider'],
				'template'                 =>'{items}', 
				'columns'=>array(
					array(
						'name'   => 'name',
			        	'value'  => 'Campaigns::model()->getExternalName($data->campaigns->id)',        	
			        ),
					array(
						'name'   => 'CR',
			        	'value'  => '$data->convrate."%"',
						'htmlOptions' => array('style' => 'width: 50px'),
			        ),
				),
			));
			?>
		</div>
		<div class="span2 top-chart">
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
	<div class="span6">
		<div class="span4">
			<?php
			$this->widget('bootstrap.widgets.TbGridView', array(
				'id'=>'topspend-grid',
				'type'=>'striped condensed',
				'dataProvider'=>$dataTops['dataProvider'],
				'template'                 =>'{items}',
				'columns'=>array(
					array(
						'name'   => 'name',
			        	'value'  => 'Campaigns::model()->getExternalName($data->campaigns->id)',        	
			        ),
					array(
						'name'   => 'Spends',
			        	'value'  => '$data->getSpendUSD()',
						'htmlOptions' => array('style' => 'width: 50px'),
			        ),
			    ),
			));
			?>
		</div>
		<div class="span2 top-chart">
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

		</div>
	</div>
	<div class="span6">
		<div class="span4">
			<?php
			$this->widget('bootstrap.widgets.TbGridView', array(
				'id'=>'topprofit-grid',
				'type'=>'striped condensed',
				'dataProvider'=>$dataTopProfit['dataProvider'],
				'template'                 =>'{items}',
				'columns'=>array(
					array(
						'name'   => 'name',
			        	'value'  => 'Campaigns::model()->getExternalName($data->campaigns->id)',        	
			        ),
					array(
						'name'   => 'Prof',
			        	'value'  => '$data->getProfit()',
						'htmlOptions' => array('style' => 'width: 50px'),
			        ),
			    ),
			));
			?>
		</div>
		<div class="span2 top-chart">
			<?php
			$this->Widget('ext.highcharts.HighchartsWidget', array(
				'options'=>array(
					'chart' => array('type' => 'column', 'height' => 200),
					'title' => array('text' => ''),
					'tooltip' => array('crosshairs'=>'true', 'shared'=>'true'),
					'xAxis' => array(
						'categories' => $dataTopProfit['array']['campaigns_id'],
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
						array('name' => 'Profit', 'data' => $dataTopProfit['array']['profits']),
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
	</div>
</div>
<div class="row" id="blank-row">
</div>

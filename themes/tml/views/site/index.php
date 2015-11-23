<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
?>

<div class="row row-bottom">
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
	    <?php
		$dpp = isset($_GET['dpp']) ? $_GET['dpp'] : '5' ;
		$dateStart      = isset($_GET['dateStart']) ? $_GET['dateStart'] : 'yesterday -7 days' ;
		$dateEnd        = isset($_GET['dateEnd']) ? $_GET['dateEnd'] : 'yesterday';
		?>
		<fieldset>
			<?php echo KHtml::datePickerPresets($dpp); ?>
			<!-- <p>From:</p>  -->
			<?php echo KHtml::datePicker('dateStart', $dateStart, array(), array('style'=>'width:73px'), 'From'); ?>
			<!-- <p>To:</p>  -->
			<?php echo KHtml::datePicker('dateEnd', $dateEnd, array(), array('style'=>'width:73px'), 'To'); ?>
	
			<?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'label'=>'Filter', 'htmlOptions' => array('class' => 'showLoading'))); ?>

	    </fieldset>

		<?php $this->endWidget(); ?>
	</div>
</div>
<div class="row row-bottom">
	<div class="span12">
		<?php $this->Widget('ext.highcharts.HighchartsWidget', array(
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
		); ?>
	
	</div>
</div>

<?php
/**
 * Print Top Charts for Dashboard
 * @param  array $model [dataProvider from controller]
 * @param  array $options [keys: tittle, data, gridviewFieldName, gridviewFieldValue, chartFieldName, chartFieldValue]
 * @return void
 */
function printTopChart($model, array $options){

	echo '
	<div class="span6 top-chart">
		<div class="well">
			<div class="row-fluid">
				<div class="span12">
					<h4>'.$options['tittle'].'</h4>
				</div>
			</div>
			<div class="row-fluid">
				<div class="span8">
	';

	$model->widget('bootstrap.widgets.TbGridView', array(
			'id'           => 'topconversions-grid',
			'type'         => 'striped condensed',
			'dataProvider' => $options['data']['dataProvider'],
			'template'     => '{items}',
			'columns'      => array(
				array(
					'name'   => 'Campaign Name',
		        	'value'  => 'Campaigns::model()->getExternalName($data->campaigns->id)',
		        	'htmlOptions' => array('class'=>'top-gridview'),
		        ),
				array(
					'name'   => $options['gridviewFieldName'],
		        	'value'  => $options['gridviewFieldValue'],
					'htmlOptions' => array('style' => 'width: 50px'),
		        ),
			),
		));

	echo '
				</div>
				<div class="span4">
	';

	$model->Widget('ext.highcharts.HighchartsWidget', array(
		'options'=>array(
			'chart' => array('type' => 'column', 'height' => 220, 'backgroundColor' => ''),
			'title' => array('text' => ''),
			'tooltip' => array('crosshairs'=>'true', 'shared'=>'true'),
			'xAxis' => array(
				'categories' => $options['data']['array']['ids'],
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
				array('name' => $options['chartFieldName'], 'data' => $options['data']['array'][$options['chartFieldValue']]),
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

	echo '
				</div>
			</div>
		</div>
	</div>
	';
}
?>

<div class="row row-bottom">
	<?php /* printTopChart($this, array(
		'tittle'             => 'Top Conversions',
		'data'               => $dataTopConversions,
		'gridviewFieldName'  => 'Conv',
		'gridviewFieldValue' => '$data->total',
		'chartFieldName'     => 'Conversions',
		'chartFieldValue'    => 'totals',
		)); */ ?>
	<?php /* printTopChart($this, array(
		'tittle'             => 'Top Conversion Rate',
		'data'               => $dataTopConversionsRate,
		'gridviewFieldName'  => 'CR',
		'gridviewFieldValue' => '$data->total."%"',
		'chartFieldName'     => 'Conversions Rate',
		'chartFieldValue'    => 'totals',
		)); */ ?>
	<?php printTopChart($this, array(
		'tittle'             => 'Top Spend',
		'data'               => $dataTops,
		'gridviewFieldName'  => 'Spends',
		'gridviewFieldValue' => 'number_format($data->total, 2)',
		'chartFieldName'     => 'Spends',
		'chartFieldValue'    => 'totals',
		)); ?>
	<?php printTopChart($this, array(
		'tittle'             => 'Top Profit',
		'data'               => $dataTopProfit,
		'gridviewFieldName'  => 'Prof',
		'gridviewFieldValue' => 'number_format($data->total, 2)',
		'chartFieldName'     => 'Profit',
		'chartFieldValue'    => 'totals',
		)); ?>
</div>

<div class="row" id="blank-row">
</div>

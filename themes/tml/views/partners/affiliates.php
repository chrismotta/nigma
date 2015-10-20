<?php
/* @var $this AffiliatesController */

$this->breadcrumbs=array(
	'Affiliates',
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

$alert = array('error', 'info', 'success', 'warning', 'muted');
?>
<div class="row totals-bar ">
	<div class="span6">
		<div class="alert alert-error">
			<small >TOTAL</small>
			<h3 class="">Conversions: <?php echo number_format(array_sum($data['graphic']['convs'])) ?></h3>
			<br>
		</div>
	</div>
	<div class="span6">
		<div class="alert alert-info">
			<small >TOTAL</small>
			<h3 class="">Revenue: <?php echo Providers::model()->findByPk($provider)->currency . " " . number_format(array_sum($data['graphic']['spends']), 2); ?></h3>
			<br>
		</div>
	</div>
</div>
<div class="row">
	<div id="container-highchart" class="span12">
	<?php

	$this->Widget('ext.highcharts.HighchartsWidget', array(
		'options'=>array(
			'chart' => array('type' => 'area'),
			'title' => array('text' => ''),
			'xAxis' => array(
				'categories' => $data['graphic']['dates']
				),
			'tooltip' => array('crosshairs'=>'true', 'shared'=>'true'),
			'yAxis' => array(
				'title' => array('text' => '')
				),
			'series' => array(
				array('name' => 'Clicks', 'data' => $data['graphic']['clics'],),
				array('name' => 'Conversions', 'data' => $data['graphic']['convs'],),
				array('name' => 'Revenues', 'data' => $data['graphic']['spends'],),
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

<hr>

<div class="botonera">
	<?php 
	//Create link to load filters in modal
	$link='excelReport?dateStart='.$dateStart.'&dateEnd='.$dateEnd.'&sum='.$sum;
	?>
</div>

<br>
<?php 
	if($preview)
		$filterAction = Yii::app()->controller->createUrl(Yii::app()->controller->id . '/' .Yii::app()->controller->action->id . '/' . $userId);
	else
		$filterAction = Yii::app()->controller->createUrl(Yii::app()->controller->id . '/' .Yii::app()->controller->action->id);
	
	$form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
		'id'                   =>'date-filter-form',
		'type'                 =>'search',
		'htmlOptions'          =>array('class'=>'well'),
		'enableAjaxValidation' =>true,
		'action'               => $filterAction,
		// 'action'               => Yii::app()->getBaseUrl() . '/partners/affiliates',
		'method'               => 'GET',
		'clientOptions'        =>array('validateOnSubmit'=>true, 'validateOnChange'=>true),
    )); ?> 

<fieldset>
	From: <?php echo KHtml::datePicker('dateStart', $dateStart); ?>
	To: <?php echo KHtml::datePicker('dateEnd', $dateEnd); ?>
		
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'label'=>'Filter', 'htmlOptions' => array('class' => 'showLoading'))); ?>

</fieldset>
<?php $this->endWidget(); ?>

<?php 
	$this->widget('bootstrap.widgets.TbGroupGridView', array(
	'id'                  => 'daily-report-grid',
	'dataProvider'        => $data['dataProvider'],
	'filter'              => null,//$model,
	'type'                => 'striped condensed',
	'template'            => '{items} {pager} {summary}',
	'extraRowColumns'     => array('firstLetter'),
	'extraRowExpression'  => '"<b style=\"font-size: 2em; color: #333;\">".$data["date"]."</b>"',
	'extraRowHtmlOptions' => array('style'=>'padding:10px'),
	'columns'      =>array(
                array(
					'name'        =>'date', 
					'header'      =>'Date',
					'htmlOptions' => array('style' => 'width: 120px;') ,
                ),
                array(
					'name'        =>'name', 
					'header'      =>'Name', 
					'htmlOptions' => array('style' => 'width: 300px;') ,
                ),
                array(
                	'name'=>'country_name',
					'value'       => '$data["country"]', 
					'header'      => 'Country', 
					'htmlOptions' => array('style' => 'width: 120px;') ,
                ),
                array(
                	'name'=>'carrier_name',
					'value'       => '$data["carrier"]', 
					'header'      => 'Carrier', 
					'htmlOptions' => array('style' => 'width: 120px;') ,
                ),
                array(
					'name'        =>'rate', 
					'header'      =>'Rate',
					'htmlOptions' =>array('style'=>'text-align: right')
                ),
                array(
					'name'        =>'clics', 
					'header'      =>'Clicks',
					'value'       =>'number_format($data["clics"])',
					'htmlOptions' =>array('style'=>'text-align: right')
                ),
                array(
					'name'        =>'conv', 
					'header'      =>'Conv',
					'value'       =>'number_format($data["conv"])',
					'htmlOptions' =>array('style'=>'text-align: right')
                ),
                array(
					'name'        =>'convrate', 
					'header'      =>'Conv. Rate',
					'value'       =>'number_format($data["convrate"] * 100, 2) . " %"',
					'htmlOptions' =>array('style'=>'text-align: right')
                ),
                array(
					'name'        =>'spend', 
					'header'      =>'Revenue',
					'value'       =>'number_format($data["spend"], 2)',
					'htmlOptions' =>array('style'=>'text-align:right')
                ),
                array(
					'name'              =>'firstLetter',
					'value'             =>'$data["date"]',
					'headerHtmlOptions' =>array('style'=>'display:none'),
					'htmlOptions'       =>array('style'=>'display:none')
					),
            ),
	)
); ?>

<?php $this->beginWidget('bootstrap.widgets.TbModal', array('id'=>'modalAffiliates')); ?>

		<div class="modal-header"></div>
        <div class="modal-body"></div>
        <div class="modal-footer"></div>

<?php $this->endWidget(); ?>

<div class="row" id="blank-row">
</div>
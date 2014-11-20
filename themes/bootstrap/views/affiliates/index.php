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
?>

<?php
	$dateStart      = isset($_GET['dateStart']) ? $_GET['dateStart'] : 'yesterday' ;
	$dateEnd        = isset($_GET['dateEnd']) ? $_GET['dateEnd'] : 'yesterday';
	$accountManager = isset($_GET['accountManager']) ? $_GET['accountManager'] : NULL;
	$opportunities  = isset($_GET['opportunities']) ? $_GET['opportunities'] : NULL;
	$networks       = isset($_GET['networks']) ? $_GET['networks'] : NULL;
	$adv_categories = isset($_GET['advertisers-cat']) ? $_GET['advertisers-cat'] : NULL;
	$sum            = isset($_GET['sum']) ? $_GET['sum'] : 0;

	$dateStart  = date('Y-m-d', strtotime($dateStart));
	$dateEnd    = date('Y-m-d', strtotime($dateEnd));
	$totalsGrap =$model->getTotals($dateStart,$dateEnd,$accountManager,$opportunities,$networks, $adv_categories);
?>
<div class="row">
	<div id="container-highchart" class="span12">
	<?php

	$this->Widget('ext.highcharts.HighchartsWidget', array(
		'options'=>array(
			'chart' => array('type' => 'area'),
			'title' => array('text' => ''),
			'xAxis' => array(
				'categories' => $totalsGrap['dates']
				),
			'tooltip' => array('crosshairs'=>'true', 'shared'=>'true'),
			'yAxis' => array(
				'title' => array('text' => '')
				),
			'series' => array(
				array('name' => 'Impressions', 'data' => $totalsGrap['impressions'],),
				array('name' => 'Clicks', 'data' => $totalsGrap['clics'],),
				array('name' => 'Conv','data' => $totalsGrap['conversions'],),
				array('name' => 'Revenue','data' => $totalsGrap['revenues'],),
				array('name' => 'Spend','data' => $totalsGrap['spends'],),
				array('name' => 'Profit','data' => $totalsGrap['profits'],),
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
	if(isset($accountManager))
	{
		if(is_array($accountManager))
		{
			foreach ($accountManager as $id) {
				$link.='&accountManager[]='.$id;
			}			
		}
		else
		{
			$link.='&accountManager='.$accountManager;
		}
	}
	if(isset($opportunities))
	{
		if(is_array($opportunities))
		{
			foreach ($opportunities as $id) {
				$link.='&opportunities[]='.$id;
			}			
		}
		else
		{
			$link.='&opportunities='.$opportunities;
		}
	}	
	if(isset($networks))
	{
		if(is_array($networks))
		{
			foreach ($networks as $id) {
				$link.='&networks[]='.$id;
			}			
		}
		else
		{
			$link.='&networks='.$networks;
		}
	}
	if(isset($adv_categories))
	{
		if(is_array($adv_categories))
		{
			foreach ($adv_categories as $id) {
				$link.='&advertisers-cat[]='.$id;
			}			
		}
		else
		{
			$link.='&advertisers-cat='.$adv_categories;
		}
	}
	$this->widget('bootstrap.widgets.TbButton', array(
		'type'        => 'info',
		'label'       => 'Excel Report',
		'block'       => false,
		'buttonType'  => 'ajaxButton',
		'url'         => $link,
		'ajaxOptions' => array(
			'type'    => 'POST',
			'beforeSend' => 'function(data)
				{
			    	var dataInicial = "<div class=\"modal-header\"></div><div class=\"modal-body\" style=\"padding:100px 0px;text-align:center;\"><img src=\"'.  Yii::app()->theme->baseUrl .'/img/loading.gif\" width=\"40\" /></div><div class=\"modal-footer\"></div>";
					$("#modalDailyReport").html(dataInicial);
					$("#modalDailyReport").modal("toggle");
				}',
			'success' => 'function(data)
				{
					$("#modalDailyReport").html(data);
				}',
			),
		'htmlOptions' => array('id' => 'excelReport'),
		)
	); ?>
</div>

<br>
<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
		'id'                   =>'date-filter-form',
		'type'                 =>'search',
		'htmlOptions'          =>array('class'=>'well'),
		'enableAjaxValidation' =>true,
		'action'               => Yii::app()->getBaseUrl() . '/dailyReport/admin',
		'method'               => 'GET',
		'clientOptions'        =>array('validateOnSubmit'=>true, 'validateOnChange'=>true),
    )); ?> 

<fieldset>
	From: <?php echo KHtml::datePicker('dateStart', $dateStart); ?>
	To: <?php echo KHtml::datePicker('dateEnd', $dateEnd); ?>
	SUM 
	<div class="input-append">
	<?php echo CHtml::checkBox('sum', $sum, array('style'=>'vertical-align: baseline;')); ?>
	</div>
		
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'label'=>'Filter', 'htmlOptions' => array('class' => 'showLoading'))); ?>

</fieldset>
<?php $this->endWidget(); ?>

<?php 
	$totals=$model->getDailyTotals($dateStart, $dateEnd, $accountManager,$opportunities,$networks, $adv_categories);
	$this->widget('bootstrap.widgets.TbExtendedGridView', array(
	'id'                       => 'daily-report-grid',
	'fixedHeader'              => true,
	'headerOffset'             => 50,
	'dataProvider'             => $model->search($dateStart, $dateEnd, $accountManager, $opportunities, $networks, $sum, $adv_categories),
	'filter'                   => $model,
	'selectionChanged'         => 'js:selectionChangedDailyReport',
	'type'                     => 'striped condensed',
	'rowHtmlOptionsExpression' => 'array("data-row-id" => $data->id, "data-row-net-id" => $data->networks_id, "data-row-c-id" => $data->campaigns_id)',
	'template'                 => '{items} {pager} {summary}',
	'rowCssClassExpression'    => '$data->getCapStatus() ? "errorCap" : null',
	'columns'                  => array(
		array(
			'name'               =>	'id',
			'footer'             => 'Totals:',
			'cssClassExpression' => '$data->isFromVector() ? "isFromVector" : NULL',
			'htmlOptions'        => array('style' => 'padding-left: 10px; height: 70px;'),
			'headerHtmlOptions'  => array('style' => 'border-left: medium solid #FFF;'),
		),
		array(
			'name'        => 'campaign_name',
			'value'       => 'Campaigns::model()->getExternalName($data->campaigns_id)',
			'headerHtmlOptions' => array('width' => '200'),
			'htmlOptions' => array('style'=>'word-wrap:break-word;'),
		),
		array(
			'name'        => 'rate',
			'value'       => '$data->getRateUSD() ? $data->getRateUSD() : 0',
			'htmlOptions' => array('style'=>'text-align:right;'),
		),
        array(
			'name'              => 'conv_api',
			'htmlOptions'       => array('style'=>'text-align:right;'),
			'footerHtmlOptions' => array('style'=>'text-align:right;'),
			'footer'            => $totals['conv_s2s'],
        ),
		array(
			'name'              => 'conv_adv',
			'filterHtmlOptions' => array('colspan'=>'2'),
			'htmlOptions'       => array('style'=>'text-align:right;'),
			'footerHtmlOptions' => array('style'=>'text-align:right;'),
			'class'             => 'bootstrap.widgets.TbEditableColumn',
			'cssClassExpression'=> '$data->campaigns->opportunities->rate === NULL 
									&& $data->campaigns->opportunities->carriers_id === NULL ?
									"notMultiCarrier" :
									"multiCarrier"',
			'editable'          => array(
				'apply'      => $sum ? false : true,
				'title'      => 'Conversions',
				'type'       => 'text',
				'url'        => 'updateEditable/',
				'emptytext'  => 'Null',
				'inputclass' => 'input-mini',
				'success'    => 'js: function(response, newValue) {
					  	if (!response.success) {
							$.fn.yiiGridView.update("daily-report-grid");
					  	}
					}',
            ),
			'footer' => $totals['conv_adv'],
		),
   //      array(
			// 'name'              => 'revenue',
			// 'value'             => '$data->getRevenueUSD()',
			// 'htmlOptions'       => array('style'=>'text-align:right;'),
			// 'footerHtmlOptions' => array('style'=>'text-align:right;'),
			// 'footer'            => $totals['revenue'],
   //      ),
		array(
			'name'              => 'spend',
			'value'             => '$data->getSpendUSD()',
			'header'			=> 'Revenue',
			'htmlOptions'       => array('style'=>'text-align:right;'),
			'footerHtmlOptions' => array('style'=>'text-align:right;'),
			'footer'            => $totals['spend'],
        ),
		array(
			'name'              => 'date',
			'value'             => 'date("d-m-Y", strtotime($data->date))',
			'headerHtmlOptions' => array('style' => "width: 30px"),
			'htmlOptions'       => array(
					'class' => 'date', 
					'style' =>'text-align:right;'
				),
			'filter'      => false,
        ),
	),
)); ?>

<?php $this->beginWidget('bootstrap.widgets.TbModal', array('id'=>'modalDailyReport')); ?>

		<div class="modal-header"></div>
        <div class="modal-body"></div>
        <div class="modal-footer"></div>

<?php $this->endWidget(); ?>

<div class="row" id="blank-row">
</div>
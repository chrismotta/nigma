<?php

$dpp = isset($_GET['dpp']) ? $_GET['dpp'] : '1' ;

$dateStart      = isset($_GET['dateStart']) ? $_GET['dateStart'] : 'today' ;
$dateEnd        = isset($_GET['dateEnd']) ? $_GET['dateEnd'] : 'today';

$dateStart      = date('Y-m-d', strtotime($dateStart));
$dateEnd        = date('Y-m-d', strtotime($dateEnd));

$accountManager = isset($_GET['accountManager']) ? $_GET['accountManager'] : NULL;
$opportunitie   = isset($_GET['opportunitie']) ? $_GET['opportunitie'] : NULL;
$providers       = isset($_GET['providers']) ? $_GET['providers'] : NULL;
$totalsGrap     = DailyTotals::model()->getTotals($dateStart,$dateEnd);

// El caso esta comentado porque daba valores 0 - Revisar más adelante
// if($accountManager==null && $opportunitie==null && $providers==null)
// 	$totals=DailyTotals::model()->getTotals($dateStart,$dateEnd);
// else 
	$totals=Campaigns::getTotals($dateStart, $dateEnd,null,$accountManager,$opportunitie,$providers);

// print_r($totals);
// return;
/* @var $this CampaignsController */
/* @var $model Campaigns */

//Agrega los links de navegación
$this->breadcrumbs=array(
	'Campaigns'=>array('index'),
	'View Traffic',
);
//Yii::import('application.components.HighchartsSnippet', true);
Yii::app()->clientScript->registerScript('search', "
	$('.search-button').click(function(){
		$('.search-form').toggle();
		return false;
	});
	$('.search-form form').submit(function(){
		$('#campaigns-grid').yiiGridView('update', {
			data: $(this).serialize()
		});
		return false;
	});
	");
?>
<div class="row">
	<div id="container-highchart" class="span12">
	<?php
	$this->Widget('ext.highcharts.HighchartsWidget', array(
		'id' => 'hig1',
		'options'=>array(
			'chart' => array('type' => 'area'),
			'title' => array('text' => ''),
			'xAxis' => array(
				'categories' => $totalsGrap['dates']
				),
			'tooltip' => array('crosshairs'=>'true', 'shared'=>'true'),
			'yAxis'   => array(
				'title' => array('text' => '')
				),
			'series' => array(
				array('name' => 'Clicks', 'data' => $totalsGrap['clics_redirect'],),
				array('name' => 'Conversions', 'data' => $totalsGrap['conversions_s2s'],),
				),
	        'legend' => array(
				'layout'          => 'vertical',
				'align'           =>  'left',
				'verticalAlign'   =>  'top',
				'x'               =>  40,
				'y'               =>  3,
				'floating'        =>  true,
				'borderWidth'     =>  1,
				'backgroundColor' => '#FFFFFF'
	        	)
			),
		)
	);
	?>
			
	</div>
</div>
<div class="botonera">
	<?php $this->widget('bootstrap.widgets.TbButton', array(
		'type'        => 'info',
		'label'       => 'Excel Conversions Report',
		'block'       => false,
		'buttonType'  => 'ajaxButton',
		'url'         => 'excelReport',
		'ajaxOptions' => array(
			'type'    => 'POST',
			'beforeSend' => 'function(data)
				{
			    	var dataInicial = "<div class=\"modal-header\"></div><div class=\"modal-body\" style=\"padding:100px 0px;text-align:center;\"><img src=\"'.  Yii::app()->theme->baseUrl .'/img/loading.gif\" width=\"40\" /></div><div class=\"modal-footer\"></div>";
					$("#modalExcel").html(dataInicial);
					$("#modalExcel").modal("toggle");
				}',
			'success' => 'function(data)
				{
					$("#modalExcel").html(data);
				}',
			),
		'htmlOptions' => array('id' => 'excelReport'),
		)
	); ?>
</div>
<br>
<!--### Date Picker ###-->
<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id'=>'date-filter-form',
        'type'=>'search',
        'htmlOptions'=>array('class'=>'well'),
        // to enable ajax validation
        'enableAjaxValidation'=>true,
        'action' => Yii::app()->getBaseUrl() . '/campaigns/traffic',
        'method' => 'GET',
        'clientOptions'=>array('validateOnSubmit'=>true, 'validateOnChange'=>true),
    )); ?> 
<fieldset>

	<?php echo KHtml::datePickerPresets($dpp); ?>
	<!-- <p>From:</p>  -->
	<?php echo KHtml::datePicker('dateStart', $dateStart, array(), array('style'=>'width:73px'), 'From'); ?>
	<!-- <p>To:</p>  -->
	<?php echo KHtml::datePicker('dateEnd', $dateEnd, array(), array('style'=>'width:73px'), 'To'); ?>

	<?php 
		if (FilterManager::model()->isUserTotalAccess('daily'))
			echo KHtml::filterAccountManagers($accountManager, array('class'=>'span2'));
		
		echo KHtml::filterOpportunities($opportunitie, $accountManager, array('class'=>'span2'));
		echo KHtml::filterProviders($providers, null, array('class'=>'span2'));
	?>

    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'label'=>'Filter', 'htmlOptions' => array('class' => 'showLoading'))); ?>
</fieldset>

<?php $this->endWidget(); ?>
<!--### Traffic grid###-->
<?php $this->widget('bootstrap.widgets.TbExtendedGridView', array(
	'id'                       => 'traffic-grid',
	'dataProvider'             => $model->searchTraffic($accountManager,$opportunitie,$providers, $dateStart, $dateEnd),
	'filter'                   => $model,
    'fixedHeader'			   => true,
    'headerOffset'			   => 50,
	'type'                     => 'striped condensed',
	'selectionChanged'         => 'js:selectionChangedTraffic',
	'rowHtmlOptionsExpression' => 'array("data-row-id" => $data->id)',
	'template'                 =>'{items} {pager} {summary}',
	
	'columns'                  =>array(
		// para incluir columnas de tablas relacionadas con search y order
		// se usa la propiedad publica custom en 'name'
		// y la ruta relacional de la columna en 'value'
		array(
			'name'              => 'advertisers_name',
			'value'             => '$data->opportunities->regions->financeEntities->advertisers->name',
			'headerHtmlOptions' => array('style' => 'width: 80px'),
			'footer'			=> 'Totals:'
        ),
		array(
			'name'              => 'financeEntities_name',
			'value'             => '$data->opportunities->regions->financeEntities->name',
			'headerHtmlOptions' => array('style' => 'width: 60px'),
        ),
		array(
			'name'              => 'name',
			'value'             => 'Campaigns::model()->getExternalName($data->id)',
			'headerHtmlOptions' => array('style' => 'width: 80px'),
        ),
		array(
			'name'              => 'model',
			'headerHtmlOptions' => array('style' => 'width: 30px'),
        ),
        array(
			'name'              => 'clicks',
			'value'             => '$data->clicks',
			'headerHtmlOptions' => array('style' => 'width: 45px; text-align:right;'),
			'htmlOptions'		=> array('style'=>'width: 45px; text-align:right;'),
			'filter'			=> '',
			'footerHtmlOptions'	=> array('style'=>'text-align:right;'),
			'footer'			=> array_sum($totals["clics_redirect"]),
        ),
        array(
			'name'              => 'conv',
			'value'             => '$data->conv',
			'headerHtmlOptions' => array('style' => 'width: 45px; text-align:right;'),
			'htmlOptions'       => array('style'=>'width: 45px; text-align:right;'),
			'footerHtmlOptions' => array('style'=>'text-align:right;'),
			'filter'            => '',
			'footer'			=> array_sum($totals["conversions_s2s"]),
        ),
        array(
			'name'              => 'rate',
			'value'             => '$data->getRateUSD("'.$dateEnd.'")',
			'htmlOptions'       => array('style'=>'width: 45px; text-align:right;'),
			'filter'            => '',
			'headerHtmlOptions' => array('style' => 'width: 45px; text-align:right;'),
        ),
        array(
			'name'              => 'revenue',
			'value'             => '($data->conv * $data->getRateUSD("'.$dateEnd.'"))',
			'headerHtmlOptions' => array('style' => 'width: 45px; text-align:right;'),
			'filter'            => '',
			'htmlOptions'       => array('style'=>'width: 45px; text-align:right;'),
        ),
        array(
			'name'              => 'spend',
			'type'				=>	'raw',
			'filter'			=> '',
			'value'             => 'CHtml::textField("row-spend" . $row, 0, array(
			        				"style" => "width:30px; text-align:right; font-size: 11px;", 
			        				"onChange" => "
			        					var revenue= $( \"#row-spend$row\" ).parent().parent().children().eq(6);
			        					var profit= $( \"#row-spend$row\" ).parent().parent().children().eq(8);
			        					var profit_percent= $( \"#row-spend$row\" ).parent().parent().children().eq(9);
			        					var spend=$( \"#row-spend$row\" ).val();
										profit.html((revenue.html()-spend).toFixed(2));
										if(revenue.html()!=0){
											profit_percent.html((profit.html()/revenue.html()*100).toFixed(2)+\"%\");
										}
			        				" 
			        				))',
			'headerHtmlOptions' => array('style' => 'width: 45px; text-align:right;'),
			'htmlOptions'       =>array('style'=>'width: 45px; text-align:right;'),
        ),
        array(
			'name'              => 'profit',
			'value'             => '0',
			'filter'            => '',
			'headerHtmlOptions' => array('style' => 'width: 45px; text-align:right;'),
			'htmlOptions'       =>array('style'=>'width: 45px; text-align:right;'),
        ),
        array(
			'name'              => 'profit_percent',
			'value'             => '0',
			'headerHtmlOptions' => array('style' => 'width: 45px; text-align:right;'),
			'filter'            => '',
			'htmlOptions'       =>array('style'=>'width: 45px; text-align:right;'),
        ),
        array(
			'class'             => 'bootstrap.widgets.TbButtonColumn',
			'headerHtmlOptions' => array('style' => "width: 70px; text-align:right;"),
			'htmlOptions'       =>array('style'=>'width: 45px; text-align:right;'),
			'buttons'           => array(
				'showCampaign' => array(
					'label' => 'Show Campaign',
					'icon'  => 'eye-open',
					'click' => '
				    function() {
				    	// get row id from data-row-id attribute
				    	var id = $(this).parents("tr").attr("data-row-id");
						var dateStart = $("#dateStart").val();
						var dateEnd = $("#dateEnd").val();						
						window.location="graphicCampaign?id="+id+"&dateStart="+dateStart+"&dateEnd="+dateEnd;
						return false;
				    }
				    ',
				),
				'showConversion' => array(
					'label' => 'Show Conversions',
					'icon'  => 'random',
					'click' => '
				    function() {
				    	// get row id from data-row-id attribute
				    	var id = $(this).parents("tr").attr("data-row-id");
						var dateStart = $("#dateStart").val();
						var dateEnd = $("#dateEnd").val();
				    	var dataInicial = "<div class=\"modal-header\"></div><div class=\"modal-body\" style=\"padding:100px 0px;text-align:center;\"><img src=\"'.  Yii::app()->theme->baseUrl .'/img/loading.gif\" width=\"40\" /></div><div class=\"modal-footer\"></div>";
						$("#modalTraffic").html(dataInicial);
						$("#modalTraffic").modal("toggle");
						
						$.post("trafficCampaignAjax?id="+id+"&dateStart="+dateStart+"&dateEnd="+dateEnd)
						 .done(function(data){
						 	$("#modalTraffic").html(data);
						});
						return false;
				    }
				    ',
				),
				'excelConversion' => array(
					'label' => 'Download Conversions',
					'icon'  => 'download',
					'click' => '
				    function() {
				    	// get row id from data-row-id attribute
				    	var id = $(this).parents("tr").attr("data-row-id");
						var dateStart = $("#dateStart").val();
						var dateEnd = $("#dateEnd").val();
				    	var dataInicial = "<div class=\"modal-header\"></div><div class=\"modal-body\" style=\"padding:100px 0px;text-align:center;\"><img src=\"'.  Yii::app()->theme->baseUrl .'/img/loading.gif\" width=\"40\" /></div><div class=\"modal-footer\"></div>";
						$("#modalExcel").html(dataInicial);
						$("#modalExcel").modal("toggle");
						
						$.post("excelReport?id="+id+"&dateStart="+dateStart+"&dateEnd="+dateEnd)
						 .done(function(data){
						 	$("#modalExcel").html(data);
						});
						return false;
				    }
				    ',
				),
			),
			'template' => '{showCampaign} {showConversion} {excelConversion}',
		),
		
	),
)); 
?>

<?php BuildGridView::printModal($this, 'modalTraffic', 'Traffic Report', array('style'=>'width: 90%;margin-left:-45%')); ?>
<?php BuildGridView::printModal($this, 'modalExcel', 'Traffic Report'); ?>

<div class="row" id="blank-row">
</div>
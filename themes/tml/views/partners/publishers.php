<?php
/* @var $this DailyReportController */
/* @var $model DailyReport */

$this->breadcrumbs=array(
	'Publishers',
);
/*
$this->menu=array(
	array('label'=>'List DailyReport', 'url'=>array('index')),
	array('label'=>'Create DailyReport', 'url'=>array('create')),
);
*/
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
/*
	// $totalsGrap = $model->getTotals($dateStart,$dateEnd,$accountManager,$opportunities,$providers, $adv_categories);
	$totalsGrap = $model->advertiserGetTotals($advertiser_id, $dateStart, $dateEnd);
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
			'yAxis'   => array(
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

<hr>

<div class="botonera">
	<?php $this->widget('bootstrap.widgets.TbButton', array(
		'type'        => 'info',
		'label'       => 'Add Daily Report Manualy',
		'block'       => false,
		'buttonType'  => 'ajaxButton',
		'url'         => 'create',
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
		'htmlOptions' => array('id' => 'createAjax'),
		)
	); ?>
	<?php 
*/
?>

<?php

	$dateStart      = isset($_GET['dateStart']) ? $_GET['dateStart'] : '-8 days';
	$dateEnd        = isset($_GET['dateEnd']) ? $_GET['dateEnd'] : '-1 days';
	// $accountManager = isset($_GET['accountManager']) ? $_GET['accountManager'] : NULL;
	// $opportunities  = isset($_GET['opportunities']) ? $_GET['opportunities'] : NULL;
	// $providers      = isset($_GET['providers']) ? $_GET['providers'] : NULL;
	// $adv_categories = isset($_GET['advertisers-cat']) ? $_GET['advertisers-cat'] : NULL;
	$sum            = isset($_GET['sum']) ? $_GET['sum'] : 0;

	$dateStart  = date('Y-m-d', strtotime($dateStart));
	$dateEnd    = date('Y-m-d', strtotime($dateEnd));
?>
 
<fieldset class="formfilter well">

<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
		'id'                   =>'date-filter-form',
		'type'                 =>'search',
		'htmlOptions'          =>array('style'=>'display:inline-block;margin:0px'),
		'enableAjaxValidation' =>false,
		'action'               => Yii::app()->request->url,
		'method'               => 'GET',
		// 'clientOptions'        =>array('validateOnSubmit'=>true, 'validateOnChange'=>true),
    )); ?> 
	<div class="formfilter-date-large">
		<!-- From:  -->
		<?php echo KHtml::datePicker('dateStart', $dateStart, array(), array(), 'From'); ?>
		<span class='formfilter-space'></span>		
		<!-- To:  -->
		<?php echo KHtml::datePicker('dateEnd', $dateEnd, array(), array(), 'To'); ?>
    	
    	<?php echo CHtml::hiddenField('sum', $sum, array('id'=>'sum')); ?>

		<span class='formfilter-space'></span>
		<?php $this->widget(
		    'bootstrap.widgets.TbButtonGroup',
		    array(
		        'toggle' => 'radio',
		        // 'type' => 'inverse',
		        'buttons' => array(
		            array('label' => 'Daily Stats', 'active'=>boolval(1-$sum), 'htmlOptions'=>array('onclick'=>'$("#sum").val("0");')),
		            array('label' => 'Merged Stats', 'active'=>boolval(0-$sum), 'htmlOptions'=>array('onclick'=>'$("#sum").val("1");')),
		        ),

		    )
		); ?>
		<span class='formfilter-space'></span>
    	<?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'label'=>'Filter', 'type' => 'success', 'htmlOptions' => array('class' => 'showLoading'))); ?>
	</div>
	<?php 
		//Load Filters
		/*
	
		if (FilterManager::model()->isUserTotalAccess('daily'))
			KHtml::filterAccountManagersMulti($accountManager,array('id' => 'accountManager-select'),'opportunities-select','accountManager','opportunities');
		KHtml::filterOpportunitiesMulti($opportunities, $accountManager, array('style' => "width: 140px; margin-left: 1em",'id' => 'opportunities-select'),'opportunities');
		KHtml::filterProvidersMulti($providers, NULL, array('style' => "width: 140px; margin-left: 1em",'id' => 'providers-select'),'providers');
		KHtml::filterAdvertisersCategoryMulti($adv_categories, array('style' => "width: 140px; margin-left: 1em",'id' => 'advertisers-cat-select'),'advertisers-cat');
	<hr>
	<div class="formfilter-submit">
		SUM
		<div class="input-append">
			<?php echo CHtml::checkBox('sum', $sum, array('style'=>'vertical-align: baseline;')); ?>
		</div>
	</div>
		*/
	?>
<?php $this->endWidget(); ?>

<?php 

//Create link to load filters in modal
$currentAction = $preview ? 'previewExcelReportAdvertisers' : 'excelReportAdvertisers'; 
$link = Yii::app()->createUrl('partners/'.$currentAction, array('id'=>$userId, 'dateStart'=>$dateStart, 'dateEnd'=>$dateEnd, 'sum'=>$sum));
/*
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
	'htmlOptions' => array('id' => 'excelReport', 'style' => 'float:right'),
	)
);
*/
?>

</fieldset>

<?php 
	
	$dataProvider = $model->publisherSearch($publisher_id, $dateStart, $dateEnd, $sum, false);	
	$totals       = $model->publisherSearch($publisher_id, $dateStart, $dateEnd, $sum, true);
	//var_dump($user_visibility->imp);

	$mergeColumns = $sum ? array('date') : array('date', 'placements.sites.name');

	$this->widget('bootstrap.widgets.TbGroupGridView', array(
	'id'                       => 'daily-report-grid',
	// 'fixedHeader'              => true,
	// 'headerOffset'             => 50,
	'dataProvider'             => $dataProvider,
	// 'filter'                   => $model,
	//'selectionChanged'         => 'js:selectionChangedDailyReport',
	'type'                     => 'condensed',
	'rowHtmlOptionsExpression' => 'array("data-row-id" => $data->id)',
	'template'                 => '{items} {pager} {summary}',
	//'rowCssClassExpression'    => '$data->getCapStatus() ? "errorCap" : null',
	// 'mergeColumns' 			   => $mergeColumns,
	'columns'                  => array(
		array(
			'name'              => 'date',
			'value'             => 'date("d-m-Y", strtotime($data->date))',
			'headerHtmlOptions' => array('style' => "width: 80px"),
			'htmlOptions'       => array(
					'id' => 'date', 
					'style' =>'text-align:left !important;'
				),
			'filter'      => false,
        ),
        array(
        	'name'  => 'placements_id',
        	'header' => '',
			'headerHtmlOptions' => array('style' => "width: 200px"),
        	'value' => '""',
			'visible'     => $sum,
        ),
        array(
        	'header' => 'Site',
        	'name' => 'placements.sites.name',
        	'htmlOptions' => array('style' =>'text-align:left !important;'),
			'visible'     => !$sum,
        ),
        array(
        	'header' => 'Placement',
        	'name' => 'placements.name',
			'visible'     => !$sum,
        ),
        array(
        	'header' => 'Size',
        	'name' => 'placements.sizes.size',
			'visible'     => !$sum,
        ),
		array(	
			'name'              => 'ad_request',
			'header'            => 'Ad Requests',
			'value'             => 'number_format($data->ad_request)',
			'headerHtmlOptions' => array('style' => "width: 80px"),
			'htmlOptions'       => array('style'=>'text-align:right;'),
			'footerHtmlOptions' => array('style'=>'text-align:right;font-weight: bold;'),
			'footer'            => isset($totals) ? number_format($totals->ad_request) : '',
			'filter'            => false,
			// 'visible'           => $user_visibility->imp,
        ),
		array(	
			'name'              => 'impressions',
			'header'            => 'Impressions',
			'value'             => 'number_format($data->impressions)',
			'headerHtmlOptions' => array('style' => "width: 80px"),
			'htmlOptions'       => array('style'=>'text-align:right;'),
			'footerHtmlOptions' => array('style'=>'text-align:right;font-weight: bold;'),
			'footer'            => isset($totals) ? number_format($totals->impressions) : '',
			'filter'            => false,
			// 'visible'           => $user_visibility->imp,
        ),
        array(
			'name'              => 'revenue',
			'header'            => 'Revenue',
			'value'             => '"$ ".number_format($data->revenue, 2)',
			'headerHtmlOptions' => array('style' => "width: 80px"),
			'htmlOptions'       => array('style'=>'text-align:right;'),
			'footerHtmlOptions' => array('style'=>'text-align:right;font-weight: bold;'),
			'footer'            => isset($totals) ? '$ '.number_format($totals->revenue, 2) : '',
			'filter'            => false,
			// 'visible'           => $user_visibility->spend,
        ),
        array(
			'name'              => 'eCPM',
			'header'            => 'eCPM',
			'value'             => '$data->impressions > 0 ? "$ ".number_format($data->revenue / $data->impressions * 1000, 2) : 0',
			'headerHtmlOptions' => array('style' => "width: 60px"),
			'htmlOptions'       => array('style'=>'text-align:right;'),
			'footerHtmlOptions' => array('style'=>'text-align:right;font-weight: bold;'),
			'footer'            => isset($totals && $totals->impressions>0) ? '$ '.number_format($totals->revenue / $totals->impressions * 1000, 2) : '',
			'filter'            => false,
			// 'visible'           => $user_visibility->spend,
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
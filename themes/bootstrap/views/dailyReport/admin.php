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

<fieldset class="formfilter">
	<div class="formfilter-date">
		<p>From:</p> 
		<?php echo KHtml::datePicker('dateStart', $dateStart); ?>
		
		<p>To:</p> 
		<?php echo KHtml::datePicker('dateEnd', $dateEnd); ?>
	</div>
	<?php 
		//Load Filters
		if (FilterManager::model()->isUserTotalAccess('daily'))
			KHtml::filterAccountManagersMulti($accountManager,array('id' => 'accountManager-select'),'opportunities-select','accountManager','opportunities');
		KHtml::filterOpportunitiesMulti($opportunities, $accountManager, array('style' => "width: 140px; margin-left: 1em",'id' => 'opportunities-select'),'opportunities');
		KHtml::filterNetworksMulti($networks, NULL, array('style' => "width: 140px; margin-left: 1em",'id' => 'networks-select'),'networks');
		KHtml::filterAdvertisersCategoryMulti($adv_categories, array('style' => "width: 140px; margin-left: 1em",'id' => 'advertisers-cat-select'),'advertisers-cat');
	?>
	<hr>
	<div class="formfilter-submit">
		SUM
		<div class="input-append">
			<?php echo CHtml::checkBox('sum', $sum, array('style'=>'vertical-align: baseline;')); ?>
		</div>
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'label'=>'Filter', 'htmlOptions' => array('class' => 'showLoading'))); ?>
	</div>

</fieldset>
<?php $this->endWidget(); ?>

<?php 
//yess
	$dataProvider=$model->search($dateStart, $dateEnd, $accountManager, $opportunities, $networks, $sum, $adv_categories);
	$totals=$model->searchTotals($dateStart, $dateEnd, $accountManager, $opportunities, $networks, $sum, $adv_categories);

	$this->widget('bootstrap.widgets.TbExtendedGridView', array(
	'id'                       => 'daily-report-grid',
	'fixedHeader'              => true,
	'headerOffset'             => 50,
	'dataProvider'             => $dataProvider,
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
			'name'        => 'comment',
			'filter'      => false,
			'class'       => 'bootstrap.widgets.TbEditableColumn',
			'htmlOptions' => array('class'=>'editableField'),
			'editable'    => array(
				'title'   => 'Comment',
				'type'    => 'textarea',
				'url'     => 'updateEditable/',
				'display' => 'js:function(value, source){
					$(this).html("<i class=\"icon-font\"></i>");
				}'
            ),
            'visible' => $sum ? false : true,
        ),
		array(
			'name'   =>	'network_name',
			'value'  =>	'$data->networks->name',
			'filter' => $networks_names,
		),
		array(
			'name'        => 'rate',
			'value'       => '$data->getRateUSD() ? number_format($data->getRateUSD(),2) : "0.00"',
			'htmlOptions' => array('style'=>'text-align:right;'),
		),
		array(	
			'name'              => 'imp',
			'htmlOptions'       => array('style'=>'text-align:right;'),
			'footerHtmlOptions' => array('style'=>'text-align:right;'),
			'footer'            => $totals['imp'],
        ),
        array(	
			'name'              => 'imp_adv',
			'htmlOptions'       => array('style'=>'text-align:right;'),
			'footerHtmlOptions' => array('style'=>'text-align:right;'),
			'footer'            => $totals['imp_adv'],
			'class'             => 'bootstrap.widgets.TbEditableColumn',
			'editable'          => array(
				'apply'      => $sum ? false : true,
				'title'      => 'Impressions',
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
        ),
        array(
			'name'              => 'clics',
			'htmlOptions'       => array('style'=>'text-align:right;'),
			'footerHtmlOptions' => array('style'=>'text-align:right;'),
			'footer'            => $totals['clics'],
        ),
        array(
			'name'              => 'conv_api',
			'htmlOptions'       => array('style'=>'text-align:right;'),
			'footerHtmlOptions' => array('style'=>'text-align:right;'),
			'footer'            => $totals['conv_api'],
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
		array(
			'name'              => 'mr',
			'filter'			=> null,
			'headerHtmlOptions' => array('class'=>'plusMR'),
			//'filterHtmlOptions' => array('class'=>'plusMR'),
			'htmlOptions'       => array('class'=>'plusMR'),
			'type'              => 'raw',
			'value'             =>	'
				$data->campaigns->opportunities->rate === NULL && $data->campaigns->opportunities->carriers_id === NULL && '.$sum.' == 0 ?
					CHtml::link(
            				"<i class=\"icon-plus\"></i>",
	            			"javascript:;",
	        				array(
	        					"onClick" => CHtml::ajax( array(
									"type"    => "POST",
									"url"     => "multiRate/" . $data->id,
									"success" => "function( data )
										{
											$(\"#modalDailyReport\").html(data);
											$(\"#modalDailyReport\").modal(\"toggle\");
										}",
									)),
								//"style"               => "width: 20px;pointer-events: none;cursor: default;",
								"rel"                 => "tooltip",
								"data-original-title" => "Update"
								)
						) 
				: null
				',
        ),
        array(
			'name'              => 'revenue',
			'value'             => 'number_format($data->getRevenueUSD(), 2)',
			'htmlOptions'       => array('style'=>'text-align:right;'),
			'footerHtmlOptions' => array('style'=>'text-align:right;'),
			'footer'            => $totals['revenue'],
        ),
		array(
			'name'              => 'spend',
			'value'             => 'number_format($data->getSpendUSD(), 2)',
			'htmlOptions'       => array('style'=>'text-align:right;'),
			'footerHtmlOptions' => array('style'=>'text-align:right;'),
			'footer'            => $totals['spend'],
        ),
		array(
			'name'              => 'profit',
			'value'             => 'number_format($data->profit, 2)',
			'htmlOptions'       => array('style'=>'text-align:right;'),
			'footerHtmlOptions' => array('style'=>'text-align:right;'),
			'footer'            => $totals['profit'],
		),
		array(
			'name'              => 'profit_percent',
			'value'             => $sum ? '$data->revenue == 0 ? "0%" : number_format($data->profit / $data->getRevenueUSD() * 100) . "%"' : 'number_format($data->profit_percent*100)."%"', // FIX for sum feature
			'htmlOptions'       => array('style'=>'text-align:right;'),
			'footerHtmlOptions' => array('style'=>'text-align:right;'),
			'footer'            => isset($totals['revenue']) && $totals['revenue']!=0 ? (number_format($totals['profit'] / $totals['revenue']) * 100)."%" : 0,
		),
		array(
			'name'              => 'click_through_rate',
			'value'             => $sum ? 'number_format($data->getCtr()*100, 2)."%"' : 'number_format($data->click_through_rate*100, 2)."%"', // FIX for sum feature
			'htmlOptions'       => array('style'=>'text-align:right;'),
			'footerHtmlOptions' => array('style'=>'text-align:right;'),
			'footer'            => isset($totals['imp']) && $totals['imp']!=0  ? (round($totals['clics'] / $totals['imp'], 4))."%" : 0,
		),
		array(
			'name'              => 'conversion_rate',
			'value'             => $sum ? 'number_format($data->getConvRate()*100, 2)."%"' : 'number_format($data->conversion_rate*100, 2)."%"', // FIX for sum feature
			'htmlOptions'       => array('style'=>'text-align:right;'),
			'footerHtmlOptions' => array('style'=>'text-align:right;'),
			'footer'            => isset($totals['clics']) && $totals['clics']!=0 ? (round( $totals['conv_adv'] / $totals['clics'], 4 ))."%" : 0,
		),
		array(
			'name'              => 'eCPM',
			'value'             => $sum ? 'number_format($data->getECPM(), 2)' : '$data->eCPM', // FIX for sum feature
			'htmlOptions'       => array('style'=>'text-align:right;'),
			'footerHtmlOptions' => array('style'=>'text-align:right;'),
			'footer'            => isset($totals['imp']) && $totals['imp']!=0 ? round($totals['spend'] * 1000 / $totals['imp'], 2) : 0,
		),
		array(
			'name'              => 'eCPC',
			'value'             => $sum ? 'number_format($data->getECPC(), 2)' : '$data->eCPC', // FIX for sum feature
			'htmlOptions'       => array('style'=>'text-align:right;'),
			'footerHtmlOptions' => array('style'=>'text-align:right;'),
			'footer'            => isset($totals['clics']) && $totals['clics']!=0 ? round($totals['spend'] / $totals['clics'], 2) : 0,
		),
		array(
			'name'              => 'eCPA',
			'value'             => $sum ? 'number_format($data->getECPA(), 2)' : '$data->eCPA', // FIX for sum feature
			'htmlOptions'       => array('style'=>'text-align:right;'),
			'footerHtmlOptions' => array('style'=>'text-align:right;'),
			'footer'            => isset($totals['conv_adv']) && $totals['conv_adv']!=0 ? round($totals['spend'] / $totals['conv_adv'], 2) : 0,
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
        array(
			'class'             => 'bootstrap.widgets.TbButtonColumn',
			'headerHtmlOptions' => array('style' => "width: 20px"),
			'buttons'           => array(
				'delete' => array(
					'visible' => '!$data->is_from_api',
				),
				'updateAjax' => array(
					'label'   => 'Update',
					'icon'    => 'pencil',
					'visible' => '!$data->is_from_api',
					'click'   => '
				    function(){
				    	// get row id from data-row-id attribute
				    	var id = $(this).parents("tr").attr("data-row-id");

				    	var dataInicial = "<div class=\"modal-header\"></div><div class=\"modal-body\" style=\"padding:100px 0px;text-align:center;\"><img src=\"'.  Yii::app()->theme->baseUrl .'/img/loading.gif\" width=\"40\" /></div><div class=\"modal-footer\"></div>";
						$("#modalDailyReport").html(dataInicial);
						$("#modalDailyReport").modal("toggle");

				    	
				    	// use jquery post method to get updateAjax view in a modal window
				    	$.post(
						"update/"+id,
						"",
						function(data)
							{
								//alert(data);
								$("#modalDailyReport").html(data);
							}
						)
					return false;
				    }
				    ',
				),
				'updateCampaign' => array(
					'label'   => 'Update Campaign',
					'icon'    => 'eye-open',
					//'visible' => '$data->getCapStatus()',
					'click' => '
				    function(){
				    	// get row id from data-row-id attribute
				    	var id = $(this).parents("tr").attr("data-row-c-id");

				    	var dataInicial = "<div class=\"modal-header\"></div><div class=\"modal-body\" style=\"padding:100px 0px;text-align:center;\"><img src=\"'.  Yii::app()->theme->baseUrl .'/img/loading.gif\" width=\"40\" /></div><div class=\"modal-footer\"></div>";
						$("#modalDailyReport").html(dataInicial);
						$("#modalDailyReport").modal("toggle");

				    	// use jquery post method to get updateAjax view in a modal window
				    	$.post(
						"'.Yii::app()->baseUrl.'/campaigns/updateAjax/"+id,
						"",
						function(data)
							{
								//alert(data);
								$("#modalDailyReport").html(data);
							}
						)
						return false;
				    }
				    ',
				),
			),

			'template' => $sum ? '{updateCampaign}' : '{updateCampaign} {updateAjax} {delete}',
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
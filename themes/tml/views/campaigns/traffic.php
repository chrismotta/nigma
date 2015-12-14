<?php

$dpp = isset($_GET['dpp']) ? $_GET['dpp'] : '1' ;

$dateStart      = isset($_GET['dateStart']) ? $_GET['dateStart'] : 'today' ;
$dateEnd        = isset($_GET['dateEnd']) ? $_GET['dateEnd'] : 'today';

$dateStart      = date('Y-m-d', strtotime($dateStart));
$dateEnd        = date('Y-m-d', strtotime($dateEnd));

$totalsGrap     = DailyTotals::model()->getTotals($dateStart,$dateEnd);

// El caso esta comentado porque daba valores 0 - Revisar más adelante
// if($accountManager==null && $opportunitie==null && $providers==null)
// 	$totals=DailyTotals::model()->getTotals($dateStart,$dateEnd);
// else 
// $totals = Campaigns::getTotals($dateStart, $dateEnd,null,$accountManager,$opportunitie,$providers);

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
				array('name' => 'Convs.', 'data' => $totalsGrap['conversions_s2s'],),
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

<fieldset class="formfilter well">

<?php 

// get arrays
$filters = array('manager'=>null, 'provider'=>null, 'advertiser'=>null);
if(isset($_GET['f'])) $filters = array_merge($filters, $_GET['f']); 
$group = array('date'=>1, 'prov'=>1, 'adv'=>1, 'coun'=>1, 'camp'=>1);
if(isset($_GET['g'])) $group = array_merge($group, $_GET['g']); 
$sum = array('clicks'=>1, 'conv'=>1, 'rate'=>1, 'revenue'=>1);
if(isset($_GET['s'])) $sum = array_merge($sum, $_GET['s']); 

$form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'id'                   => 'date-filter-form',
	'type'                 => 'search',
	'htmlOptions'          => array('style'=>'display:inline-block;margin:0px'),
	'enableAjaxValidation' => false,
	'action'               => Yii::app()->controller->createUrl(Yii::app()->controller->id . '/' .Yii::app()->controller->action->id),
	'method'               => 'GET',
	// 'clientOptions'        =>array('validateOnSubmit'=>true, 'validateOnChange'=>true),
)); ?> 
<div class="formfilter-date-large">

	<?php 

	echo '<div class="form-row">';

	echo KHtml::datePickerPresets($dpp);
	echo "<span class='formfilter-space'></span>";
	echo KHtml::datePicker('dateStart', $dateStart, array(), array('style'=>'width:73px'), 'From');
	echo "<span class='formfilter-space'></span>";
	echo KHtml::datePicker('dateEnd', $dateEnd, array(), array('style'=>'width:73px'), 'To');

	echo "<span class='formfilter-space'></span>";

	if (FilterManager::model()->isUserTotalAccess('daily'))
		echo KHtml::filterAccountManagers($filters['manager'], array('class'=>'span2'), 'f[manager]');	
	echo "<span class='formfilter-space'></span>";
	echo KHtml::filterAdvertisers($filters['advertiser'], array('class'=>'span2'), 'f[advertiser]');
	echo "<span class='formfilter-space'></span>";
	echo KHtml::filterProviders($filters['provider'], null, array('class'=>'span2'), 'f[provider]');

	echo '</div>';
	echo '<div>';

	echo CHtml::hiddenField('g[date]', $group['date'], array('id'=>'g_date'));
	echo CHtml::hiddenField('g[prov]', $group['prov'], array('id'=>'g_prov'));
	echo CHtml::hiddenField('g[adv]', $group['adv'], array('id'=>'g_adv'));
	echo CHtml::hiddenField('g[coun]', $group['coun'], array('id'=>'g_coun'));
	echo CHtml::hiddenField('g[camp]', $group['camp'], array('id'=>'g_camp'));

	$this->widget(
	    'bootstrap.widgets.TbButtonGroup',
	    array(
	        'toggle' => 'checkbox',
	        // 'type' => 'inverse',
	        'buttons' => array(
	        	array('label' => 'Group Columns', 'disabled' => 'disabled', 'type' => 'info'),
	            array('label' => 'Date', 'active'=>$group['date'], 
	            	'htmlOptions'=>array('onclick' => '$("#g_date").val( 1 - $("#g_date").val() );')
	            	),
	            array('label' => 'Traffic Source', 'active'=>$group['prov'], 
	            	'htmlOptions'=>array('onclick' => '$("#g_prov").val( 1 - $("#g_prov").val() );')
	            	),
	            array('label' => 'Advertiser', 'active'=>$group['adv'], 
	            	'htmlOptions'=>array('onclick' => '$("#g_adv").val( 1 - $("#g_adv").val() );')
	            	),
	            array('label' => 'Country', 'active'=>$group['coun'], 
	            	'htmlOptions'=>array('onclick' => '$("#g_coun").val( 1 - $("#g_coun").val() );')
	            	),
	            array('label' => 'Campaign', 'active'=>$group['camp'], 
	            	'htmlOptions'=>array('onclick' => '$("#g_camp").val( 1 - $("#g_camp").val() );')
	            	),
	        ),
	    )
	);

	echo "<span class='formfilter-space'></span>";
	
	echo CHtml::hiddenField('s[clicks]', $sum['clicks'], array('id'=>'s_clicks'));
	echo CHtml::hiddenField('s[conv]', $sum['conv'], array('id'=>'s_conv'));
	echo CHtml::hiddenField('s[rate]', $sum['rate'], array('id'=>'s_rate'));
	echo CHtml::hiddenField('s[revenue]', $sum['revenue'], array('id'=>'s_revenue'));

	$this->widget(
	    'bootstrap.widgets.TbButtonGroup',
	    array(
	        'toggle' => 'checkbox',
	        // 'type' => 'inverse',
	        'buttons' => array(
	        	array('label' => 'Sum Columns', 'disabled' => 'disabled', 'type' => 'info'),
	            array('label' => 'Clicks', 'active'=>$sum['clicks'], 
	            	'htmlOptions'=>array('onclick' => '$("#s_clicks").val( 1 - $("#s_clicks").val() );')
	            	),
	            array('label' => 'Conv.', 'active'=>$sum['conv'], 
	            	'htmlOptions'=>array('onclick' => '$("#s_conv").val( 1 - $("#s_conv").val() );')
	            	),
	            array('label' => 'Rate', 'active'=>$sum['rate'], 
	            	'htmlOptions'=>array('onclick' => '$("#s_rate").val( 1 - $("#s_rate").val() );')
	            	),
	            array('label' => 'Revenue', 'active'=>$sum['revenue'], 
	            	'htmlOptions'=>array('onclick' => '$("#s_revenue").val( 1 - $("#s_revenue").val() );')
	            	),
	        ),
	    )
	); 
	
	echo "<span class='formfilter-space'></span>";

	$this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'label'=>'Submit', 'type' => 'success', 'htmlOptions' => array('class' => 'showLoading')));

	echo '</div>';
	
	?>
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
</fieldset>

<?php 
function trafficGridView($controller, $model, $dateStart, $dateEnd, $group=array(), $sum=array(), $filters=array(), $isTest=false){

	$totals = $model->searchTraffic($dateStart, $dateEnd, $group, $filters, $isTest, true);
	$dataProvider = $model->searchTraffic($dateStart, $dateEnd, $group, $filters, $isTest);

	$controller->widget('application.components.NiExtendedGridView', array(
		'id'                       => 'traffic-grid',
		'dataProvider'             => $dataProvider,
		// 'filter'                    => $model,
	    // 'fixedHeader'			   => true,
	    // 'headerOffset'			   => 50,
		'type'                     => 'condensed',
		'rowHtmlOptionsExpression' => 'array("data-row-id" => $data->campaigns_id)',
		'template'                 => '{items} {pagerExt} {summary}',
		// 'htmlOptions'              => array('style'=>'width:500px'),
		'columns'                  => array(
			array(
				'name'    => 'date',
				'htmlOptions' => array('class' => 'date-column'),
				'visible' => $group['date'],
	        	),
			array(
				'name'    => 'provider',
				'visible' => $group['prov'],
	        	),
			array(
				'name'    => 'advertiser',
				'visible' => $group['adv'],
	        	),
			array(
				'name'    => 'country_name',
				'htmlOptions' => array('class' => 'traffic-group-column'),
				'visible' => $group['coun'],
	        	),
			array(
				'name'    => 'campaign',
				'value'   => '$data->campaigns->getExternalName($data->campaigns_id)',
				'htmlOptions' => array('class' => 'traffic-group-column'),
				'visible' => $group['camp'],
	        	),
			array(
				'name'              => 'clicks',
				'visible'           => $sum['clicks'],
				//
				'htmlOptions'       => array('class' => 'traffic-sum-column'),
				'headerHtmlOptions' => array('class' => 'traffic-sum-column'),
				'footerHtmlOptions' => array('class' => 'traffic-sum-column'),
				'footer'            => $totals['clicks'],
	        	),
			array(
				'name'              => 'conversions',
				'htmlOptions'       => array('class' => 'traffic-sum-column'),
				'footer'            => $totals['conversions'],
				//
				'headerHtmlOptions' => array('class' => 'traffic-sum-column'),
				'footerHtmlOptions' => array('class' => 'traffic-sum-column'),
				'visible'           => $sum['conv'],
	        	),
			array(
				'name'              => 'convRate',
				'value'             => '$data->clicks > 0 ? number_format($data->conversions / $data->clicks * 100, 2)."%" : "-"',
				'footer'            => $totals['clicks'] > 0 ? number_format($totals['conversions'] / $totals['clicks'] * 100, 2) . '%' : '-',
				//
				'htmlOptions'       => array('class' => 'traffic-sum-column'),
				'headerHtmlOptions' => array('class' => 'traffic-sum-column'),
				'footerHtmlOptions' => array('class' => 'traffic-sum-column'),
				'visible'           => $sum['conv'],
	        	),
			array(
				'name'              => 'rate',
				'value'             => '"$".number_format($data->campaigns->getRateUSD($data->date),2)',
				//
				'htmlOptions'       => array('class' => 'traffic-sum-column'),
				'headerHtmlOptions' => array('class' => 'traffic-sum-column'),
				'footerHtmlOptions' => array('class' => 'traffic-sum-column'),
				'visible'           => $sum['rate'],
	        	),
			array(
				'name'              => 'revenue',
				'value'             => '"$".number_format($data->conversions * $data->campaigns->getRateUSD($data->date),2)',
				'footer'			=> '',
				//
				'htmlOptions'       => array('class' => 'traffic-sum-column','id'=>'revColumn'),
				'headerHtmlOptions' => array('class' => 'traffic-sum-column'),
				'footerHtmlOptions' => array('class' => 'traffic-sum-column','id'=>'revColumnFooter'),
				'visible'           => $sum['revenue'],
	        	),
			array(
				'class'             => 'bootstrap.widgets.TbButtonColumn',
				'headerHtmlOptions' => array('style' => "width: 70px; text-align:right;"),
				'htmlOptions'       =>array('style'=>'width: 45px; text-align:right;'),
				'visible'           => $group['camp'],
				'buttons'           => array(
					'showCampaign' => array(
						'label' => 'Show Campaign',
						'icon'  => 'eye-open',
						/*'click' => '
					    function() {
					    	// get row id from data-row-id attribute
					    	var id = $(this).parents("tr").attr("data-row-id");
							var dateStart = $("#dateStart").val();
							var dateEnd = $("#dateEnd").val();						
							window.location="graphicCampaign?id="+id+"&dateStart="+dateStart+"&dateEnd="+dateEnd;
							return false;
					    }
					    ',*/
					    'url'=>'"graphicCampaign/".$data->campaigns_id."?dateStart='.$dateStart.'&dateEnd='.$dateEnd.'"',
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
		)
	); 

}

trafficGridView($this, $modelClicks, $dateStart, $dateEnd, $group, $sum, $filters, false);

echo '<br/>';
echo '<div class="well">';
echo '<h4>S2S Conversion Tests</h4>';

// get arrays
$testGroup = array('date'=>1, 'prov'=>0, 'adv'=>1, 'coun'=>1, 'camp'=>1);
$testSum = array('clicks'=>1, 'conv'=>1, 'rate'=>0, 'revenue'=>0);

trafficGridView($this, $modelClicks, $dateStart, $dateEnd, $testGroup, $testSum, null, true);
echo '</div>';

?>

<?php BuildGridView::printModal($this, 'modalTraffic', 'Traffic Report', array('style'=>'width: 90%;margin-left:-45%')); ?>
<?php BuildGridView::printModal($this, 'modalExcel', 'Traffic Report'); ?>

<div class="row" id="blank-row">
</div>
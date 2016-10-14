<?php 

// post data
$dpp       = isset($space['dpp']) ? $_REQUEST['dpp'] : '1' ;
$dateStart = isset($_REQUEST['dateStart']) ? $_REQUEST['dateStart'] : date("Y-m-d", strtotime("yesterday"));
$dateEnd   = isset($_REQUEST['dateEnd']) ? $_REQUEST['dateEnd'] : date("Y-m-d", strtotime("today"));
$timeStart = isset($_REQUEST['timeStart']) ? $_REQUEST['timeStart'] : '12:00 AM';
$timeEnd   = isset($_REQUEST['timeEnd']) ? $_REQUEST['timeEnd'] : '11:59 AM';

$accountManager = isset($_GET['accountManager']) ? $_GET['accountManager'] : NULL;
$advertisers    = isset($_GET['advertisers']) ? $_GET['advertisers'] : NULL;
$opportunities  = isset($_GET['opportunities']) ? $_GET['opportunities'] : NULL;
$providers      = isset($_GET['providers']) ? $_GET['providers'] : NULL;
$adv_categories = isset($_GET['advertisers-cat']) ? $_GET['advertisers-cat'] : NULL;

$editable = false;

$group = array(
	'Date'          =>0, 
	'TrafficSource' =>0, 
	'Advertiser'    =>1, 
	'Category'		=>0, 
	'Campaign'      =>0,
	'Vector'      	=>0,
	'Opportunity'	=>0,
	'Country'       =>0,
	'Carrier'		=>0,
	'AccountManager'=>0,
	);

if(isset($_REQUEST['group']))
	$group = $_REQUEST['group'];

$grouped = array_search(0, $group) ? 1 : 0;

$groupBy = '';
foreach ( $group as $property => $value )
{
	$groupBy .= '&groupBy['.$property.']='.$value;
}

	$sum = array(
		'Imp'        =>1, 
		'Clicks'     =>1, 
		'CTR'        =>1,
		'Conv'       =>1, 
		'CR'         =>1,
		//'Rate'       =>1, 
		'Revenue'    =>1,
		'Spend'      =>1,
		'Profit'     =>1,
		'eCPM'       =>0,
		'eCPC'       =>0,
		'eCPA'       =>0,
	);

if(isset($_REQUEST['sum']))
	$sum = $_REQUEST['sum'];



/*
$filterColumns                    = array();
$filterColumns['provider']        = 0; 
$filterColumns['advertiser']      = 0;
$filterColumns['opportunity']     = 0;
$filterColumns['campaign']        = 0;
$filterColumns['country']         = 0;
$filterColumns['category']        = 0;
$filterColumns['account_manager'] = 0;

if ( isset($_REQUEST['filters']) )
	$filterColumns = $_REQUEST['filters'];
*/
$filter                    = array();
$filter['provider']        = null; 
$filter['advertiser']      = null;
$filter['country']         = null;
$filter['campaign']        = null;
$filter['vector']          = null;
$filter['opportunity']     = null;
$filter['account_manager'] = null;
$filter['category']        = null;
$filter['carrier']         = null;

if ( isset($_REQUEST['filter']) )
{
	foreach ( $_REQUEST['filter'] as $f => $v )
	{
		$filter[$f] = $v;
	}
}

$space = "<span class='formfilter-space'></span>";

// breadcrumb

$this->breadcrumbs=array(
	'Daily Report',
);


$totalsGrap =$model->getTotals($dateStart, $dateEnd,$filter['account_manager'], $filter['opportunity'], $filter['provider'], $grouped, $filter['category'], $filter['advertiser'], $filter['country'], $filter['campaign'], $filter['vector'], $filter['carrier'] );


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
				array('name' => 'Imp.', 'data' => $totalsGrap['impressions'],),
				array('name' => 'Clicks', 'data' => $totalsGrap['clics'],),
				array('name' => 'Conv.','data' => $totalsGrap['conversions'],),
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

<?php 


$form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'id'                   =>'date-filter-form',
	'type'                 =>'search',
	'htmlOptions'          =>array('class'=>'well'),
	'enableAjaxValidation' =>true,
	'action'               => Yii::app()->getBaseUrl() . '/dailyReport/admin',
	'method'               => 'GET',
	'clientOptions'        =>array('validateOnSubmit'=>true, 'validateOnChange'=>true),
));


// report form

echo '<fieldset class="formfilter">';


// ----- Date Time
echo '<div class="row-fluid">';
echo '<div class="form-sep span12" style="margin-top:0px">DATE RANGE</div>';
//echo '<div class="form-sep span6" style="margin-top:0px">TIME RANGE</div>';
echo '</div>';

echo '<div class="row-fluid">';
	echo '<div class="span12">';

	echo KHtml::datePickerPresets($dpp, array('style'=>'width:120px'));

	echo $space;

	echo KHtml::datePicker('dateStart', $dateStart, array(), array('style'=>'width:100px'), 'From');

	echo $space;

	echo KHtml::datePicker('dateEnd', $dateEnd, array(), array('style'=>'width:100px'), 'To');
	
	echo '</div>';
/*
	echo '<div class="span6">';

	$this->widget(
	    'bootstrap.widgets.TbButton',
	    array(
			'type'   => 'info', 
			'toggle' => 'checkbox',
			'label'  => 'All Day',
	        )
	);
	echo $space;
	
	echo KHtml::timePicker('timeStart', '12:00 AM', array(), array('style'=>'width:100px;'), 'From');

	echo $space;

	echo KHtml::timePicker('timeEnd', '11:59 PM', array(), array('style'=>'width:100px;'), 'To');	

	echo '</div>';
*/

echo '</div>';




// ----- Groups

echo '<div class="row-fluid">';
echo '<div class="form-sep span12">GROUP COLUMNS</div>';
echo '</div>';

echo '<div class="row-fluid">';
echo '<div class="span12">';
echo '<div>';
ReportingManager::groupFilter($this, $group, 'group', null, '', 'small', 'info', false);
echo '</div>';
echo '</div>';
echo '</div>';

	

// ----- Sums

echo '<div class="row-fluid">';
echo '<div class="form-sep span12">SUM COLUMNS</div>';
echo '</div>';

echo '<div class="row-fluid">';
echo '<div class="span12">';
echo '<div>';
ReportingManager::groupFilter($this, $sum, 'sum', null, '', 'small', 'info', false);
echo '</div>';
echo '</div>';
echo '</div>';

// ----- Filters

echo '<div class="row-fluid">';
echo '<div class="form-sep span12">FILTERS</div>';
echo '</div>';

echo '<div class="row-fluid">';
echo '<div>';
//ReportingManager::addFilter($this, $filterColumns, 'filters', null, '', 'small', '', false);
echo '</div>';
echo '</div>';
echo '<div class="row-fluid" id="filters-row">';


KHtml::filterProvidersMulti($filter['provider'], NULL, array('style' => "width: 140px; margin-left: 1em",'id' => 'providers-select'),'filter[provider]');

KHtml::filterAdvertisersMulti($filter['advertiser'], null, array('style' => "width: 140px; margin-left: 1em",'id' => 'advertisers-select'),'filter[advertiser]');

KHtml::filterAdvertisersCategoryMulti($filter['category'], array('style' => "width: 140px; margin-left: 1em",'id' => 'advertisers-cat-select'),'filter[category]');

KHtml::filterCampaignsMulti($filter['campaign'], null, array('style' => "width: 140px; margin-left: 1em",'id' => 'campaigns-select'), 'filter[campaign]');

KHtml::filterVectorsMulti($filter['vector'], null, array('style' => "width: 140px; margin-left: 1em",'id' => 'vectors-select'), 'filter[vector]');

KHtml::filterOpportunitiesMulti($filter['opportunity'], $filter['account_manager'], array('style' => "width: 140px; margin-left: 1em",'id' => 'opportunities-select'),'filter[opportunity]');

KHtml::filterCountriesMulti($filter['country'], null, array('style' => "width: 140px; margin-left: 1em",'id' => 'country-select'), 'filter[country]');

KHtml::filterCarriersMulti($filter['carrier'], array('style' => "width: 140px; margin-left: 1em",'id' => 'carriers-select'),'filter[carrier]');

if (FilterManager::model()->isUserTotalAccess('daily'))
	KHtml::filterAccountManagersMulti($filter['account_manager'],array('id' => 'accountManager-select'),'opportunities-select','filter[account_manager]','filter[opportunity]');

// hide all .multi-select-hide
$jQuery = '$("div.multi-select-hide:not(:has(ul li.select2-search-choice))").hide()';
Yii::app()->clientScript->registerScript('hide', $jQuery, 4);

echo '</div>';



// ----- actions

echo '<div class="row-fluid">';
echo '<div class="form-sep span12">ACTIONS</div>';
echo '</div>';

	$this->widget('bootstrap.widgets.TbButton', 
		array(
			'buttonType'=>'link', 
			'label'=>'Submit', 
			'type' => 'success', 
			'htmlOptions' => array(
				'class' => 'showLoading',
				'onclick' => '$("#date-filter-form").attr("target", "_self");if ( $("#download-flag").length ) $("#download-flag").remove() ;$("#date-filter-form").submit();'
			)
		)
	); 

	echo $space;

	$this->widget('bootstrap.widgets.TbButton', 
		array(
			'buttonType'=>'link', 
			'label'=>'Download CSV', 
			'type' => 'warning', 
			//'url' => $link,
			'htmlOptions' => array(
				'class' => 'showLoading', 
				'onclick' => '$("#date-filter-form").attr("target", "#");if ( $("#download-flag").length ) $("#download-flag").val("true"); else $("#date-filter-form").append("<input type=\"hidden\" style=\"visibility:collapse;\" id=\"download-flag\" name=\"download\" value=\"true\" />"); $("#date-filter-form").submit();' 
			)
		)
	); 


	
$this->endWidget();


echo '</div>';

?>

<?php 

if(count($_REQUEST)>1){
	$dataProvider=$model->search($dateStart, $dateEnd, $filter['account_manager'], $filter['opportunity'], $filter['provider'], $grouped, $filter['category'], $group, $sum, $filter['advertiser'], $filter['country'], $filter['campaign'], $filter['vector'], $filter['carrier']);
	$totals=$model->searchTotals($dateStart, $dateEnd,$filter['account_manager'], $filter['opportunity'], $filter['provider'], $grouped, $filter['category'], $filter['advertiser'], $filter['country'], $filter['campaign'], $filter['vector'], $filter['carrier']);

	$this->widget('application.components.NiExtendedGridView', array(
	'id'                       => 'daily-report-grid',
	'fixedHeader'              => true,
	'headerOffset'             => 50,
	'responsiveTable' 		   => true,
	'dataProvider'             => $dataProvider,
	//'filter'                   => $model,
	'selectionChanged'         => 'js:selectionChangedDailyReport',
	'type'                     => 'striped condensed',
	'rowHtmlOptionsExpression' => 'array("data-row-id" => $data->id, "data-row-net-id" => $data->providers_id, "data-row-c-id" => $data->campaigns_id)',
	'template'                 => '{items} {pagerExt} {summary}',
	// 'rowCssClassExpression'    => '$data->getCapStatus() ? "errorCap" : null',
	'columns'                  => array(
		array(
			'name'               =>	'id',
			'footer'             => 'Totals:',
			// VECTOR COLOR
			'cssClassExpression' => '$data->isFromVector() ? "isFromVector" : NULL',
			'htmlOptions'        => array('style' => 'padding-left: 10px; height: 70px;'),
			'headerHtmlOptions'  => array('style' => 'border-left: medium solid #FFF;'),
            'visible' => false,
		),
		array(
			'name'   =>	'account_manager',
			//'filter' => $providers_names,
            'visible' => $group['AccountManager'],
		),		
		array(
			'name'              => 'date',
			'value'             => 'date("d-m-Y", strtotime($data->date))',
			'headerHtmlOptions' => array('style' => "width: 60px"),
			'htmlOptions'       => array(
					'class' => 'date', 
					'style' =>'text-align:right;'
				),
			// 'filter'      => false,
            'visible' => $group['Date'],
        ),
		array(
			'name'   =>	'providers_name',
			'value'  =>	'$data->providers->name',
			//'filter' => $providers_names,
            'visible' => $group['TrafficSource'],
		),
		array(
			'name'   =>	'advertisers_name',
            'visible' => $group['Advertiser'],
		),
		array(
			'name'   =>	'advertiser_cat',
            'visible' => $group['Category'],
		),		
		array(
			'name'        => 'campaign_name',
			'value'       => 'Campaigns::model()->getExternalName($data->campaigns_id)',
			'headerHtmlOptions' => array('width' => '200'),
			'htmlOptions' => array('style'=>'word-wrap:break-word;'),
            'visible' => $group['Campaign'],
		),
		array(
			'name'   =>	'vector',
			//'value'	=> '$data->dailyReportVectors->vectors_id',
            'visible' => $group['Vector'],
		),		
		array(
			'name'   =>	'opportunity',
            'visible' => $group['Opportunity'],
		),		
		array(
			'name'   =>	'country',
            'visible' => $group['Country'],
		),	
		array(
			'name'   =>	'carrier',
            'visible' => $group['Carrier'],
		),
		array(	
			'name'              => 'imp',
			'htmlOptions'       => array('style'=>'text-align:right;'),
			'footerHtmlOptions' => array('style'=>'text-align:right;'),
			'footer'            => number_format($totals['imp']),
            'visible' => $sum['Imp'],
        ),
        array(	
			'name'              => 'imp_adv',
			'htmlOptions'       => array('style'=>'text-align:right;'),
			'footerHtmlOptions' => array('style'=>'text-align:right;'),
			'footer'            => number_format($totals['imp_adv']),
			'class'             => 'bootstrap.widgets.TbEditableColumn',
			'editable'          => array(
				'apply'      => $grouped ? false : true,
				'title'      => 'Impressions',
				'type'       => 'text',
				'url'        => 'updateEditable/',
				'emptytext'  => 'Add',
				'inputclass' => 'input-mini',
				'success'    => 'js: function(response, newValue) {
					  	if (!response.success) {
							$.fn.yiiGridView.update("daily-report-grid");
					  	}
					}',
            ),
            'visible' => $editable,
        ),
        array(
			'name'              => 'clics',
			'htmlOptions'       => array('style'=>'text-align:right;'),
			'footerHtmlOptions' => array('style'=>'text-align:right;'),
			'footer'            => number_format($totals['clics']),
            'visible' => $sum['Clicks'],
        ),
        /*array(
            'name'  => 'clics_redirect',
            'value' => '$data->getClicksRedirect()',
			'htmlOptions'       => array('style'=>'text-align:right;'),
			'footerHtmlOptions' => array('style'=>'text-align:right;'),
			'footer'            => number_format($totals['clics']),
        ),*/
		array(
			'name'              => 'click_through_rate',
			'value'             => $grouped ? 'number_format($data->getCtr()*100, 2)."%"' : 'number_format($data->click_through_rate*100, 2)."%"', // FIX for sum feature
			'htmlOptions'       => array('style'=>'text-align:right;'),
			'footerHtmlOptions' => array('style'=>'text-align:right;'),
			'footer'            => isset($totals['imp']) && $totals['imp']!=0  ? (round($totals['clics'] / $totals['imp'], 4)*100)."%" : 0,
            'visible' => $sum['CTR'],
		),
        array(
			'name'              => 'conv_api',
			'htmlOptions'       => array('style'=>'text-align:right;'),
			'footerHtmlOptions' => array('style'=>'text-align:right;'),
			'footer'            => number_format($totals['conv_api']),
            'visible' => $sum['Conv'],
        ),
		array(
			'name'              => 'conv_adv',
			// 'filterHtmlOptions' => array('colspan'=>'2'),
			'htmlOptions'       => array('style'=>'text-align:right;'),
			'footerHtmlOptions' => array('style'=>'text-align:right;'),
			'class'             => 'bootstrap.widgets.TbEditableColumn',
			'cssClassExpression'=> '$data->campaigns->opportunities->rate === NULL 
									&& $data->campaigns->opportunities->carriers_id === NULL ?
									"notMultiCarrier" :
									"multiCarrier"',
			'editable'          => array(
				'apply'      => $grouped ? false : true,
				'title'      => 'Conversions',
				'type'       => 'text',
				'url'        => 'updateEditable/',
				'emptytext'  => 'Add',
				'inputclass' => 'input-mini',
				'success'    => 'js: function(response, newValue) {
					  	if (!response.success) {
							$.fn.yiiGridView.update("daily-report-grid");
					  	}
					}',
            ),
			'footer' => number_format($totals['conv_adv']),
            'visible' => $editable,
		),
		array(
			'name'              => 'mr',
			'filter'			=> false,
			'headerHtmlOptions' => array('class'=>'plusMR'),
			//'filterHtmlOptions' => array('class'=>'plusMR'),
			'htmlOptions'       => array('class'=>'plusMR'),
			'type'              => 'raw',
			'value'             =>	'
				$data->campaigns->opportunities->rate === NULL && $data->campaigns->opportunities->carriers_id === NULL && '.$grouped.' == 0 ?
					CHtml::link(
            				"<i class=\"icon-plus\"></i>",
	            			"javascript:;",
	        				array(
	        					"onClick" => CHtml::ajax( array(
									"type"    => "POST",
									"url"     => "multiRate/" . $data->id,
									"data"    => "'.$_SERVER['QUERY_STRING'].'",
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
            'visible' => $editable,
        ),
		array(
			'name'              => 'conversion_rate',
			'value'             => $grouped ? 'number_format($data->getConvRate()*100, 2)."%"' : 'number_format($data->conversion_rate*100, 2)."%"', // FIX for sum feature
			'htmlOptions'       => array('style'=>'text-align:right;'),
			'footerHtmlOptions' => array('style'=>'text-align:right;'),
			'footer'            => isset($totals['clics']) && $totals['clics']!=0 ? (round( $totals['conv'] / $totals['clics'], 4 )*100)."%" : 0,
            'visible' => $sum['CR'],
		),
		/*
		array(
			'name'        => 'rate',
			'value'       => '$data->getRateUSD() ? "$".number_format($data->getRateUSD(),2) : "$0.00"',
			'htmlOptions' => array('style'=>'text-align:right;'),
            'visible' => $sum['Rate'] && !$grouped,
		),
		*/
        array(
			'name'              => 'revenue',
			'value'             => '"$".number_format($data->getRevenueUSD(), 2)',
			'htmlOptions'       => array('style'=>'text-align:right;'),
			'footerHtmlOptions' => array('style'=>'text-align:right;'),
			'footer'            => '$'.number_format($totals['revenue'],2),
            'visible' => $sum['Revenue'],
        ),
		array(
			'name'              => 'spend',
			'value'             => '"$".number_format($data->getSpendUSD(), 2)',
			'htmlOptions'       => array('style'=>'text-align:right;'),
			'footerHtmlOptions' => array('style'=>'text-align:right;'),
			'footer'            => '$'.number_format($totals['spend'],2),
            'visible' => $sum['Spend'],
        ),
		array(
			'name'              => 'profit',
			'value'             => '"$".number_format($data->profit, 2)',
			'htmlOptions'       => array('style'=>'text-align:right;'),
			'footerHtmlOptions' => array('style'=>'text-align:right;'),
			'footer'            => '$'.number_format($totals['profit'],2),
            'visible' => $sum['Profit'],
		),
		array(
			'name'              => 'profit_percent',
			'value'             => $grouped ? '$data->revenue == 0 ? "0%" : number_format($data->profit / $data->getRevenueUSD() * 100) . "%"' : 'number_format($data->profit_percent*100)."%"', // FIX for sum feature
			'htmlOptions'       => array('style'=>'text-align:right;'),
			'footerHtmlOptions' => array('style'=>'text-align:right;'),
			'footer'            => isset($totals['revenue']) && $totals['revenue']!=0 ? number_format(($totals['profit'] / $totals['revenue']) * 100)."%" : 0,
            'visible' => $sum['Profit'],
		),
		array(
			'name'              => 'eCPM',
			'value'             => $grouped ? '"$".number_format($data->getECPM(), 2)' : '"$".$data->eCPM', // FIX for sum feature
			'htmlOptions'       => array('style'=>'text-align:right;'),
			'footerHtmlOptions' => array('style'=>'text-align:right;'),
			'footer'            => isset($totals['imp']) && $totals['imp']!=0 ? '$'.round($totals['spend'] * 1000 / $totals['imp'], 2) : '$0',
            'visible' => $sum['eCPM'],
		),
		array(
			'name'              => 'eCPC',
			'value'             => $grouped ? '"$".number_format($data->getECPC(), 2)' : '"$".$data->eCPC', // FIX for sum feature
			'htmlOptions'       => array('style'=>'text-align:right;'),
			'footerHtmlOptions' => array('style'=>'text-align:right;'),
			'footer'            => isset($totals['clics']) && $totals['clics']!=0 ? '$'.round($totals['spend'] / $totals['clics'], 2) : '$0',
            'visible' => $sum['eCPC'],
		),
		array(
			'name'              => 'eCPA',
			'value'             => $grouped ? '"$".number_format($data->getECPA(), 2)' : '"$".$data->eCPA', // FIX for sum feature
			'htmlOptions'       => array('style'=>'text-align:right;'),
			'footerHtmlOptions' => array('style'=>'text-align:right;'),
			'footer'            => isset($totals['conv']) && $totals['conv']!=0 ? '$'.round($totals['spend'] / $totals['conv'], 2) : '$0',
            'visible' => $sum['eCPA'],
		),


	),
)); 
}

?>

<?php BuildGridView::printModal($this, 'modalDailyReport', 'Daily Report'); ?>

<div class="row" id="blank-row">
</div>

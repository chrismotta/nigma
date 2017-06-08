<?php 
$start = microtime(true);
// post data

$dpp       = isset($space['dpp']) ? $_REQUEST['dpp'] : '1' ;
$dateStart = isset($_REQUEST['dateStart']) ? $_REQUEST['dateStart'] : 'today' ;
$dateEnd   = isset($_REQUEST['dateEnd']) ? $_REQUEST['dateEnd'] : 'today';

$partner = isset($publisher_name) && isset($publisher_id) ? $publisher_name . ' (' . $publisher_id . ')' : null;

$space = "<span class='formfilter-space'></span>";

// breadcrumb

$this->breadcrumbs=array(
	'Impressions Report',
);


echo CHtml::beginForm('', 'POST', array(
	'id'    =>'filter-form',
	'class' =>'well form-search',
	));


// report form

echo '<fieldset class="formfilter">';


// ----- Date Time
echo '<div class="row-fluid">';
echo '<div class="form-sep span6" style="margin-top:0px">DATE RANGE</div>';
echo '<div class="form-sep span6" style="margin-top:0px">TIME RANGE</div>';
echo '</div>';

echo '<div class="row-fluid">';
	echo '<div class="span6">';

	echo KHtml::datePickerPresets($dpp, array('style'=>'width:120px'));

	echo $space;

	echo KHtml::datePicker('dateStart', $dateStart, array(), array('style'=>'width:100px'), 'From');

	echo $space;

	echo KHtml::datePicker('dateEnd', $dateEnd, array(), array('style'=>'width:100px'), 'To');
	
	echo '</div>';
	echo '<div class="span6">';

	$this->widget(
	    'bootstrap.widgets.TbButton',
	    array(
			'type' => 'info', 
	        'toggle' => 'checkbox',
	    	'label' => 'All Day',
	        )
	);
	echo $space;
	
	echo KHtml::timePicker('timeStart', '12:00 AM', array(), array('style'=>'width:70px'), 'From');

	echo $space;

	echo KHtml::timePicker('timeEnd', '11:59 PM', array(), array('style'=>'width:70px'), 'To');	

	echo '</div>';
echo '</div>';



$groupColumns1 = array();
$groupColumns1['date']                     = 0;
$groupColumns1['hour']                     = 0;
if(!$partner) $groupColumns1['provider']   = 1;
$groupColumns1['placement']                = !$partner ? 0 : 1;
if(!$partner) $groupColumns1['tag']        = 1;
if(!$partner) $groupColumns1['advertiser'] = 0;
if(!$partner) $groupColumns1['campaign']   = 0;
$groupColumns1['pubid']                    = 0;
$groupColumns1['country']                  = 0;
$groupColumns1['os_type']                  = 0;
$groupColumns1['os_version']               = 0;

$groupColumns2 = array();
$groupColumns2['device_type']              = 0;
$groupColumns2['device_brand']             = 0;
$groupColumns2['device_model']             = 0;
$groupColumns2['browser_type']             = 0;
$groupColumns2['browser_version']          = 0;
$groupColumns2['connection_type']          = 0;
$groupColumns2['carrier']                  = 0;

if(isset($_REQUEST['group1']))
	$groupColumns1 = $_REQUEST['group1'];
if(isset($_REQUEST['group2']))
	$groupColumns2 = $_REQUEST['group2'];

$sumColumns = array();
$sumColumns['impressions']               = 1;
$sumColumns['unique_imps']               = 1;
$sumColumns['revenue']                   = 1;
if(!$partner) $sumColumns['cost']        = 1;
if(!$partner) $sumColumns['profit']      = 1;
$sumColumns['revenue_eCPM']              = 1;
if(!$partner) $sumColumns['cost_eCPM']   = 1;
if(!$partner) $sumColumns['profit_eCPM'] = 1;


if(isset($_REQUEST['sum']))
	$sumColumns = $_REQUEST['sum'];


// ----- Groups

echo '<div class="row-fluid">';
echo '<div class="form-sep span12">GROUP COLUMNS</div>';
echo '</div>';

echo '<div class="row-fluid">';
echo '<div class="span12">';
echo '<div>';
ReportingManager::groupFilter($this, $groupColumns1, 'group1', null, '', 'small', 'info', false, $partner);
echo '</div>';
echo '</div>';
echo '</div>';
echo '<div class="row-fluid">';
echo '<div class="span12">';
echo '<div>';
ReportingManager::groupFilter($this, $groupColumns2, 'group2', null, '', 'small', 'info', false, $partner);
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
ReportingManager::groupFilter($this, $sumColumns, 'sum', null, '', 'small', 'info', false, $partner);
echo '</div>';

echo '</div>';
echo '</div>';


// ----- Filters

$filterColumns = array();
if(!$partner) $filterColumns['provider']   = 0; 
if(!$partner) $filterColumns['tag']        = 0;
$filterColumns['placement']                = 0;
if(!$partner) $filterColumns['advertiser'] = 0;
if(!$partner) $filterColumns['campaign']   = 0;
$filterColumns['country']                  = 0;
$filterColumns['os_type']                  = 0;
$filterColumns['os_version']               = 0;
$filterColumns['device_type']              = 0;
$filterColumns['device_brand']             = 0;
$filterColumns['device_model']             = 0;
$filterColumns['browser_type']             = 0;
$filterColumns['browser_version']          = 0;
$filterColumns['connection_type']          = 0; 
$filterColumns['carrier']                  = 0;


echo '<div class="row-fluid">';
echo '<div class="form-sep span12">FILTERS</div>';
echo '</div>';

echo '<div class="row-fluid">';
echo '<div>';
ReportingManager::addFilter($this, $filterColumns, 'g', null, '', 'small', '', false);
echo '</div>';
echo '</div>';
echo '<div class="row-fluid" id="filters-row">';


isset($_REQUEST['filter']) ? $filter = $_REQUEST['filter'] : $filter = null;

ReportingManager::dataMultiSelect(new DSupply(), 'provider', $filter);
$comparePlacement = $partner ? array('provider'=>$partner) : array();
ReportingManager::dataMultiSelect(new DDemand(), 'tag', $filter);
ReportingManager::dataMultiSelect(new DSupply(), 'placement', $filter, $comparePlacement);
ReportingManager::dataMultiSelect(new DDemand(), 'advertiser', $filter);
ReportingManager::dataMultiSelect(new DDemand(), 'campaign', $filter);
ReportingManager::dataMultiSelect(new FImpCompact(), 'country', $filter);
ReportingManager::dataMultiSelect(new FImpCompact(), 'os_type', $filter);
ReportingManager::dataMultiSelect(new FImpCompact(), 'os_version', $filter);
ReportingManager::dataMultiSelect(new FImpCompact(), 'device_type', $filter);
ReportingManager::dataMultiSelect(new FImpCompact(), 'device_brand', $filter);
ReportingManager::dataMultiSelect(new FImpCompact(), 'device_model', $filter);
ReportingManager::dataMultiSelect(new FImpCompact(), 'browser_type', $filter);
ReportingManager::dataMultiSelect(new FImpCompact(), 'browser_version', $filter);
ReportingManager::dataMultiSelect(new FImpCompact(), 'connection_type', $filter);
ReportingManager::dataMultiSelect(new FImpCompact(), 'carrier', $filter);

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
			'buttonType'=>'submit', 
			'label'=>'Submit', 
			'type' => 'success', 
			'htmlOptions' => array(
				'class' => 'showLoading',
				'onclick' => '$("#filter-form").attr("target", "_self");if ( $("#download-flag").length ) $("#download-flag").remove() ;$("#filter-form").submit();'				
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
				'onclick' => '$("#filter-form").attr("target", "#");if ( $("#download-flag").length ) $("#download-flag").val("true"); else $("#filter-form").append("<input type=\"hidden\" style=\"visibility:collapse;\" id=\"download-flag\" name=\"download\" value=\"true\" />"); $("#filter-form").submit();' 
			)
		)
	); 
	
echo CHtml::endForm();


echo '</div>';

if(count($_REQUEST)>1){

	// JSON
	// echo '<div class="row-fluid" style="word-wrap: break-word;">';
	// echo '<hr>';
	// echo json_encode($_REQUEST);
	// echo '<hr>';
	// echo '</div>';
	//$dependency = new CDbCacheDependency('SELECT MAX(id) FROM F_Imp');

	$totals = $model->cache(3600)->search(true, $partner);
	
	$this->widget('application.components.NiExtendedGridView', array(
		'id'              => 'impressions-grid',
		'dataProvider'    => $model->cache(3600)->search(false, $partner),
		'filter'          => null,
		'type'            => 'condensed',
		'template'        => '{items} {pagerExt} {summary}',
		'columns'         => array(
			array(
				'name' => 'date',
				'visible' => $groupColumns1['date'],
				),
			array(
				'name' => 'hour',
				'visible' => $groupColumns1['hour'],
				),
			array(
				'name' => 'provider',
				'visible' => !$partner ? $groupColumns1['provider'] : false,
				),
			array(
				'name' => 'placement',
				'visible' => $groupColumns1['placement'],
				),
			array(
				'name' => 'tag',
				'visible' => !$partner ? $groupColumns1['tag'] : false,
				),
			array(
				'name' => 'advertiser',
				'visible' => !$partner ? $groupColumns1['advertiser'] : false,
				),
			array(
				'name' => 'campaign',
				'visible' => !$partner ? $groupColumns1['campaign'] : false,
				),
			array(
				'name' => 'pubid',
				'visible' => $groupColumns1['pubid'],
				),
			array(
				'name' => 'country',
				'visible' => $groupColumns1['country'],
				),
			array(
				'name' => 'os_type',
				'visible' => $groupColumns1['os_type'],
				),
			array(
				'name' => 'os_version',
				'visible' => $groupColumns1['os_version'],
				),
			array(
				'name' => 'device_type',
				'visible' => $groupColumns2['device_type'],
				),
			array(
				'name' => 'device_brand',
				'visible' => $groupColumns2['device_brand'],
				),
			array(
				'name' => 'device_model',
				'visible' => $groupColumns2['device_model'],
				),
			array(
				'name' => 'browser_type',
				'visible' => $groupColumns2['browser_type'],
				),
			array(
				'name' => 'browser_version',
				'visible' => $groupColumns2['browser_version'],
				),
			array(
				'name' => 'connection_type',
				'visible' => $groupColumns2['connection_type'],
				),
			array(
				'name' => 'carrier',
				'visible' => $groupColumns2['carrier'],
				),
			array(
				'name' => 'impressions',
				'visible' => $sumColumns['impressions'],
				'footer' => $totals['impressions'],
				'headerHtmlOptions' => array('style'=>'text-align:right'),
				'htmlOptions' => array('style'=>'text-align:right'),
				'footerHtmlOptions' => array('style'=>'text-align:right'),
				),
			array(
				'name' => 'unique_imps',
				'visible' => $sumColumns['unique_imps'],
				'footer' => $totals['unique_imps'],
				'headerHtmlOptions' => array('style'=>'text-align:right'),
				'htmlOptions' => array('style'=>'text-align:right'),
				'footerHtmlOptions' => array('style'=>'text-align:right'),
				),
			array(
				'name' => 'revenue',
				'visible' => $sumColumns['revenue'],
				'footer' => $totals['revenue'],
				'headerHtmlOptions' => array('style'=>'text-align:right'),
				'htmlOptions' => array('style'=>'text-align:right'),
				'footerHtmlOptions' => array('style'=>'text-align:right'),
				),
			array(
				'name' => 'cost',
				'visible' => !$partner ? $sumColumns['cost'] : false,
				'footer' => $totals['cost'],
				'headerHtmlOptions' => array('style'=>'text-align:right'),
				'htmlOptions' => array('style'=>'text-align:right'),
				'footerHtmlOptions' => array('style'=>'text-align:right'),
				),
			array(
				'name' => 'profit',
				'visible' => !$partner ? $sumColumns['profit'] : false,
				'footer' => $totals['profit'],
				'headerHtmlOptions' => array('style'=>'text-align:right'),
				'htmlOptions' => array('style'=>'text-align:right'),
				'footerHtmlOptions' => array('style'=>'text-align:right'),
				),
			array(
				'name' => 'revenue_eCPM',
				'header' => !$partner ? 'ReCPM' : 'eCPM',
				'visible' => $sumColumns['revenue_eCPM'],
				'footer' => $totals['revenue_eCPM'],
				'headerHtmlOptions' => array('style'=>'text-align:right'),
				'htmlOptions' => array('style'=>'text-align:right'),
				'footerHtmlOptions' => array('style'=>'text-align:right'),
				),
			array(
				'name' => 'cost_eCPM',
				'visible' => !$partner ? $sumColumns['cost_eCPM'] : false,
				'footer' => $totals['cost_eCPM'],
				'headerHtmlOptions' => array('style'=>'text-align:right'),
				'htmlOptions' => array('style'=>'text-align:right'),
				'footerHtmlOptions' => array('style'=>'text-align:right'),
				),
			array(
				'name' => 'profit_eCPM',
				'visible' => !$partner ? $sumColumns['profit_eCPM'] : false,
				'footer' => $totals['profit_eCPM'],
				'headerHtmlOptions' => array('style'=>'text-align:right'),
				'htmlOptions' => array('style'=>'text-align:right'),
				'footerHtmlOptions' => array('style'=>'text-align:right'),
				),
			)
	));
$elapsed = (microtime(true) - $start);
echo '<div class="grid-view" style="margin-bottom:30px;margin-top:-10px;padding-top:0px !important"><div class="summary">Total lapsed time: '.number_format($elapsed,2).' seg.</div></div>';
}

?>
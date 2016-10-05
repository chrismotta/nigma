<?php 

// post data
$dpp       = isset($space['dpp']) ? $_REQUEST['dpp'] : '1' ;
$model->dateStart = isset($_REQUEST['dateStart']) ? $_REQUEST['dateStart'] : date("Y-m-d", strtotime("yesterday"));
$model->dateEnd = isset($_REQUEST['dateEnd']) ? $_REQUEST['dateEnd'] : date("Y-m-d", strtotime("today"));

$timeStart = isset($_REQUEST['timeStart']) ? $_REQUEST['timeStart'] : '12:00 AM';
$timeEnd = isset($_REQUEST['timeEnd']) ? $_REQUEST['timeEnd'] : '11:59 PM';


$model->providers_id = isset($_REQUEST['ts']) ? $_REQUEST['ts'] : null;
$model->only_conversions = isset($_REQUEST['c']) ? true : false;
$partner = isset($publisher_name) ? $publisher_name : null;

$groupColumns1 = array();
$groupColumns1['Date']              = 1; 
$groupColumns1['TrafficSource']     = 1; 
$groupColumns1['TrafficSourceType'] = 1; 
$groupColumns1['Advertiser']        = 1;
$groupColumns1['Campaign']          = 1;
$groupColumns1['Vector']            = 1;
$groupColumns1['Product']           = 1;
$groupColumns1['Country']           = 1;

$groupColumns2 = array();
$groupColumns2['ServerIP']       = 1;
$groupColumns2['OS']             = 1;
$groupColumns2['OSVersion']      = 1;
$groupColumns2['DeviceType']     = 1;
$groupColumns2['DeviceBrand']    = 1;
$groupColumns2['DeviceModel']    = 1;
$groupColumns2['Browser']        = 1;
$groupColumns2['BrowserVersion'] = 1;
$groupColumns2['Carrier']        = 1;


if(isset($_REQUEST['group1']))
	$groupColumns1 = $_REQUEST['group1'];
if(isset($_REQUEST['group2']))
	$groupColumns2 = $_REQUEST['group2'];

$group = array_merge($groupColumns1, $groupColumns2); 
$grouped = 0;

$groupBy = '';
$groupedByDate = false;
foreach ( $group as $property => $value )
{
	$groupBy .= '&groupBy['.$property.']='.$value;

	if ( $value != 0 )
	{
		$grouped++;

		if ( $property == 'Date' )
			$groupedByDate = true;
	}
}

$sum = array();
$sum['Conv']               = 1;
$sum['Revenue']            = 1;
$sum['Spend']              = 1;
$sum['Profit']             = 1;

if(isset($_REQUEST['sum']))
	$sum = $_REQUEST['sum'];



/*
$filterColumns = array();
$filterColumns['provider']				   = 0; 
$filterColumns['advertiser']               = 0;
$filterColumns['country']                  = 0;
$filterColumns['campaign']           	   = 0;
$filterColumns['vector'] 		           = 0;
$filterColumns['os_version']               = 0;
$filterColumns['device_type']              = 0;
$filterColumns['device_brand']             = 0;
$filterColumns['device_model']             = 0;
$filterColumns['browser_type']             = 0;
$filterColumns['browser_version']          = 0;
$filterColumns['carrier']                  = 0;
*/

if ( isset($_REQUEST['filters']) )
	$filterColumns = $_REQUEST['filters'];

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
	'Clicks Report',
);


$form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'id'                   =>'date-filter-form',
	'type'                 =>'search',
	'htmlOptions'          =>array('class'=>'well'),
	'enableAjaxValidation' =>true,
	'action'               => Yii::app()->getBaseUrl() . '/clicklog/admin',
	'method'               => 'GET',
	'clientOptions'        =>array('validateOnSubmit'=>true, 'validateOnChange'=>true),
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

	echo KHtml::datePicker('dateStart', $model->dateStart, array(), array('style'=>'width:100px'), 'From');

	echo $space;

	echo KHtml::datePicker('dateEnd', $model->dateEnd, array(), array('style'=>'width:100px'), 'To');
	
	echo '</div>';

	echo '<div class="span6">';

	$this->widget(
	    'bootstrap.widgets.TbButton',
	    array(
			'type' => 'info', 
	    	'label' => 'All Day',
	    	'htmlOptions'=> array('onclick' => '$("#timeStart").val("12:00 AM");$("#timeEnd").val("11:59 PM");'),
	        )
	);
	echo $space;
	
	echo KHtml::timePicker('timeStart', $timeStart, array(), array('style'=>'width:70px'), 'From');

	echo $space;

	echo KHtml::timePicker('timeEnd', $timeEnd, array(), array('style'=>'width:70px'), 'To');	

	echo '</div>';


echo '</div>';




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
ReportingManager::groupFilter($this, $sum, 'sum', null, '', 'small', 'info', false, $partner);
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


/*
ReportingManager::dataMultiSelect(new DGeoLocation(), 'country', $filterColumns);
ReportingManager::dataMultiSelect(new DUserAgent(), 'os_type', $filterColumns);
ReportingManager::dataMultiSelect(new DUserAgent(), 'os_version', $filterColumns);
ReportingManager::dataMultiSelect(new DUserAgent(), 'device_type', $filterColumns);
ReportingManager::dataMultiSelect(new DUserAgent(), 'device_brand', $filterColumns);
ReportingManager::dataMultiSelect(new DUserAgent(), 'device_model', $filterColumns);
ReportingManager::dataMultiSelect(new DUserAgent(), 'browser_type', $filterColumns);
ReportingManager::dataMultiSelect(new DUserAgent(), 'browser_version', $filterColumns);
*/
KHtml::filterProvidersMulti($filter['provider'], NULL, array('style' => "width: 140px; margin-left: 1em",'id' => 'providers-select'),'filter[provider]');

KHtml::filterAdvertisersMulti($filter['advertiser'], null, array('style' => "width: 140px; margin-left: 1em",'id' => 'advertisers-select'),'filter[advertiser]');


KHtml::filterCampaignsMulti($filter['campaign'], null, array('style' => "width: 140px; margin-left: 1em",'id' => 'campaigns-select'), 'filter[campaign]');

KHtml::filterVectorsMulti($filter['vector'], null, array('style' => "width: 140px; margin-left: 1em",'id' => 'vectors-select'), 'filter[vector]');


KHtml::filterCountriesMulti($filter['country'], null, array('style' => "width: 140px; margin-left: 1em",'id' => 'country-select'), 'filter[country]');



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
	//Create link to load filters
	//$link='csv?download=true&dateStart='.$model->dateStart.'&dateEnd='.$model->dateEnd.'&ts='.$model->providers_id.'&c='.$model->only_conversions.$groupBy;

	//'.$model->dateStart.'&dateEnd='.$model->dateEnd.'&ts='.$model->providers_id.'&c='.$model->only_conversions;
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

if(count($_REQUEST)>1){

	// JSON
	// echo '<div class="row-fluid" style="word-wrap: break-word;">';
	// echo '<hr>';
	// echo json_encode($_REQUEST);
	// echo '<hr>';
	// echo '</div>';
		
	//$totals = $model->search(true, $partner);
	
	$totals=$model->csvReport($model->dateStart, $model->dateEnd, $model->providers_id, $model->only_conversions, $group, $filter, $timeStart, $timeEnd, true );

	$this->widget('application.components.NiExtendedGridView', array(
		'id'              => 'clickslog-grid',
		'dataProvider'    => $model->csvReport($model->dateStart, $model->dateEnd, $model->providers_id, $model->only_conversions, $group, $filter, $timeStart, $timeEnd ),
		'filter'          => null,
		'type'            => 'condensed',
		'template'        => '{items} {pagerExt} {summary}',
		'columns'         => array(
			array(
				'name' => 'id',
				'visible' => $grouped==0,
				),
		
			array(
				'name' => 'click_date',
				'visible' => $grouped==0 || $groupedByDate,
				),
			array(
				'name' => 'click_time',
				'visible' => $grouped==0,				
				),
			array(
				'name' => 'conv_date',
				'value' => '$data->conv_date',
				'visible' => $grouped==0,
				),
			array(
				'name' => 'conv_time',
				'value' => '$data->conv_time',
				'visible' => $grouped==0,				
				),			
			array(
				'name' => 'advertiser',
				'visible' => $groupColumns1['Advertiser'],
				),
			array(
				'name' => 'country_name',
				'visible' => $groupColumns1['Country'],
				),			
			array(
				'name' => 'campaigns_id',
				'visible' => $groupColumns1['Campaign'],
				),			
			array(
				'name' => 'campaigns_name',
				'value' => '$data->campaigns_name',
				'visible' => $groupColumns1['Campaign'],
				),
			array(
				'name' => 'vector_name',
				'visible' => $groupColumns1['Vector'],
				),									
			array(
				'name' => 'traffic_source',
				'visible' => $groupColumns1['TrafficSource'],
				),
			array(
				'name' => 'traffic_source_type',
				'visible' => $groupColumns1['TrafficSource'],
				),						
			array(
				'name' => 'os',
				'visible' => $groupColumns2['OS'],
				),
			array(
				'name' => 'os_version',
				'visible' => $groupColumns2['OSVersion'],
				),
			array(
				'name' => 'device_type',
				'value' => '$data->device_type',
				'visible' => $groupColumns2['DeviceType'],
				),
			array(
				'name' => 'device',
				'visible' => $groupColumns2['DeviceBrand'],
				),
			array(
				'name' => 'device_model',
				'visible' => $groupColumns2['DeviceModel'],
				),
			array(
				'name' => 'browser',
				'visible' => $groupColumns2['Browser'],
				),
			array(
				'name' => 'browser_version',
				'visible' => $groupColumns2['BrowserVersion'],
				),
			array(
				'name' => 'carrier',
				'visible' => $groupColumns2['Carrier'],
				),	
			array(
				'name' => 'totalClicks',
				'footer' => number_format($totals['totalClicks']),
				),			
			array(
				'name' => 'totalConv',
				'footer' => number_format($totals['totalConv']),
				'visible' => $sum['Conv'],
				),	
			array(
				'name' => 'revenue',
				'visible' => $sum['Revenue'],
				'footer' => '$'.number_format($totals['revenue'],2),
				),	
			array(
				'name' => 'spend',
				'visible' => $sum['Spend'],
				'footer' => '$'.number_format($totals['spend'], 2),
				),	
			array(
				'name' => 'profit',
				'value'=> '$data->profit',
				'visible' => $sum['Profit'],
				'footer' => '$'.number_format($totals['profit'], 2),
				),																									
			)
	));

}

?>

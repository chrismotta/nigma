<?php 

// post data
$dpp       = isset($space['dpp']) ? $_REQUEST['dpp'] : '1' ;
$model->dateStart = isset($_REQUEST['dateStart']) ? $_REQUEST['dateStart'] : date("Y-m-d", strtotime("yesterday"));
$model->dateEnd = isset($_REQUEST['dateEnd']) ? $_REQUEST['dateEnd'] : date("Y-m-d", strtotime("today"));
$model->providers_id = isset($_REQUEST['ts']) ? $_REQUEST['ts'] : null;
$model->only_conversions = isset($_REQUEST['c']) ? true : false;
$partner = isset($publisher_name) ? $publisher_name : null;

$groupColumns1 = array();
$groupColumns1['TrafficSource']   		  = 1; 
$groupColumns1['TrafficSourceType']	  = 0; 
$groupColumns1['Advertiser']              = 0;
$groupColumns1['Campaign']            	  = 0;
$groupColumns1['Vector']               	  = 0;
$groupColumns1['Product']                 = 0;
$groupColumns1['Country']                 = 0;


$groupColumns2 = array();
$groupColumns2['ServerIP']               = 0;
$groupColumns2['OS']	                  = 0;
$groupColumns2['OSVersion']              = 0;
$groupColumns2['DeviceType']             = 0;
$groupColumns2['DeviceBrand']            = 0;
$groupColumns2['DeviceModel']            = 0;
$groupColumns2['Browser']                 = 0;
$groupColumns2['BrowserVersion']         = 0;
$groupColumns2['Carrier']                 = 0;


if(isset($_REQUEST['group1']))
	$groupColumns1 = $_REQUEST['group1'];
if(isset($_REQUEST['group2']))
	$groupColumns2 = $_REQUEST['group2'];

$group = array_merge($groupColumns1, $groupColumns2); 


$groupBy = '';
foreach ( $group as $property => $value )
{
	$groupBy .= '&groupBy['.$property.']='.$value;
}

$sum = array();
$sum['Conv']               = 1;
$sum['Revenue']            = 1;
$sum['Spend']              = 1;
$sum['Profit']             = 0;

if(isset($_REQUEST['sum']))
	$sum = $_REQUEST['sum'];




$filterColumns = array();
$filterColumns['provider']				   = 0; 
$filterColumns['advertiser']               = 0;
$filterColumns['country']                  = 0;
$filterColumns['campaign']           	   = 0;
$filterColumns['os_type'] 		           = 0;
$filterColumns['os_version']               = 0;
$filterColumns['device_type']              = 0;
$filterColumns['device_brand']             = 0;
$filterColumns['device_model']             = 0;
$filterColumns['browser_type']             = 0;
$filterColumns['browser_version']          = 0;
$filterColumns['carrier']                  = 0;


if ( isset($_REQUEST['filters']) )
	$filterColumns = $_REQUEST['filters'];

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
ReportingManager::addFilter($this, $filterColumns, 'filters', null, '', 'small', '', false);
echo '</div>';
echo '</div>';
echo '<div class="row-fluid" id="filters-row">';


ReportingManager::dataMultiSelect(new DSupply(), 'provider', $filterColumns);
ReportingManager::dataMultiSelect(new DDemand(), 'advertiser', $filterColumns);
ReportingManager::dataMultiSelect(new DDemand(), 'campaign', $filterColumns);
ReportingManager::dataMultiSelect(new DGeoLocation(), 'country', $filterColumns);
ReportingManager::dataMultiSelect(new DUserAgent(), 'os_type', $filterColumns);
ReportingManager::dataMultiSelect(new DUserAgent(), 'os_version', $filterColumns);
ReportingManager::dataMultiSelect(new DUserAgent(), 'device_type', $filterColumns);
ReportingManager::dataMultiSelect(new DUserAgent(), 'device_brand', $filterColumns);
ReportingManager::dataMultiSelect(new DUserAgent(), 'device_model', $filterColumns);
ReportingManager::dataMultiSelect(new DUserAgent(), 'browser_type', $filterColumns);
ReportingManager::dataMultiSelect(new DUserAgent(), 'browser_version', $filterColumns);

ReportingManager::dataMultiSelect(new DGeoLocation(), 'carrier', $filterColumns);

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
			'htmlOptions' => array('class' => 'showLoading')
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
				'onclick' => '$("#date-filter-form").attr("target", "#");$("#date-filter-form").append("<input type=\"hidden\" style=\"visibility:collapse;\" name=\"download\" value=\"true\" />");$("#date-filter-form").submit();' 
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
		
	$totals = $model->search(true, $partner);
	
	$this->widget('application.components.NiExtendedGridView', array(
		'id'              => 'clickslog-grid',
		'dataProvider'    => $model->csvReport(),
		'filter'          => null,
		'type'            => 'condensed',
		'template'        => '{items} {pagerExt} {summary}',
		'columns'         => array(
			array(
				'name' => 'ID',
				),			
			array(
				'name' => 'Click Date',
				'value' => '$data->click_date'
				),
			array(
				'name' => 'Click Time',
				),
			array(
				'name' => 'Advertiser',
				'visible' => $groupColumns1['Advertiser'],
				),
			array(
				'name' => 'Country',
				'visible' => $groupColumns1['Country'],
				),			
			array(
				'name' => 'Campaign ID',
				'value' => '$data->campaigns_id',
				//'visible' => $groupColumns1['campaigns_id'],
				),			
			array(
				'name' => 'Campaign Name',
				'value' => '$data->campaigns_name',
				//'visible' => $groupColumns1['campaigns_name'],
				),
			array(
				'name' => 'Vectors Id',
				//'visible' => $groupColumns1['vectors_id'],
				),					
			array(
				'name' => 'conv_date',
				//'visible' => $groupColumns1['conv_date'],
				),									
			array(
				'name' => 'conv_time',
				//'visible' => $groupColumns1['conv_time'],
				),				
			array(
				'name' => 'Traffic Source',
				'visible' => $groupColumns1['Traffic Source'],
				),
			array(
				'name' => 'Traffic Source Type',
				'visible' => $groupColumns1['Traffic Source'],
				),						
			array(
				'name' => 'os',
				'visible' => $groupColumns2['Os'],
				),
			array(
				'name' => 'OS Version',
				'visible' => $groupColumns2['os_version'],
				),
			array(
				'name' => 'device_type',
				'visible' => $groupColumns2['device_type'],
				),
			array(
				'name' => 'device',
				'visible' => $groupColumns2['device'],
				),
			array(
				'name' => 'device_model',
				'visible' => $groupColumns2['device_model'],
				),
			array(
				'name' => 'browser',
				'visible' => $groupColumns2['Browser'],
				),
			array(
				'name' => 'browser_version',
				'visible' => $groupColumns2['browser_version'],
				),
			array(
				'name' => 'carrier',
				'visible' => $groupColumns2['carrier'],
				),									
			)
	));

}

?>
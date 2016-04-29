<?php 

// post data

$dpp       = isset($space['dpp']) ? $_REQUEST['dpp'] : '1' ;
$dateStart = isset($_REQUEST['dateStart']) ? $_REQUEST['dateStart'] : 'today' ;
$dateEnd   = isset($_REQUEST['dateEnd']) ? $_REQUEST['dateEnd'] : 'today';

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


// ----- Columns

$groupColumns1 = array(
		'date'           =>1, 
		'hour'           =>0, 
		'advertiser'     =>1, 
		'campaign'       =>0, 
		'tag'            =>0,
		'provider'       =>1,
		'placement'      =>0, 
		'pubid'          =>0, 
		// geo
		'connection_type' =>0,
		'country'        =>0, 
		'carrier'        =>0,
		);
$groupColumns2 = array(
		// user_agent
		'device_type'     =>0,
		'device_brand'    =>0,
		'device_model'    =>0,
		'os_type'         =>0,
		'os_version'      =>0,
		'browser_type'    =>0,
		'browser_version' =>0,
		);

if(isset($_REQUEST['group1']))
	$groupColumns1 = $_REQUEST['group1'];
if(isset($_REQUEST['group2']))
	$groupColumns2 = $_REQUEST['group2'];

$sumColumns = array(
		'impressions'  =>1, 
		'unique_user'  =>1, 
		'revenue'      =>1, 
		'cost'         =>1, 
		'profit'       =>1, 
		'revenue_eCPM' =>0, 
		'cost_eCPM'    =>0, 
		'profit_eCPM'  =>0, 
		);

if(isset($_REQUEST['sum']))
	$sumColumns = $_REQUEST['sum'];


// ----- Groups

echo '<div class="row-fluid">';
echo '<div class="form-sep span12">GROUP COLUMNS</div>';
echo '</div>';

echo '<div class="row-fluid">';
echo '<div class="span12">';
echo '<div>';
ReportingManager::groupFilter($this, $groupColumns1, 'group1', null, '', 'small', 'info', false);
echo '</div>';
echo '</div>';
echo '</div>';
echo '<div class="row-fluid">';
echo '<div class="span12">';
echo '<div>';
ReportingManager::groupFilter($this, $groupColumns2, 'group2', null, '', 'small', 'info', false);
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
ReportingManager::groupFilter($this, $sumColumns, 'sum', null, '', 'small', 'inverse', false);
echo '</div>';

echo '</div>';
echo '</div>';


// ----- Filters

$filterColumns = array(
		'advertiser'      =>0, 
		'campaign'        =>0, 
		'tag'             =>0, 
		'provider'        =>0, 
		'placement'       =>0, 
		// geo
		'connection_type' =>0, 
		'country'         =>0, 
		'carrier'         =>0,
		// user_agent
		'device_type'     =>0,
		'device_brand'    =>0,
		'device_model'    =>0,
		'os_type'         =>0,
		'os_version'      =>0,
		'browser_type'    =>0,
		'browser_version' =>0,
		);

echo '<div class="row-fluid">';
echo '<div class="form-sep span12">FILTERS</div>';
echo '</div>';

echo '<div class="row-fluid">';
echo '<div>';
ReportingManager::addFilter($this, $filterColumns, 'g', null, '', 'small', '', false);
echo '</div>';
echo '</div>';
echo '<div class="row-fluid" id="filters-row">';


$value = array();

ReportingManager::dataMultiSelect(new DDemand(), 'advertiser');
ReportingManager::dataMultiSelect(new DSupply(), 'provider');
ReportingManager::dataMultiSelect(new DGeoLocation(), 'connection_type');
ReportingManager::dataMultiSelect(new DGeoLocation(), 'country');
ReportingManager::dataMultiSelect(new DGeoLocation(), 'carrier');
ReportingManager::dataMultiSelect(new DUserAgent(), 'device_type');
ReportingManager::dataMultiSelect(new DUserAgent(), 'device_brand');
ReportingManager::dataMultiSelect(new DUserAgent(), 'device_model');
ReportingManager::dataMultiSelect(new DUserAgent(), 'os_type');
ReportingManager::dataMultiSelect(new DUserAgent(), 'os_version');
ReportingManager::dataMultiSelect(new DUserAgent(), 'browser_type');
ReportingManager::dataMultiSelect(new DUserAgent(), 'browser_version');

// hide all .multi-select-hide
Yii::app()->clientScript->registerScript('hide', '$(".multi-select-hide").hide();', 4);

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

	$this->widget('bootstrap.widgets.TbButton', 
		array(
			'buttonType'=>'submit', 
			'label'=>'Download CSV', 
			'type' => 'warning', 
			'htmlOptions' => array('class' => 'showLoading')
			)
		); 

	// echo $space;

	// $this->widget('bootstrap.widgets.TbButton', 
	// 	array(
	// 		'buttonType'=>'reset', 
	// 		'label'=>'Reset', 
	// 		'htmlOptions' => array('class' => 'showLoading')
	// 		)
	// 	); 
	
echo CHtml::endForm();
// $this->endWidget(); 


echo '</div>';

if(count($_REQUEST)>0){

	/* JSON
	echo '<div class="row-fluid" style="word-wrap: break-word;">';
	echo '<hr>';
	echo json_encode($_REQUEST);
	echo '<hr>';
	echo '</div>';
	*/

	$this->widget('application.components.NiExtendedGridView', array(
		'id'              => 'imp-log-grid',
		'dataProvider'    => $model->search(),
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
				'name' => 'advertiser',
				'visible' => $groupColumns1['advertiser'],
				),
			array(
				'name' => 'campaign',
				'visible' => $groupColumns1['campaign'],
				),
			array(
				'name' => 'tag',
				'visible' => $groupColumns1['tag'],
				),
			array(
				'name' => 'provider',
				'visible' => $groupColumns1['provider'],
				),
			array(
				'name' => 'placement',
				'visible' => $groupColumns1['placement'],
				),
			array(
				'name' => 'pubid',
				'visible' => $groupColumns1['pubid'],
				),
			array(
				'name' => 'connection_type',
				'visible' => $groupColumns1['connection_type'],
				),
			array(
				'name' => 'country',
				'visible' => $groupColumns1['country'],
				),
			array(
				'name' => 'carrier',
				'visible' => $groupColumns1['carrier'],
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
				'name' => 'os_type',
				'visible' => $groupColumns2['os_type'],
				),
			array(
				'name' => 'os_version',
				'visible' => $groupColumns2['os_version'],
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
				'name' => 'impressions',
				'visible' => $sumColumns['impressions'],
				),
			array(
				'name' => 'unique_user',
				'visible' => $sumColumns['unique_user'],
				),
			array(
				'name' => 'revenue',
				'visible' => $sumColumns['revenue'],
				),
			array(
				'name' => 'cost',
				'visible' => $sumColumns['cost'],
				),
			array(
				'name' => 'profit',
				'visible' => $sumColumns['profit'],
				),
			array(
				'name' => 'revenue_eCPM',
				'visible' => $sumColumns['revenue_eCPM'],
				),
			array(
				'name' => 'cost_eCPM',
				'visible' => $sumColumns['cost_eCPM'],
				),
			array(
				'name' => 'profit_eCPM',
				'visible' => $sumColumns['profit_eCPM'],
				),
			)
	));

}

?>
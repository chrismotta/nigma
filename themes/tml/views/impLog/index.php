<?php
/* @var $this ImpLogController */
/* @var $model ImpLog */

// post data

$dpp       = isset($space['dpp']) ? $_REQUEST['dpp'] : '5' ;
$dateStart = isset($_REQUEST['dateStart']) ? $_REQUEST['dateStart'] : 'today -7 days' ;
$dateEnd   = isset($_REQUEST['dateEnd']) ? $_REQUEST['dateEnd'] : 'yesterday';

$space = "<span class='formfilter-space'></span>";

// breadcrumb

$this->breadcrumbs=array(
	'Impressions Report',
);

// $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
// 		'id'                   =>'filter-form',
// 		'type'                 =>'search',
// 		'htmlOptions'          =>array('class'=>'well'),
// 		'enableAjaxValidation' =>false,
// 		'method'               => 'POST',
// 		'action'               => Yii::app()->getBaseUrl() . '/impLog/',
//     ));

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

$groupColumns = array(
		'Date'           =>1, 
		'Time'           =>0, 
		'Advertiser'     =>1, 
		'Tag'			 =>0,
		'TrafficSource'  =>0,
		'Placement'		 =>0, 
		'PubID'  		 =>0, 
		// geo
		'Country'        =>1, 
		'Carrier'        =>0,
		// user_agent
		'DeviceType'     =>1,
		'DeviceBrand'    =>0,
		'DeviceModel'    =>0,
		'OS'             =>1,
		'OSVersion'      =>1,
		'Browser'        =>0,
		'BrowserVersion' =>0,
		);

if(isset($_REQUEST['group']))
	$groupColumns = $_REQUEST['group'];

$sumColumns = array(
		'Imp'           =>1, 
		'UniqueUsr'     =>1, 
		'Revenue'       =>1, 
		'Spend'         =>1, 
		'Profit'        =>1, 
		'Revenue_eCPM'  =>0, 
		'Cost_eCPM'     =>0, 
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
KHtml::groupFilter($this, $groupColumns, 'group', null, '', 'small', 'info', false);
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
KHtml::groupFilter($this, $sumColumns, 'sum', null, '', 'small', 'inverse', false);
echo '</div>';

echo '</div>';
echo '</div>';


// ----- Filters

$filterColumns = array(
		'advertiser'     =>0, 
		'trafficSource'  =>0, 
		// geo
		'country'        =>0, 
		'carrier'        =>0,
		// user_agent
		'deviceType'     =>0,
		'deviceBrand'    =>0,
		'deviceModel'    =>0,
		'os'             =>0,
		'osVersion'      =>0,
		'browser'        =>0,
		'browserVersion' =>0,
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

	$criteria = new CDbCriteria;
	$criteria->order = 'name';
	$criteria->compare('status', 'Active');
	$data = CHtml::listData(
        Advertisers::model()->findAll($criteria), 'id', 'name');
	ReportingManager::multiSelect(
		array(
            'name'        => 'advertiser',
            'data'        => $data,
            'value'       => $value,
			), array(), true);


	$criteria = new CDbCriteria;
	$criteria->order = 'name';
	$criteria->compare('status', 'Active');
	$data = CHtml::listData(
        Providers::model()->findAll($criteria), 'id', 'name');
	ReportingManager::multiSelect(
		array(
            'name'        => 'trafficSource',
            'data'        => $data,
            'value'       => $value,
			), array(), true);


	$criteria = new CDbCriteria;
	$criteria->order = 'name';
	$criteria->compare('type', 'Country');
	$data = CHtml::listData(
        GeoLocation::model()->findAll($criteria), 'ISO2', 'name');
	ReportingManager::multiSelect(
		array(
            'name'        => 'country',
            'data'        => $data,
            'value'       => $value,
			), array(), true);


	ReportingManager::multiSelect(
		array(
            'name'        => 'carrier',
            'data'        => $model->selectDistinct('carrier'),
            'value'       => $value,
			), array(), true);


	ReportingManager::multiSelect(
		array(
            'name'        => 'deviceType',
            'data'        => $model->selectDistinct('device_type'),
            'value'       => $value,
			), array(), true);


	ReportingManager::multiSelect(
		array(
            'name'        => 'deviceBrand',
            'data'        => $model->selectDistinct('device'),
            'value'       => $value,
			), array(), true);


	ReportingManager::multiSelect(
		array(
            'name'        => 'deviceModel',
            'data'        => $model->selectDistinct('device_model'),
            'value'       => $value,
			), array(), true);

	ReportingManager::multiSelect(
		array(
            'name'        => 'os',
            'data'        => $model->selectDistinct('os'),
            'value'       => $value,
			), array(), true);

	ReportingManager::multiSelect(
		array(
            'name'        => 'osVersion',
            'data'        => $model->selectDistinct('os_version'),
            'value'       => $value,
			), array(), true);

	ReportingManager::multiSelect(
		array(
            'name'        => 'browser',
            'data'        => $model->selectDistinct('browser'),
            'value'       => $value,
			), array(), true);

	ReportingManager::multiSelect(
		array(
            'name'        => 'browserVersion',
            'data'        => $model->selectDistinct('browser_version'),
            'value'       => $value,
			), array(), true);

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

	echo '<div class="row-fluid" style="word-wrap: break-word;">';
	echo '<hr>';

		echo json_encode($_REQUEST);

	echo '<hr>';
	echo '</div>';
	// echo '<br>';

	// report result
	
	
	$this->widget('application.components.NiExtendedGridView', array(
		'id'              => 'imp-log-grid',
		'dataProvider'    => $model->search(),
		'filter'          => null,
		'type'            => 'condensed',
	    //
	    // 'ajaxUrl' => array($dp->pagination->route),
		'ajaxUpdateError'=>'function(xhr,ts,et,err,id){ console.log("Error: "+xhr+ts+et+err+id); }',
		//
		'template'        => '{items} {pagerExt} {summary}',
		'columns'         => array(
			array(
				'name' => 'date',
				'visible' => $groupColumns['Date'],
				),
			array(
				'name' => 'time',
				'visible' => $groupColumns['Time'],
				),
			array(
				'name' => 'advertiser',
				'visible' => $groupColumns['Advertiser'],
				),
			array(
				'name' => 'tags_id',
				'visible' => $groupColumns['Tag'],
				),
			array(
				'name' => 'traffic_source',
				'visible' => $groupColumns['TrafficSource'],
				),
			array(
				'name' => 'placements_id',
				'visible' => $groupColumns['Placement'],
				),
			array(
				'name' => 'pubid',
				'visible' => $groupColumns['PubID'],
				),
			array(
				'name' => 'country',
				'visible' => $groupColumns['Country'],
				),
			array(
				'name' => 'carrier',
				'visible' => $groupColumns['Carrier'],
				),
			array(
				'name' => 'device_type',
				'visible' => $groupColumns['DeviceType'],
				),
			array(
				'name' => 'device',
				'visible' => $groupColumns['DeviceBrand'],
				),
			array(
				'name' => 'device_model',
				'visible' => $groupColumns['DeviceModel'],
				),
			array(
				'name' => 'os',
				'visible' => $groupColumns['OS'],
				),
			array(
				'name' => 'os_version',
				'visible' => $groupColumns['OSVersion'],
				),
			array(
				'name' => 'browser',
				'visible' => $groupColumns['Browser'],
				),
			array(
				'name' => 'browser_version',
				'visible' => $groupColumns['BrowserVersion'],
				),
			array(
				'name' => 'imp',
				'visible' => $sumColumns['Imp'],
				),
			array(
				'name' => 'unique_usr',
				'visible' => $sumColumns['UniqueUsr'],
				),
			array(
				'name' => 'revenue',
				'visible' => $sumColumns['Revenue'],
				),
			array(
				'name' => 'spend',
				'visible' => $sumColumns['Spend'],
				),
			array(
				'name' => 'profit',
				'visible' => $sumColumns['Profit'],
				),
			array(
				'name' => 'reCPM',
				'visible' => $sumColumns['Revenue_eCPM'],
				),
			array(
				'name' => 'ceCPM',
				'visible' => $sumColumns['Cost_eCPM'],
				),
		)
	));
}

?>
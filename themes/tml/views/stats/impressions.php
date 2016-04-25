<?php 

// post data

$dpp       = isset($space['dpp']) ? $_REQUEST['dpp'] : '5' ;
$dateStart = isset($_REQUEST['dateStart']) ? $_REQUEST['dateStart'] : 'today -7 days' ;
$dateEnd   = isset($_REQUEST['dateEnd']) ? $_REQUEST['dateEnd'] : 'yesterday';

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

$groupColumns = array(
		'date'           =>1, 
		'time'           =>0, 
		'advertiser'     =>1, 
		'tag'            =>0,
		'trafficSource'  =>0,
		'placement'      =>0, 
		'pubid'          =>0, 
		// geo
		'country'        =>1, 
		'carrier'        =>0,
		// user_agent
		'deviceType'     =>1,
		'deviceBrand'    =>0,
		'deviceModel'    =>0,
		'osType'         =>1,
		'osVersion'      =>1,
		'browserType'    =>0,
		'browserVersion' =>0,
		);

if(isset($_REQUEST['group']))
	$groupColumns = $_REQUEST['group'];

$sumColumns = array(
		'impressions'  =>1, 
		'uniqueUsr'    =>1, 
		'revenue'      =>1, 
		'cost'         =>1, 
		'profit'       =>1, 
		'revenue_eCPM' =>0, 
		'cost_eCPM'    =>0, 
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
		'osType'         =>0,
		'osVersion'      =>0,
		'browserType'    =>0,
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
/*	
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
*/
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


	$this->widget('application.components.NiExtendedGridView', array(
		'id'              => 'imp-log-grid',
		'dataProvider'    => $model->search(),
		'filter'          => null,
		'type'            => 'condensed',
		'template'        => '{items} {pagerExt} {summary}',
		'columns'         => array(
			array(
				'name' => 'date',
				'visible' => $groupColumns['date'],
				),
			array(
				'name' => 'time',
				'visible' => $groupColumns['time'],
				),
			array(
				'name' => 'advertiser',
				'visible' => $groupColumns['advertiser'],
				),
			array(
				'name' => 'tag',
				'visible' => $groupColumns['tag'],
				),
			array(
				'name' => 'trafficSource',
				'visible' => $groupColumns['trafficSource'],
				),
			array(
				'name' => 'placements',
				'visible' => $groupColumns['placement'],
				),
			array(
				'name' => 'pubid',
				'visible' => $groupColumns['pubid'],
				),
			array(
				'name' => 'country',
				'visible' => $groupColumns['country'],
				),
			array(
				'name' => 'carrier',
				'visible' => $groupColumns['carrier'],
				),
			array(
				'name' => 'deviceType',
				'visible' => $groupColumns['deviceType'],
				),
			array(
				'name' => 'deviceBrand',
				'visible' => $groupColumns['deviceBrand'],
				),
			array(
				'name' => 'deviceModel',
				'visible' => $groupColumns['deviceModel'],
				),
			array(
				'name' => 'osType',
				'visible' => $groupColumns['osType'],
				),
			array(
				'name' => 'osVersion',
				'visible' => $groupColumns['osVersion'],
				),
			array(
				'name' => 'browserType',
				'visible' => $groupColumns['browserType'],
				),
			array(
				'name' => 'browserVersion',
				'visible' => $groupColumns['browserVersion'],
				),
			array(
				'name' => 'impressions',
				'visible' => $sumColumns['impressions'],
				),
			array(
				'name' => 'uniqueUsr',
				'visible' => $sumColumns['uniqueUsr'],
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
			)
	));

}

?>
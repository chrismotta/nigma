<?php
/* @var $this CampaignsController */
/* @var $model Campaigns */

$name = 'KickAds-Report-' . date( 'd-m-Y', strtotime($model->date) ) . '.xls';

$this->widget('EExcelWriter', array(
    'dataProvider' => $model->excel(),
    'title'        => 'EExcelWriter',
    'stream'       => TRUE,
    'fileName'     => $name,
    'columns'      => array(
    	// array(
    	// 	'name' => 'Click Id',
    	// 	'value' => '$data->clicksLog->id'
    	// ),
        array(
        	'name' => 'Advertisers Name',
        	'value' => '$data->campaign->opportunities->ios->advertisers->name',
        ),
        array(
        	'name' => 'Campaign Name',
        	'value' => '$data->campaign->name',
        ),
        'date',
        array(
        	'name' => 'Click Date',
        	'value' => '$data->clicksLog->date',
        ),
        array(
        	'name' => 'Network',
        	'value' => '$data->clicksLog->networks->name',
        ),
        array(
        	'name' => 'Banner Size',
        	'value' => '$data->campaign->bannerSizes ? $data->campaign->bannerSizes->size : ""',
        ),
        array(
        	'name' => 'IP',
        	'value' => '$data->clicksLog->ip_forwarded ? $data->clicksLog->ip_forwarded : $data->clicksLog->server_ip',
        ),
        array(
        	'name' => 'Referer',
        	'value' => '$data->clicksLog->referer',
        ),
        array(
        	'name' => 'Country',
        	'value' => '$data->clicksLog->country',
        ),
        array(
        	'name' => 'City',
        	'value' => '$data->clicksLog->city',
        ),
        array(
        	'name' => 'Carrier',
        	'value' => '$data->clicksLog->carrier',
        ),
        array(
        	'name' => 'Device',
        	'value' => '$data->clicksLog->device',
        ),
        array(
        	'name' => 'OS',
        	'value' => '$data->clicksLog->os',
        ),
        array(
        	'name' => 'App',
        	'value' => '$data->clicksLog->app',
        ),
    ),
));

unlink($name);

?>
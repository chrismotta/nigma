<?php 
/* @var $this DailyReportController */
/* @var $model DailyReport */

set_time_limit(1000);

$path = 'uploads/';
$name = 'KickAds-DailyReport.xls';

$dateStart = isset($_POST['excel-dateStart']) ? $_POST['excel-dateStart'] : 'yesterday' ;
$dateEnd   = isset($_POST['excel-dateEnd']) ? $_POST['excel-dateEnd'] : 'yesterday';
$sum       = isset($_POST['sum']) ? $_POST['sum'] : '0';

$dateStart = date('Y-m-d', strtotime($dateStart));
$dateEnd = date('Y-m-d', strtotime($dateEnd));

$this->widget('EExcelWriter', array(
    'dataProvider' => $model->excel($dateStart, $dateEnd, $sum),
    'title'        => 'EExcelWriter',
    'stream'       => TRUE,
    'fileName'     => $name,
    'filePath'     => $path,
    'columns'      => array(
        array(
            'name'  => 'account_manager',
            'value' => '$data->campaigns->opportunities->accountManager->lastname . " " . $data->campaigns->opportunities->accountManager->name',
        ),
        array(
            'name'  => 'campaign_name',
            'value' => 'Campaigns::model()->getExternalName($data->campaigns_id)',
        ),
        array(
            'name'  => 'format',
            'value' => '$data->campaigns->formats->name',
        ),
        array(
            'name'  => 'opportunitie_name',
            'value' => '$data->campaigns->opportunities->getVirtualName()',
        ),        
        array(
            'name'  => 'io_name',
            'value' => '$data->campaigns->opportunities->ios->name',
        ),
        array(
            'name'  => 'country',
            'value' => '$data->campaigns->opportunities->country->name',
        ),
        array(
            'name'  => 'entity_name',
            'value' => '$data->campaigns->opportunities->ios->entity',
        ),
        array(
            'name'  => 'category_name',
            'value' => '$data->campaigns->opportunities->ios->advertisers->cat',
        ),
        array(
            'name'  => 'network_name',
            'value' => '$data->networks->name',
        ),
        array(
            'header'  => 'rate',
            'value' => '$data->getRateUSD() ? $data->getRateUSD() : 0',
            'htmlOptions'=>array('style'=>'width: 45px'),
        ),
        'imp',
        'imp_adv',
        'clics',        
        array(
            'name'  => 'clics_redirect',
            'value' => 'Campaigns::model()->getClicksRedirect($data->date, $data->date, $data->campaigns_id)',
        ),
        'conv_api',
        'conv_adv',
        array(
            'name'  =>  'Consolidated',
            'value' =>  '$data->getConv()',
        ),
        array(
            'name'  =>  'spend',
            'value' =>  '$data->getSpendUSD()',
        ),
        array(
            'name'  => 'revenue',
            'value' => '$data->getRevenueUSD()',
        ),
        array(
            'name'  => 'profit',
            'value' =>  '$data->profit',
        ),
        array(
            'name'  => 'profit_percent',
            'value' => '$data->profit_percent * 100',
        ),
        array(
            'name'  => 'click_through_rate',
            'value' => '$data->click_through_rate * 100',
        ),
        array(
            'name'  => 'conversion_rate',
            'value' => '$data->conversion_rate * 100',
        ),
        array(
            'name'  => 'eCPM',
            'value' => '$data->eCPM',
        ),
        array(
            'name'  => 'eCPC',
            'value' => '$data->eCPC',
        ),
        array(
            'name'  => 'eCPA',
            'value' => '$data->eCPA',
        ),
        array(
            'name'  => 'date',
            'value' => 'date("d-m-Y", strtotime($data->date))',
        ),
        array(
            'name'  => 'CAP',
            'value' => '$data->getCapStatus() ? "Exceeded" : ""',
        ),
    ),
));

unlink($path . $name);

?>
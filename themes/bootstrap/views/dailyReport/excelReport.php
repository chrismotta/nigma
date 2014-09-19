<?php 
/* @var $this DailyReportController */
/* @var $model DailyReport */

$path = 'uploads/';
$name = 'KickAds-DailyReport.xls';

$dateStart = isset($_POST['excel-dateStart']) ? $_POST['excel-dateStart'] : 'yesterday' ;
$dateEnd   = isset($_POST['excel-dateEnd']) ? $_POST['excel-dateEnd'] : 'yesterday';

$dateStart = date('Y-m-d', strtotime($dateStart));
$dateEnd = date('Y-m-d', strtotime($dateEnd));

$this->widget('EExcelWriter', array(
    'dataProvider' => $model->excel($dateStart, $dateEnd),
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
            'name'  => 'opportunitie_name',
            'value' => '$data->campaigns->opportunities->getVirtualName()',
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
        'imp',
        'imp_adv',
        'clics',
        'conv_api',
        'conv_adv',
        array(
            'name'  =>  'spend',
            'value' =>  '$data->getSpendUSD()',
        ),
        array(
            'name'  => 'revenue',
            'value' => '$data->getRevenueUSD()',
        ),
        array(
            'header'  => 'Profit',
            'value' =>  '$data->getProfit()',
        ),        
        array(
            'header'  => 'Profit Perc',
            'value' => '$data->getProfitPerc() * 100 . "%"',
            'htmlOptions'=>array('style'=>'width: 30px'),
        ),
        array(
            'header'  => 'Click Rate',
            'value' => '$data->getCtr() * 100',
        ),
        array(
            'header'  => 'Conv Rate',
            'value' => '$data->getConvRate() * 100',
        ),
        array(
            'header'  => 'Profit Perc',
            'value' => '$data->getProfitPerc() * 100',
        ),
        array(
            'header'  => 'eCPM',
            'value' => '$data->getECPM()',
        ),
        array(
            'header'  => 'eCPC',
            'value' => '$data->getECPC()',
        ),
        array(
            'header'  => 'eCPA',
            'value' => '$data->getECPA()',
        ),
        array(
            'name'  => 'date',
            'value' => 'date("d-m-Y", strtotime($data->date))',
        ),
    ),
));

unlink($path . $name);

?>
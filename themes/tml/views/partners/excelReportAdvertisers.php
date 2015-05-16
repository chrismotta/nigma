<?php 
/* @var $this FinanceController */
/* @var $model Finance */

$path         = 'uploads/';
$name         = 'TheMediaLab-Clients.xls';
// $year         =isset($_POST['year']) ? $_POST['year'] : date('Y', strtotime('today'));
// $month        =isset($_POST['month']) ? $_POST['month'] : date('m', strtotime('today'));
// $entity       =isset($_POST['entity']) ? $_POST['entity'] : null;
// $cat          =isset($_POST['cat']) ? $_POST['cat'] : null;
// $status       =isset($_POST['status']) ? $_POST['status'] : null;
// $clients      =Ios::getClients($month,$year,$entity,null,null,null,$cat,$status,null);

// $dataProvider =new CArrayDataProvider($clients['data'], array(
//     'id'=>'clients',
//     'pagination'=>array(
//         'pageSize'=>30,
//     ),
// ));
$this->widget('EExcelWriter', array(
    'dataProvider' => $dataProvider,
    'title'        => 'EExcelWriter',
    'stream'       => TRUE,
    'fileName'     => $name,
    'filePath'     => $path,
    'columns'                  => array(
        array(
            'name'              => 'date',
            'value'             => 'date("d-m-Y", strtotime($data->date))',            
        ),
        array(
            'name'  => 'product',
            'value' => '$data->campaigns->opportunities->product',
        ),
        array(
            'name'  => 'country',
            'value' => '$data->campaigns->opportunities->regions->country_id ? 
                        $data->campaigns->opportunities->regions->country->name:
                        $data->campaigns->opportunities->regions->region',
        ),
        array(
            'name'  => 'carrier',
            'value' => '$data->campaigns->opportunities->carriers_id ?
                        $data->campaigns->opportunities->carriers->mobile_brand:
                        "no carrier"',
        ),
        array(  
            'name'              => 'imp',
        ),
        array(
            'name'              => 'clics',
        ),
        array(
            'name'              => 'conv_api',
        ),        
        array(
            'name'              => 'revenue',
            'value'             => '"\$ ".number_format($data->getRevenueUSD(), 2)',
        ),
        array(
            'name'              => 'click_through_rate',
            'value'             => $sum ? 'number_format($data->getCtr()*100, 2)."%"' : 'number_format($data->click_through_rate*100, 2)."%"', // FIX for sum feature
        ),
        array(
            'name'              => 'conversion_rate',
            'value'             => $sum ? 'number_format($data->getConvRate()*100, 2)."%"' : 'number_format($data->conversion_rate*100, 2)."%"', // FIX for sum feature
        ),
        array(
            'name'              => 'eCPM',            
            'value'             => $sum ? 'number_format($data->getECPM(), 2)' : '$data->eCPM', // FIX for sum feature
        ),
        array(
            'name'              => 'eCPC',
            'value'             => $sum ? 'number_format($data->getECPC(), 2)' : '$data->eCPC', // FIX for sum feature
        ),
        array(
            'name'              => 'eCPA',
            'value'             => $sum ? 'number_format($data->getECPA(), 2)' : '$data->eCPA', // FIX for sum feature
        ),
    ),
));

unlink($path . $name);

?>
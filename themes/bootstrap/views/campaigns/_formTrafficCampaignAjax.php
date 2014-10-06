<?php
/* @var $this CampaignsController
 * @var $model Campaigns 
 * @var $form CActiveForm 
 */
?>

<div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h4>Campaign Traffic</h4>
</div>
<div class="modal-body">
    <div class="row">
    <div id="container-highchart" class="span12">
     <?php 
     $data=array();
    $criteria=new CDbCriteria;
    $criteria->select='count(*) as clics, country';
    $criteria->addCondition("DATE(date)>='".$dateStart."' AND DATE(date)<='".$dateEnd."' AND campaigns_id=".$model->id);
    $criteria->group='country';
    $clicksLogs = ClicksLog::model()->findAll($criteria);
    foreach ($clicksLogs as $log) {
        if(strlen($log->country)==2)
        $data[]=array('hc-key' => strtolower($log->country), 'value' => $log->clics);
    }
    $this->widget('ext.highcharts.HighmapsWidget', array(
    'id'=>'asd',
    'options' => array(
        'title' => array(
            'text' => 'Highmaps basic demo',
        ),
        'mapNavigation' => array(
            'enabled' => true,
            'buttonOptions' => array(
                'verticalAlign' => 'bottom',
            )
        ),
        'colorAxis' => array(
            'min' => 0,
        ),
        'series' => array(
            array(
                'data' => $data,
                'mapData' => 'js:Highcharts.maps["custom/world"]',
                'joinBy' => 'hc-key',
                'name' => 'Random data',
                'states' => array(
                    'hover' => array(
                        'color' => '#BADA55',
                    )
                ),
                'dataLabels' => array(
                    'enabled' => true,
                    'format' => '{point.name}',
                )
            )
        )
    )
));
 Yii::app()->clientScript->registerScriptFile('//code.highcharts.com/mapdata/custom/world.js');
 
 ?>
    </div>
</div>
</div>

<div class="modal-footer">
    Edit campaign attributes. Fields with <span class="required">*</span> are required.
</div>

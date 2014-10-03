<?php
/* @var $this CampaignsController
 * @var $model Campaigns 
 * @var $form CActiveForm 
 */
?>
<?php $totalsGrap=Campaigns::model()->totalsTraffic($dateStart,$dateEnd,$model->id); 

?>
<div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h4>Campaign Traffic</h4>
</div>
<div class="modal-body">
<div class="row">
    <div id="container-highchart" class="span12">
         <?php 
        
// $this->widget('ext.highcharts.HighmapsWidget', array(
//     'id'=>'asd',
//     'options' => array(
//         'title' => array(
//             'text' => 'Highmaps basic demo',
//         ),
//         'mapNavigation' => array(
//             'enabled' => true,
//             'buttonOptions' => array(
//                 'verticalAlign' => 'bottom',
//             )
//         ),
//         'colorAxis' => array(
//             'min' => 0,
//         ),
//         'series' => array(
//             array(
//                 'data' => array(
//                     array('hc-key' => 'ni', 'value' => 0),
//                     array('hc-key' => 'hb', 'value' => 1),
//                     array('hc-key' => 'sh', 'value' => 2),
//                     array('hc-key' => 'be', 'value' => 3),
//                     array('hc-key' => 'mv', 'value' => 4),
//                     array('hc-key' => 'hh', 'value' => 5),
//                     array('hc-key' => 'rp', 'value' => 6),
//                     array('hc-key' => 'sl', 'value' => 7),
//                     array('hc-key' => 'by', 'value' => 8),
//                     array('hc-key' => 'th', 'value' => 9),
//                     array('hc-key' => 'st', 'value' => 10),
//                     array('hc-key' => 'sn', 'value' => 11),
//                     array('hc-key' => 'br', 'value' => 12),
//                     array('hc-key' => 'nw', 'value' => 13),
//                     array('hc-key' => 'bw', 'value' => 14),
//                     array('hc-key' => 'he', 'value' => 15),
//                 ),
//                 'mapData' => 'js:Highcharts.maps["custom/world"]',
//                 'joinBy' => 'hc-key',
//                 'name' => 'Random data',
//                 'states' => array(
//                     'hover' => array(
//                         'color' => '#BADA55',
//                     )
//                 ),
//                 'dataLabels' => array(
//                     'enabled' => true,
//                     'format' => '{point.name}',
//                 )
//             )
//         )
//     )
// ));
//  Yii::app()->clientScript->registerScriptFile('//code.highcharts.com/mapdata/custom/world.js');

         
    $this->Widget('ext.highcharts.HighchartsWidget', array(
        'id' => 'hig2',
        'options'=>array(
            'chart' => array('type' => 'area'),
            'title' => array('text' => ''),
            'xAxis' => array(
                'categories' => $totalsGrap['dates'],
                ),
            'tooltip' => array('crosshairs'=>'true', 'shared'=>'true'),
            'yAxis' => array(
                'title' => array('text' => '')
                ),
            'series' => array(
                array('name' => 'Clicks', 'data' => $totalsGrap['clics']),
                array('name' => 'Conversions', 'data' => $totalsGrap['conversions']),
                ),
            'legend' => array(
                'layout' => 'vertical',
                'align' =>  'left',
                'verticalAlign' =>  'top',
                'x' =>  40,
                'y' =>  3,
                'floating' =>  true,
                'borderWidth' =>  1,
                'backgroundColor' => '#FFFFFF'
                )
            ),
        )
    );
    
    ?>
    </div>
</div>
<hr>
    

    <?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id'=>'traffic-campaign-form',
        'type'=>'horizontal',
        'htmlOptions'=>array('class'=>'well'),
        // to enable ajax validation
        'enableAjaxValidation'=>true,
        'clientOptions'=>array('validateOnSubmit'=>true, 'validateOnChange'=>true),
    )); ?>
    <fieldset>
       From:
    <div class="input-append">
        <?php 
            $this->widget('bootstrap.widgets.TbDatePicker',array(
            'name'  => 'dateStart1',
            'value' => date('d-m-Y', strtotime($dateStart)),
            'htmlOptions' => array(
                'style' => 'width: 80px',
            ),
            'options' => array(
                'autoclose'      => true,
                'format'         => 'dd-mm-yyyy',
                'viewformat'     => 'dd-mm-yyyy',
                'placement'      => 'right',
            ),
        ));
        ?>
        <span class="add-on"><i class="icon-calendar"></i></span>
    </div>
    To:
    <div class="input-append">
        <?php 
            $this->widget('bootstrap.widgets.TbDatePicker',array(
            'name'        => 'dateEnd1',
            'value'       => date('d-m-Y', strtotime($dateEnd)),
            'htmlOptions' => array(
                'style' => 'width: 80px',
            ),
            'options'     => array(
                'autoclose'      => true,
                'format'         => 'dd-mm-yyyy',
                'viewformat'     => 'dd-mm-yyyy',
                'placement'      => 'right',
            ),
        ));
        ?>
        <span class="add-on"><i class="icon-calendar"></i></span>
    </div>
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'label'=>'Filter')); ?>
    </fieldset>
    <?php $this->endWidget(); ?>
</div>

<div class="modal-footer">
    Edit campaign attributes. Fields with <span class="required">*</span> are required.
</div>

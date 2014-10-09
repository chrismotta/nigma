<?php
/* @var $this CampaignsController */
/* @var $dataProvider CActiveDataProvider */
$this->pageTitle=Yii::app()->name;
$this->breadcrumbs=array(
    'Campaigns'=>array('index'),
    'Traffic'=>array('traffic'),
    'View Campaign',
);

$dateStart=$_GET['dateStart'];
$dateEnd=$_GET['dateEnd'];
?>


<div class="row" id="top">
    <div class="span12">
        <h4>Campaign: <?php echo Campaigns::model()->getExternalName($_GET['id']); ?></h4>

        <br>
<?php 

    $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id'=>'date-filter-form',
        'type'=>'search',
        'htmlOptions'=>array('class'=>'well'),
        // to enable ajax validation
        'enableAjaxValidation'=>true,
        'action' => Yii::app()->getBaseUrl() . '/campaigns/graphicCampaign',
        'method' => 'GET',
        'clientOptions'=>array('validateOnSubmit'=>true, 'validateOnChange'=>true),
    )); ?> 

    <fieldset>
    From: 
    <div class="input-append">
        <?php 
            $this->widget('bootstrap.widgets.TbDatePicker',array(
            'name'  => 'dateStart',
            'value' => date('d-m-Y', strtotime($dateStart)),
            'htmlOptions' => array(
                'style' => 'width: 80px',
            ),
            'options' => array(
                'autoclose'  => true,
                'todayHighlight' => true,
                'format'     => 'dd-mm-yyyy',
                'viewformat' => 'dd-mm-yyyy',
                'placement'  => 'right',
            ),
        ));
        ?>
        <span class="add-on"><i class="icon-calendar"></i></span>
    </div>
    To:
    <div class="input-append">
        <?php 
            $this->widget('bootstrap.widgets.TbDatePicker',array(
            'name'        => 'dateEnd',
            'value'       => date('d-m-Y', strtotime($dateEnd)),
            'htmlOptions' => array(
                'style' => 'width: 80px',
            ),
            'options'     => array(
                'autoclose'      => true,
                'todayHighlight' => true,
                'format'         => 'dd-mm-yyyy',
                'viewformat'     => 'dd-mm-yyyy',
                'placement'      => 'right',
            ),
        ));
        ?>
        <span class="add-on"><i class="icon-calendar"></i></span>
    </div>
    <?php echo $form->hiddenField($model, 'id', array('name'=>'id')) ?>
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'label'=>'Filter')); ?>

    </fieldset>

<?php $this->endWidget(); ?>
    </div>
</div>
<div class="row" id="top">
    <div id="container-highchart" class="span9">

    <?php 
        $this->widget('ext.highcharts.HighmapsWidget', array(
        'id'=>'geo-map',
        'options' => array(
            'title' => array(
                //'text' => 'GEO Traffic Clicks',
            ),
            'mapNavigation' => array(
                'enabled' => false,
                'buttonOptions' => array(
                    'verticalAlign' => 'bottom',
                )
            ),
            'colorAxis' => array(
                'min' => 0,
            ),
            'series' => array(
                array(
                    'data' => $geo['array'],
                    'mapData' => 'js:Highcharts.maps["custom/world"]',
                    'joinBy' => 'hc-key',
                    'name' => 'Random data',
                    'states' => array(
                        'hover' => array(
                            'color' => '#BADA55',
                        )
                    ),
                    'dataLabels' => array(
                        'enabled' => false,
                        'format' => '{point.name}',
                    )
                )
            )
        )
    ));
     Yii::app()->clientScript->registerScriptFile('//code.highcharts.com/mapdata/custom/world.js');
     
     ?>

	</div>
    <div class="span3">
        <?php
        $this->widget('bootstrap.widgets.TbGridView', array(
            'id'           =>'geo-grid',
            'type'         =>'striped condensed',
            'dataProvider' =>$geo['dataprovider'],
            'template'     =>'{items}',
            'columns'      =>array(
                array(
                    'name'        => 'Country',
                    'value'       => 'GeoLocation::model()->getNameFromISO2($data->country)', 
                    'htmlOptions' => array('style' => 'width: 60px'),          
                ),
                array(
                    'name'        => 'Clicks',
                    'value'       => '$data->clics', 
                    'htmlOptions' => array('style' => 'width: 10px'),  
                ),
            ),
        ));
        ?>

    </div>
</div>

<div class="row" id="top">
    <div class="span6">
        <h4>Carriers</h4>
    </div>
    <div class="span6">
        <h4>Devices Type</h4>
    </div>
</div>

<div class="row" id="top">
    <div class="span6">
        <?php
            $this->Widget('ext.highcharts.HighchartsWidget', array(
                'options'=>array(
                    'chart' => array('type' => 'pie', 'height' => 200,),
                    'title' => array('text' => ''),
                    'tooltip' => array('crosshairs'=>'true', 'shared'=>'true'),
                    'xAxis' => array(
                        //'categories' => $carrier['carrier'],
                        'labels' => array(
                            'rotation' => -45,
                            'align' => 'right',
                            'style' => array(
                                'fontSize' => '9px',
                                'fontFamily' => 'Verdana, sans-serif'
                                )
                            )
                        ),
                    //'tooltip' => array('crosshairs'=>'true', 'shared'=>'true'),
                    'yAxis' => array(
                        'title' => array('text' => '')
                        ),
                    'series' => array(
                        array(
                            'name'=> 'Clics',
                            'data' => $carrier,
                            ),
                        ),
                    'legend' => array(
                        'enabled' => false
                        ),
                        'plotOptions' => array(
                            'column' => array(
                                'groupPadding' => 0.05,
                                'stacking' => 'normal',
                                'dataLabels' => array(
                                    'enabled' => true,
                                    //'color' => (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white',
                                    //'style' => array(
                                    //    'textShadow' => '0 0 3px black, 0 0 3px black'
                                    //)
                                )
                            )
                        ),
                    ),
                )
            );
            ?>
    </div>
    <div class="span6">
        <?php
            $this->Widget('ext.highcharts.HighchartsWidget', array(
                'options'=>array(
                    'chart' => array('type' => 'pie', 'height' => 200,),
                    'title' => array('text' => ''),
                    'tooltip' => array('crosshairs'=>'true', 'shared'=>'true'),
                    'xAxis' => array(
                        //'categories' => $carrier['carrier'],
                        'labels' => array(
                            'rotation' => -45,
                            'align' => 'right',
                            'style' => array(
                                'fontSize' => '9px',
                                'fontFamily' => 'Verdana, sans-serif'
                                )
                            )
                        ),
                    //'tooltip' => array('crosshairs'=>'true', 'shared'=>'true'),
                    'yAxis' => array(
                        'title' => array('text' => '')
                        ),
                    'series' => array(
                        array(
                            'name'=> 'Clics',
                            'data' => $device_type,
                            ),
                        ),
                    'legend' => array(
                        'enabled' => false
                        ),
                        'plotOptions' => array(
                            'column' => array(
                                'groupPadding' => 0.05,
                                'stacking' => 'normal',
                                'dataLabels' => array(
                                    'enabled' => true,
                                    //'color' => (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white',
                                    //'style' => array(
                                    //    'textShadow' => '0 0 3px black, 0 0 3px black'
                                    //)
                                )
                            )
                        ),
                    ),
                )
            );
            ?>
    </div>
</div> 

<div class="row" id="top">
    <div class="span6">
        <h4>Browsers</h4>
    </div>
    <div class="span6">
        <h4>Operative Systems</h4>
    </div>
</div>

<div class="row" id="top">
    <div class="span6">
        <?php
            $this->Widget('ext.highcharts.HighchartsWidget', array(
                'options'=>array(
                    'chart' => array('type' => 'pie', 'height' => 200,),
                    'title' => array('text' => ''),
                    'tooltip' => array('crosshairs'=>'true', 'shared'=>'true'),
                    'xAxis' => array(
                        //'categories' => $carrier['carrier'],
                        'labels' => array(
                            'rotation' => -45,
                            'align' => 'right',
                            'style' => array(
                                'fontSize' => '9px',
                                'fontFamily' => 'Verdana, sans-serif'
                                )
                            )
                        ),
                    //'tooltip' => array('crosshairs'=>'true', 'shared'=>'true'),
                    'yAxis' => array(
                        'title' => array('text' => '')
                        ),
                    'series' => array(
                        array(
                            'name'=> 'Clics',
                            'data' => $browser,
                            ),
                        ),
                    'legend' => array(
                        'enabled' => false
                        ),
                        'plotOptions' => array(
                            'column' => array(
                                'groupPadding' => 0.05,
                                'stacking' => 'normal',
                                'dataLabels' => array(
                                    'enabled' => true,
                                    //'color' => (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white',
                                    //'style' => array(
                                    //    'textShadow' => '0 0 3px black, 0 0 3px black'
                                    //)
                                )
                            )
                        ),
                    ),
                )
            );
            ?>
    </div>

    <div class="span6">
        <?php
            $this->Widget('ext.highcharts.HighchartsWidget', array(
                'options'=>array(
                    'chart' => array('type' => 'pie', 'height' => 200,),
                    'title' => array('text' => ''),
                    'tooltip' => array('crosshairs'=>'true', 'shared'=>'true'),
                    'xAxis' => array(
                        //'categories' => $carrier['carrier'],
                        'labels' => array(
                            'rotation' => -45,
                            'align' => 'right',
                            'style' => array(
                                'fontSize' => '9px',
                                'fontFamily' => 'Verdana, sans-serif'
                                )
                            )
                        ),
                    //'tooltip' => array('crosshairs'=>'true', 'shared'=>'true'),
                    'yAxis' => array(
                        'title' => array('text' => '')
                        ),
                    'series' => array(
                        array(
                            'name'=> 'Clics',
                            'data' => $os,
                            ),
                        ),
                    'legend' => array(
                        'enabled' => false
                        ),
                        'plotOptions' => array(
                            'column' => array(
                                'groupPadding' => 0.05,
                                'stacking' => 'normal',
                                'dataLabels' => array(
                                    'enabled' => true,
                                    //'color' => (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white',
                                    //'style' => array(
                                    //    'textShadow' => '0 0 3px black, 0 0 3px black'
                                    //)
                                )
                            )
                        ),
                    ),
                )
            );
            ?>
    </div>
</div> 

<div class="row" id="top">
    <div class="span12">
        <h4>Device</h4>
    </div>
</div>

<div class="row" id="top">
    <div class="span12">
        <?php
        $this->widget('bootstrap.widgets.TbGridView', array(
            'id'           =>'device-grid',
            'type'         =>'striped condensed',
            'dataProvider' =>$device,
            'filter'       =>new ClicksLog,
            'template'     => '{items} {summary} {pager}',
            'columns'      =>array(
                array(
                    'name'        => 'device',
                    'value'       => '$data->device', 
                    'htmlOptions' => array('style' => 'width: 60px'),          
                ),
                array(
                    'name'        => 'device_model',
                    'value'       => '$data->device_model', 
                    'htmlOptions' => array('style' => 'width: 60px'),          
                ),
                array(
                    'name'        => 'device_type',
                    'value'       => '$data->device_type', 
                    'htmlOptions' => array('style' => 'width: 60px'),          
                ),
                array(
                    'name'        => 'clics',
                    'value'       => '$data->clics', 
                    'htmlOptions' => array('style' => 'width: 10px'),  
                ),
            ),
        ));
        ?>
    </div>
</div>

<div 
<div class="row" id="blank-row">
</div>

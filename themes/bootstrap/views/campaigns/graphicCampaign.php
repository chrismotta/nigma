<?php
/* @var $this CampaignsController */
/* @var $dataProvider CActiveDataProvider */
$this->pageTitle=Yii::app()->name;
?>

<div class="row">
    <div id="container-highchart" class="span12">
        <h2>Dashboard Campaign</h2>
    </div>
</div>
<div class="row">
    <div id="container-highchart" class="span12">
    <?php 
    // $data=array();
    // $dateStart=date('Y-m-d', strtotime($dateStart));
    // $dateEnd=date('Y-m-d', strtotime($dateEnd));
    // $criteria=new CDbCriteria;
    // $criteria->select='count(*) as clics, country';
    // $criteria->addCondition("DATE(date)>='".$dateStart."' AND DATE(date)<='".$dateEnd."' AND campaigns_id=".$model->id);
    // $criteria->group='country';
    // $clicksLogs = ClicksLog::model()->findAll($criteria);
    // foreach ($clicksLogs as $log) {
    // 	if(strlen($log->country)==2)
    //     $data[]=array('hc-key' => strtolower($log->country), 'value' => $log->clics);
    // }

    // //select campaigns_id,count(*),device from clicks_log where campaigns_id=11 group by device;
    // $criteria=new CDbCriteria;
    // $criteria->select='count(*) as clics,device';
    // $criteria->addCondition("DATE(date)>='".$dateStart."' AND DATE(date)<='".$dateEnd."' AND campaigns_id=".$model->id);


        $this->widget('ext.highcharts.HighmapsWidget', array(
        'id'=>'asd',
        'options' => array(
            'title' => array(
                'text' => '',
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
                    'data' => $geo,
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

<div class="row" id="top">
    <div class="span6">
        <h4>Carriers</h4>
    </div>
    <div class="span6">
        <h4>Devices</h4>
    </div>
</div>

<div class="row">
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
</div> 

<div class="row">
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
                            'data' => $device,
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

<div class="row">
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
</div> 

<div class="row">
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
<div class="row" id="blank-row">
</div>

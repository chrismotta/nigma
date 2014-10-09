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
        <br>
    <?php
$criteria=new CDbCriteria;
$criteria->with=array('clicksLog');
$criteria->addCondition('t.campaign_id='.$model->id);
$criteria->addCondition("DATE(t.date)>='".date('Y-m-d', strtotime($dateStart))."'");
$criteria->addCondition("DATE(t.date)<='".date('Y-m-d', strtotime($dateEnd))."'");
$criteria->addCondition('t.clicks_log_id=clicksLog.id');
$modeld=new ConvLog;
$data=new CActiveDataProvider($modeld, array(
            'criteria' =>$criteria,));
//country, city, carrier, browser, os, device, device_type, referer_url y app
$this->widget('bootstrap.widgets.TbGridView', array(
    'id'=>'topspend-grid',
    'type'=>'striped condensed',
    'dataProvider'=>$data,
    'template'                 =>'{items}',
    'columns'=>array(
        array(
            'name'   => 'id',
            'value'  => '$data->id',            
        ),
        array(
            'name'   => 'Campaign',
            'value'  => 'Campaigns::model()->getExternalName($data->campaign_id)',           
        ),
        array(
            'name'   => 'Country',
            'value'  => '$data->clicksLog->country',            
        ),
        array(
            'name'   => 'City',
            'value'  => '$data->clicksLog->city',            
        ),
        array(
            'name'   => 'Carrier',
            'value'  => '$data->clicksLog->carrier',            
        ),
        array(
            'name'   => 'Browser',
            'value'  => '$data->clicksLog->browser',            
        ),
        array(
            'name'   => 'OS',
            'value'  => '$data->clicksLog->os',            
        ),
        array(
            'name'   => 'Device',
            'value'  => '$data->clicksLog->device',            
        ),
        array(
            'name'   => 'Device Type',
            'value'  => '$data->clicksLog->device_type',            
        ),
        array(
            'name'   => 'Referer URL',
            'value'  => '$data->clicksLog->referer',            
        ),
        array(
            'name'   => 'APP',
            'value'  => '$data->clicksLog->app',            
        ),
        array(
            'name'   => 'Date',
            'value'  => '$data->date',            
        ),
    ),
));
?>

</div>
</div>

<div class="modal-footer">
    Edit campaign attributes. Fields with <span class="required">*</span> are required.
</div>

<?php
/* @var $this CampaignsController */
/* @var $model Campaigns */

$this->widget('EExcelWriter', array(
    'dataProvider' => $model->search(),
    'title'        => 'EExcelWriter',
    'stream'       => TRUE,
    'fileName'     => 'file.xls',
    'columns'      => array(
        'tid',
        'id',
    ),
));

?>
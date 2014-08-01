<?php
/* @var $this CampaignsController */
/* @var $model Campaigns */
?>

<div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h4>Campaign <?php echo "#".$model->id ?></h4>
</div>

<div class="modal-body">

<h5>Advertiser</h5>
<?php $this->widget('bootstrap.widgets.TbDetailView', array(
    'type'=>'striped bordered condensed',
	'data'=>$model,
	'attributes'=>array(
		'opportunities.ios.advertisers.name',
	),
)); ?>

<h5>Insertion Order</h5>
<?php $this->widget('bootstrap.widgets.TbDetailView', array(
    'type'=>'striped bordered condensed',
	'data'=>$model,
	'attributes'=>array(
		'opportunities.ios.id',
	),
)); ?>

<h5>Opportunity</h5>
<?php $this->widget('bootstrap.widgets.TbDetailView', array(
    'type'=>'striped bordered condensed',
	'data'=>$model,
	'attributes'=>array(
		'opportunities.id',
	),
)); ?>

<h5>Campaign</h5>
<?php $this->widget('bootstrap.widgets.TbDetailView', array(
    'type'=>'striped bordered condensed',
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
		'networks.name',
		'campaign_categories.name',
		'wifi',
		'formats_id',
		'cap',
		'model',
		'ip',
		'devices_id',
		'url',
		'status',
		'opportunities_id',
	),
)); ?>

</div>

<div class="modal-footer">
    Campaigns detail view.
</div>

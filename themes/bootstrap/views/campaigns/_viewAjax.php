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
		'opportunities.ios.advertisers.id',
		'opportunities.ios.advertisers.name',
		'opportunities.ios.advertisers.cat',
		'opportunities.ios.advertisers.status',
	),
)); ?>

<h5>Insertion Order</h5>
<?php $this->widget('bootstrap.widgets.TbDetailView', array(
    'type'=>'striped bordered condensed',
	'data'=>$model,
	'attributes'=>array(
		'opportunities.ios.id',
		'opportunities.ios.name',
		'opportunities.ios.commercial_name',
		'opportunities.ios.status',
		'opportunities.ios.prospect',
		// 'opportunities.ios.address',
		array(
			'label' => 'Country',
			'name'  => 'opportunities.ios.country.name',
		),
		'opportunities.ios.state',
		'opportunities.ios.zip_code',
		'opportunities.ios.phone',
		'opportunities.ios.contact_com',
		'opportunities.ios.email_adm',
		'opportunities.ios.contact_adm',
		'opportunities.ios.currency',
		'opportunities.ios.ret',
		'opportunities.ios.tax_id',
		'opportunities.ios.net_payment',
	),
)); ?>

<h5>Commercial Manager</h5>
<?php $this->widget('bootstrap.widgets.TbDetailView', array(
    'type'=>'striped bordered condensed',
	'data'=>$model,
	'attributes'=>array(
		'opportunities.ios.commercial.id',
		'opportunities.ios.commercial.name',
		'opportunities.ios.commercial.lastname',
		'opportunities.ios.commercial.username',
	),
)); ?>

<h5>Opportunity</h5>
<?php $this->widget('bootstrap.widgets.TbDetailView', array(
    'type'=>'striped bordered condensed',
	'data'=>$model,
	'attributes'=>array(
		'opportunities.id',
		array(
			'label' => 'Country',
			'name'  => 'opportunities.carriers.mobile_brand',
		),
		'opportunities.rate',
		'opportunities.model_adv',
		'opportunities.product',
		'opportunities.comment',
		'opportunities.country.name',
		array(
			'label' => 'Wifi',
			'name' => 'opportunities.wifi',
		),
		'opportunities.budget',
		'opportunities.server_to_server',
		'opportunities.startDate',
		'opportunities.endDate',
		'opportunities.status',
	),
)); ?>

<h5>Account Manager</h5>
<?php $this->widget('bootstrap.widgets.TbDetailView', array(
    'type'=>'striped bordered condensed',
	'data'=>$model,
	'attributes'=>array(
		'opportunities.accountManager.id',
		'opportunities.accountManager.name',
		'opportunities.accountManager.lastname',
		'opportunities.accountManager.username',
	),
)); ?>

<h5>Campaign</h5>
<?php $this->widget('bootstrap.widgets.TbDetailView', array(
    'type'=>'striped bordered condensed',
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
		array(
			'label' => $model->getAttributeLabel('networks_id'),
			'name'  => 'networks.name',
		),
		array(
			'label' => $model->getAttributeLabel('campaign_categories_id'),
			'name'  => 'campaignCategories.name',
		),
		array(
			'label' =>$model->getAttributeLabel('wifi'),
			'value' =>$model->wifi ? "Habilitado" : "Inhabilitado",
		),
		array(
			'label' =>$model->getAttributeLabel('ip'),
			'value' =>$model->ip ? "Habilitado" : "Inhabilitado",
		),
		array(
			'label' => $model->getAttributeLabel('formats_id'),
			'name'  => 'formats.name',
		),
		'cap',
		'model',
		array(
			'label' => $model->getAttributeLabel('devices_id'),
			'name'  => 'devices.name',
		),
		'url',
		'status',
		'comment',
		array(
			'label' => $model->getAttributeLabel('banner_sizes_id'),
			'name'  => 'bannerSizes.name',
		),
	),
)); ?>

</div>

<div class="modal-footer">
    Campaigns detail view.
</div>

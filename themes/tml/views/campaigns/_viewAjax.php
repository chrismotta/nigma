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
		'opportunities.regions.financeEntities.advertisers.id',
		'opportunities.regions.financeEntities.advertisers.name',
		'opportunities.regions.financeEntities.advertisers.cat',
		'opportunities.regions.financeEntities.advertisers.status',
	),
)); ?>

<h5>Insertion Order</h5>
<?php $this->widget('bootstrap.widgets.TbDetailView', array(
    'type'=>'striped bordered condensed',
	'data'=>$model,
	'attributes'=>array(
		'opportunities.regions.financeEntities.id',
		'opportunities.regions.financeEntities.name',
		'opportunities.regions.financeEntities.commercial_name',
		'opportunities.regions.financeEntities.status',
		'opportunities.regions.financeEntities.prospect',
		// 'opportunities.regions.financeEntities.address',
		array(
			'label' => 'Country',
			'name'  => 'opportunities.regions.financeEntities.country.name',
		),
		'opportunities.regions.financeEntities.state',
		'opportunities.regions.financeEntities.zip_code',
		'opportunities.regions.financeEntities.phone',
		'opportunities.regions.financeEntities.contact_com',
		'opportunities.regions.financeEntities.email_adm',
		'opportunities.regions.financeEntities.contact_adm',
		'opportunities.regions.financeEntities.currency',
		'opportunities.regions.financeEntities.ret',
		'opportunities.regions.financeEntities.tax_id',
		'opportunities.regions.financeEntities.net_payment',
	),
)); ?>

<h5>Commercial Manager</h5>
<?php $this->widget('bootstrap.widgets.TbDetailView', array(
    'type'=>'striped bordered condensed',
	'data'=>$model,
	'attributes'=>array(
		'opportunities.regions.financeEntities.commercial.id',
		'opportunities.regions.financeEntities.commercial.name',
		'opportunities.regions.financeEntities.commercial.lastname',
		'opportunities.regions.financeEntities.commercial.username',
	),
)); ?>

<h5>Opportunity</h5>
<?php $this->widget('bootstrap.widgets.TbDetailView', array(
    'type'=>'striped bordered condensed',
	'data'=>$model,
	'attributes'=>array(
		'opportunities.id',
		'opportunities.country.name',
		'opportunities.carriers.mobile_brand',
		'opportunities.rate',
		'opportunities.model_adv',
		'opportunities.product',
		'opportunities.comment',
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
			'label' => $model->getAttributeLabel('providers_id'),
			'name'  => 'providers.name',
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

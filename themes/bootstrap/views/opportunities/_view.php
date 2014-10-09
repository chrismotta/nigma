<?php
/* @var $this OpportunitiesController */
/* @var $model Opportunities */
?>

<div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h4>Opportunity <?php echo "#".$model->id ?></h4>
</div>

<div class="modal-body">

	<h5>Advertiser</h5>
	<?php $this->widget('bootstrap.widgets.TbDetailView', array(
	    'type'=>'striped bordered condensed',
		'data'=>$model,
		'attributes'=>array(
			'ios.advertisers.id',
			'ios.advertisers.name',
			'ios.advertisers.cat',
		),
	)); ?>


	<h5>Insertion Order</h5>
	<?php $this->widget('bootstrap.widgets.TbDetailView', array(
	    'type'=>'striped bordered condensed',
		'data'=>$model,
		'attributes'=>array(
			'ios.id',
			'ios.name',
			'ios.commercial_name',
			'ios.status',
			// 'opportunities.ios.address',
			array(
				'label' => 'Country',
				'name'  => 'ios.country.name',
			),
			'ios.state',
			'ios.zip_code',
			'ios.phone',
			'ios.contact_com',
			'ios.email_adm',
			'ios.contact_adm',
			'ios.currency',
			'ios.ret',
			'ios.tax_id',
			'ios.net_payment',
			'ios.entity',
		),
	)); ?>

	<h5>Commercial Manager</h5>
	<?php $this->widget('bootstrap.widgets.TbDetailView', array(
	    'type'=>'striped bordered condensed',
		'data'=>$model,
		'attributes'=>array(
			'ios.commercial.id',
			'ios.commercial.name',
			'ios.commercial.lastname',
			'ios.commercial.username',
		),
	)); ?>

	<h5>Opportunity</h5>
	<?php $this->widget('bootstrap.widgets.TbDetailView', array(
	    'type'=>'striped bordered condensed',
		'data'=>$model,
		'attributes'=>array(
			'id',
			array(
				'label' =>$model->getAttributeLabel('carrier_mobile_brand'),
				'name'  =>'carriers.mobile_brand'
			),
			'rate',
			'model_adv',
			'product',
			'comment',
			array(
				'label' =>$model->getAttributeLabel('country_name'),
				'name'  =>'country.name'
			),
			array(
				'label' =>$model->getAttributeLabel('wifi'),
				'value' =>$model->wifi ? "Habilitado" : "Inhabilitado",
			),
			'budget',
			'server_to_server',
			'startDate',
			'endDate',
			'freq_cap',
			'imp_per_day',
			'imp_total',
			'targeting',
			'sizes',
			'channel',
			'channel_description',
		),
	)); ?>

	<h5>Account Manager</h5>
	<?php $this->widget('bootstrap.widgets.TbDetailView', array(
	    'type'=>'striped bordered condensed',
		'data'=>$model,
		'attributes'=>array(
			'accountManager.id',
			'accountManager.name',
			'accountManager.lastname',
			'accountManager.username',
		),
	)); ?>
</div>

<div class="modal-footer">
    Opportunity detail view.
</div>
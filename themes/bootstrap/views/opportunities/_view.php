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
		),
	)); ?>


	<h5>Insertion Order</h5>
	<?php $this->widget('bootstrap.widgets.TbDetailView', array(
	    'type'=>'striped bordered condensed',
		'data'=>$model,
		'attributes'=>array(
			'ios.id',
			'ios.name',
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
		),
	)); ?>

</div>

<div class="modal-footer">
    Opportunity detail view.
</div>
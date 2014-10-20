<?php
/* @var $this IosController */
/* @var $model Ios */
?>

<div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h4>Io <?php echo "#".$model->id ?></h4>
</div>

<div class="modal-body">

	<h5>Advertiser</h5>
	<?php $this->widget('bootstrap.widgets.TbDetailView', array(
	    'type'=>'striped bordered condensed',
		'data'=>$model,
		'attributes'=>array(
			'advertisers.id',
			'advertisers.name',
			'advertisers.cat',
			'advertisers.status',
		),
	)); ?>

	<h5>Commercial</h5>
	<?php $this->widget('bootstrap.widgets.TbDetailView', array(
	    'type'=>'striped bordered condensed',
		'data'=>$model,
		'attributes'=>array(
			'commercial.id',
			'commercial.name',
			'commercial.lastname',
			'commercial.username',
			'commercial.email',
		),
	)); ?>

	<h5>Insertion Order</h5>
	<?php $this->widget('bootstrap.widgets.TbDetailView', array(
	    'type'=>'striped bordered condensed',
		'data'=>$model,
		'attributes'=>array(
			'id',
			'name',
			'commercial_name',
			array(
				'label' =>$model->getAttributeLabel('country_name'),
				'name'  =>'country.name'
			),
			'address',
			'state',
			'prospect',
			'zip_code',
			'phone',
			'contact_com',
			'email_adm',
			'contact_adm',
			'currency',
			'ret',
			'tax_id',
			'net_payment',
			'entity',
			'status',
			'description',
		),
	)); ?>

</div>

<div class="modal-footer">
    Io detail view.
</div>
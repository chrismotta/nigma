<?php
/* @var $this PlacementsController */
/* @var $data Placements */
?>

<div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h4>Publisher <?php echo "#".$model->id ?></h4>
</div>

<div class="modal-body">

	<h5>Publisher</h5>
	<?php $this->widget('bootstrap.widgets.TbDetailView', array(
	    'type'=>'striped bordered condensed',
		'data'=>$model,
		'attributes'=>array(
			'publishers.id',
			'publishers.status',
			'publishers.name',
			'publishers.commercial_name',
			array(
				'label' => 'Country',
				'name'  => 'publishers.country.name',
			),
			'publishers.state',
			'publishers.zip_code',
			'publishers.address',
			'publishers.phone',
			'publishers.currency',
			'publishers.contact_com',
			'publishers.email_com',
			'publishers.contact_adm',
			'publishers.email_adm',
			'publishers.entity',
			'publishers.tax_id',
			'publishers.net_payment',
			'publishers.model',
			'publishers.RS_perc',
			'publishers.rate',
		),
	)); ?>

	<h5>Account Manager</h5>
	<?php $this->widget('bootstrap.widgets.TbDetailView', array(
	    'type'=>'striped bordered condensed',
		'data'=>$model,
		'attributes'=>array(
			'publishers.accountManager.id',
			'publishers.accountManager.name',
			'publishers.accountManager.lastname',
			'publishers.accountManager.username',
		),
	)); ?>

	<h5>Placements</h5>
	<?php $this->widget('bootstrap.widgets.TbDetailView', array(
	    'type'=>'striped bordered condensed',
		'data'=>$model,
		'attributes'=>array(
			'id',
			'name',
			'product',
			array(
				'label' => 'Exchange',
				'name'  => 'exchanges.name'
			),
			array(
				'label' => 'Size',
				'name'  => 'sizes.size'
			),
		),
	)); ?>
</div>

<div class="modal-footer">
    Publisher detail view.
</div>
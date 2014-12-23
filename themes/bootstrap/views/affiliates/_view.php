<?php
/* @var $this AffiliatesController */
/* @var $model Affiliates */
?>

<div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h4>Affiliate <?php echo "#".$modelAffi->providers_id ?></h4>
</div>

<div class="modal-body">
	
	<h5>Providers</h5>
	<?php $this->widget('bootstrap.widgets.TbDetailView', array(
	    'type'=>'striped bordered condensed',
		'data'=>$modelProv,
		'attributes'=>array(
			'id',
			'prefix',
			'name',
			'currency',
			'entity',
			'status',
		),
	)); ?>

	<h5>Affiliates</h5>
	<?php $this->widget('bootstrap.widgets.TbDetailView', array(
	    'type'=>'striped bordered condensed',
		'data'=>$modelAffi,
		'attributes'=>array(
			'providers_id',
			'commercial_name',
			array(
				'label' =>$modelAffi->getAttributeLabel('country_name'),
				'name'  =>'country.name'
			),
			'address',
			'state',
			'zip_code',
			'phone',
			'contact_com',
			'email_adm',
			'contact_adm',
		),
	)); ?>
</div>

<div class="modal-footer">
    Affiliate detail view.
</div>
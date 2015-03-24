<?php
/* @var $this IosController */
/* @var $model Ios */
/* @var $opportunities Opportunities */
?>

<div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h4>Io <?php echo "#".$model->id ?></h4>
</div>

<div class="modal-body">
	
	<h5>Insertion Order</h5>
	<?php $this->widget('bootstrap.widgets.TbDetailView', array(
	    'type'=>'striped bordered condensed',
		'data'=>$model,
		'attributes'=>array(
			'id',
			'name',
			// array(
			// 	'label' =>$model->getAttributeLabel('country_name'),
			// 	'name'  =>'country.name'
			// ),
			// 'address',
			// 'state',
			// 'zip_code',
			// 'phone',
			// 'email',
			// 'contact_adm',
			'currency',
			'ret',
			// 'tax_id',
			// 'net_payment',
		),
	)); ?>

	<h5>Opportunities</h5>
	<?php foreach($opportunities as $opp) {
		echo '<h5>Opportunity #' . $opp->id . '</h5>';
		$this->widget('bootstrap.widgets.TbDetailView', array(
		    'type'=>'striped bordered condensed',
			'data'=>$opp,
			'attributes'=>array(
				'id',
				'rate',
				'model_adv',
				'product',
				array(
					'label' =>$opp->getAttributeLabel('wifi'),
					'value' =>$opp->wifi ? "Habilitado" : "Inhabilitado",
				),
				'budget',
				'startDate',
				'endDate',
			),
		));
	}; ?>


	<div class="form-actions">
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'success', 'label'=>'Generate PDF')); ?>
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'cancel', 'type'=>'cancel', 'label'=>'Cancel')); ?>
    </div>

</div>

<div class="modal-footer">
    PDF generation.
</div>
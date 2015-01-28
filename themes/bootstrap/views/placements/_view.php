<?php
/* @var $this PlacementsController */
/* @var $data Placements */
?>

<div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h4>Placements <?php echo "#".$model->id ?></h4>
</div>

<div class="modal-body">

	<?php $this->renderPartial('/providers/_view', array(
        'model' => $model->publishers->providers,
    )); ?>

	<h5>Publisher</h5>
	<?php $this->widget('bootstrap.widgets.TbDetailView', array(
	    'type'=>'striped bordered condensed',
		'data'=>$model,
		'attributes'=>array(
			'publishers.providers_id',
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
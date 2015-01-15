<?php
/* @var $this NetworksController */
/* @var $model Networks */
?>

<div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h4>Network <?php echo "#".$modelNetw->providers_id ?></h4>
</div>

<div class="modal-body">
	
	<?php $this->renderPartial('/providers/_view', array(
        'model' => $modelProv,
    )); ?>

	<h5>Networks</h5>
	<?php $this->widget('bootstrap.widgets.TbDetailView', array(
	    'type'=>'striped bordered condensed',
		'data'=>$modelNetw,
		'attributes'=>array(
			'providers_id',
			'percent_off',
			'url',
			'use_alternative_convention_name',
			'has_api',
			'use_vectors',
			'query_string',
			'token1',
			'token2',
			'token3',
		),
	)); ?>
</div>

<div class="modal-footer">
    Network detail view.
</div>
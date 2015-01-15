<?php
/* @var $this AffiliatesController */
/* @var $model Affiliates */
?>

<div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h4>Affiliate <?php echo "#".$modelAffi->providers_id ?></h4>
</div>

<div class="modal-body">
	
	<?php $this->renderPartial('/providers/_view', array(
        'model' => $modelProv,
    )); ?>

	<h5>External User Login</h5>
	<?php $this->widget('bootstrap.widgets.TbDetailView', array(
	    'type'=>'striped bordered condensed',
		'data'=>$modelAffi,
		'attributes'=>array(
			'users.username',
			'users.name',
			'users.lastname',
		),
	)); ?>

	<h5>Affiliates</h5>
	<?php $this->widget('bootstrap.widgets.TbDetailView', array(
	    'type'=>'striped bordered condensed',
		'data'=>$modelAffi,
		'attributes'=>array(
			'phone',
		),
	)); ?>
</div>

<div class="modal-footer">
    Affiliate detail view.
</div>
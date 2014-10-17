<?php
/* @var $this AdvertisersController */
/* @var $model Advertiser */
?>

<div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h4>Advertiser <?php echo "#".$model->id ?></h4>
</div>

<div class="modal-body">

<h5>Advertiser</h5>
<?php $this->widget('bootstrap.widgets.TbDetailView', array(
    'type'=>'striped bordered condensed',
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
		'cat',
		'status',
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

</div>

<div class="modal-footer">
    Advertiser detail view.
</div>

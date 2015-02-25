<?php
/* @var $this UsersController */
/* @var $data Users */
?>

<div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h4>User <?php echo "#".$model->id ?></h4>
</div>

<div class="modal-body">

	<h5>User</h5>
	<?php $this->widget('bootstrap.widgets.TbDetailView', array(
	    'type'=>'striped bordered condensed',
		'data'=>$model,
		'attributes'=>array(
			'id',
			'name',
			'lastname',
			'username',
			'status',
		),
	)); ?>
</div>

<div class="modal-footer">
    User detail view.
</div>
<?php
/* @var $this PublishersController */
/* @var $data Publishers */
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
			'id',
			'status',
			'name',
			'commercial_name',
			array(
				'label' => 'Country',
				'name'  => 'country.name',
			),
			'state',
			'zip_code',
			'address',
			'phone',
			'currency',
			'contact_com',
			'email_com',
			'contact_adm',
			'email_adm',
			'entity',
			'tax_id',
			'net_payment',
			'model',
			'RS_perc',
			'rate',
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

	<h5>Placements</h5>
	<?php 
	$placement = new Placements;
	$this->widget('bootstrap.widgets.TbGridView', array(
    'id'           =>'placement-grid',
    'type'         =>'striped condensed',
    'dataProvider' =>$placement->findByPublisherId($model->id),
    'template'     =>'{items} {summary} {pager}',
    'columns'=>array(
        array(
			'name'              =>'id',
			'headerHtmlOptions' => array('style'=>'width: 50px'),
		),
		array(
			'name'              =>'name',
			'headerHtmlOptions' => array('style'=>'width: 70px'),
		),
		array(
			'name'              =>'product',
			'headerHtmlOptions' => array('style'=>'width: 70px'),
		),
		array(
			'name'              =>'sizes_id',
			'value'             => '$data->sizes_id ? $data->sizes->size: ""',
			'headerHtmlOptions' => array('style'=>'width: 50px'),
		),
    ),
));
?>
</div>

<div class="modal-footer">
    Publisher detail view.
</div>
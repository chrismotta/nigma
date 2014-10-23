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
	'type'       =>'striped bordered condensed',
	'data'       =>$model,
	'attributes' =>array(
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

<h5>Ios</h5>
<?php 

	$ios=new Ios;
	$this->widget('bootstrap.widgets.TbExtendedGridView', array(
	'id'                       => 'ios-grid',
	'dataProvider'             => $ios->findByAdvertisers($model->id),
	'type'                     => 'striped condensed',
	//'fixedHeader'              => true,
	//'headerOffset'             => 50,
	'rowHtmlOptionsExpression' => 'array("data-row-id" => $data->id)',
	'template'                 =>'{items} {pager}',
	
	'columns'                  =>array(
		array(
			'name'              =>'id',
			//'headerHtmlOptions' => array('style' => "width: 60px"),
		),
		array(
			'name'              =>'name',
			//'headerHtmlOptions' => array('style' => "width: 100px"),
		),
		'commercial_name',
		array(
				'name'  =>'country_name',
				'value' => '$data->country ? $data->country->name : ""',			
		),
		array(
				'name'   =>'contact_com',
				'value'  => '$data->contact_com',
				'header' =>'Com. Name',			
		),
		array(
				'name'   =>'contact_adm',
				'value'  => '$data->contact_adm',	
				'header' =>'Adm. Name',					
		),
		array(
			'name'  =>'com_lastname',
			'value' => '$data->commercial ? $data->commercial->lastname . " " . $data->commercial->name : ""',
		),
		array(
			'name'              =>'entity',
			//'headerHtmlOptions' => array('style' => "width: 30px"),
		),
	),
)); ?>
</div>
<div class="modal-footer">
    Advertiser detail view.
</div>

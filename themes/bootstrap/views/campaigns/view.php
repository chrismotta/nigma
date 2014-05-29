<?php
/* @var $this CampaignsController */
/* @var $model Campaigns */

$this->breadcrumbs=array(
	'Campaigns'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List Campaigns', 'url'=>array('index')),
	array('label'=>'Create Campaigns', 'url'=>array('create')),
	array('label'=>'Update Campaigns', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Campaigns', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Campaigns', 'url'=>array('admin')),
);
?>

<h1>View Campaigns #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'rec',
		'ios_id',
		'name',
		'campaign_categories_id',
		'offer_type',
		'currency',
		'budget_type',
		'budget',
		'cap',
		'model',
		'bid',
		'comment',
		'status',
		'date_start',
		'date_end',
	),
)); ?>

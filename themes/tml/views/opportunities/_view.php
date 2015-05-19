<?php
/* @var $this OpportunitiesController */
/* @var $model Opportunities */
?>

<div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h4>Opportunity <?php echo "#".$model->id ?></h4>
</div>

<div class="modal-body">

	<h5>Advertiser</h5>
	<?php $this->widget('bootstrap.widgets.TbDetailView', array(
	    'type'=>'striped bordered condensed',
		'data'=>$model,
		'attributes'=>array(
			'regions.financeEntities.advertisers.id',
			'regions.financeEntities.advertisers.name',
			'regions.financeEntities.advertisers.cat',
			'regions.financeEntities.advertisers.status',
		),
	)); ?>


	<h5>Insertion Order</h5>
	<?php $this->widget('bootstrap.widgets.TbDetailView', array(
	    'type'=>'striped bordered condensed',
		'data'=>$model,
		'attributes'=>array(
			'regions.financeEntities.id',
			'regions.financeEntities.name',
			'regions.financeEntities.commercial_name',
			'regions.financeEntities.status',
			'regions.financeEntities.prospect',
			// 'opportunities.ios.address',
			array(
				'label' => 'Country',
				'name'  => 'ios.country.name',
			),
			'regions.financeEntities.state',
			'regions.financeEntities.zip_code',
			'regions.financeEntities.phone',
			'regions.financeEntities.contact_com',
			'regions.financeEntities.email_adm',
			'regions.financeEntities.contact_adm',
			'regions.financeEntities.currency',
			'regions.financeEntities.ret',
			'regions.financeEntities.tax_id',
			'regions.financeEntities.net_payment',
			'regions.financeEntities.entity',
		),
	)); ?>

	<h5>Commercial Manager</h5>
	<?php $this->widget('bootstrap.widgets.TbDetailView', array(
	    'type'=>'striped bordered condensed',
		'data'=>$model,
		'attributes'=>array(
			'regions.financeEntities.commercial.id',
			'regions.financeEntities.commercial.name',
			'regions.financeEntities.commercial.lastname',
			'regions.financeEntities.commercial.username',
		),
	)); ?>

	<h5>Opportunity</h5>
	<?php $this->widget('bootstrap.widgets.TbDetailView', array(
	    'type'=>'striped bordered condensed',
		'data'=>$model,
		'attributes'=>array(
			'id',
			array(
				'label' =>$model->getAttributeLabel('carrier_mobile_brand'),
				'name'  =>'carriers.mobile_brand'
			),
			'rate',
			'model_adv',
			'product',
			'comment',
			array(
				'label' =>$model->getAttributeLabel('country_name'),
				'name'  =>'country.name'
			),
			array(
				'label' =>$model->getAttributeLabel('wifi'),
				'value' =>$model->wifi ? "Habilitado" : "Inhabilitado",
			),
			'budget',
			'server_to_server',
			'startDate',
			'endDate',
			'freq_cap',
			'imp_per_day',
			'imp_total',
			'targeting',
			'sizes',
			'channel',
			'channel_description',
			'status',
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
<h5>Campaigns</h5>
<?php 

	$campaign=new Campaigns;
	$this->widget('bootstrap.widgets.TbExtendedGridView', array(
	'id'                       => 'campaigns-grid',
	'dataProvider'             => $campaign->findByOpportunities($model->id),
	'type'                     => 'striped condensed',
	// 'fixedHeader'              => true,
	'headerOffset'             => 50,
	'enableSorting' => false,
	'rowHtmlOptionsExpression' => 'array("data-row-id" => $data->id)',
	'template'                 =>'{items} {pager} {summary}',
	
	'columns'                  =>array(
		array(
			'name'  => 'account_manager',
			'value' => '$data->opportunities->accountManager ? $data->opportunities->accountManager->lastname . " " . $data->opportunities->accountManager->name : ""',
        	'htmlOptions'	=> array('style' => 'width: 120px'),
		),
		array(
			'name'              => 'advertisers_name',
			'value'             => '$data->opportunities->regions->financeEntities->advertisers->name',
			'headerHtmlOptions' => array('style' => 'width: 80px'),
        ),
		array(
			'name'              => 'ios_name',
			'value'             => '$data->opportunities->regions->financeEntities->name',
			'headerHtmlOptions' => array('style' => 'width: 60px'),
        ),
		array(
			'name'              => 'name',
			'value'             => '$data->getExternalName($data->id)',
			'headerHtmlOptions' => array('style' => 'width: 300px'),
        ),
		array(
			'name'              => 'net_currency',
			'headerHtmlOptions' => array('style' => 'width: 20px'),
			'value'             => '$data->providers->currency',
        ),
		array(
			'name'              => 'cap',
			'headerHtmlOptions' => array('style' => 'width: 60px'),
			'value'             => '$data->cap',
        ),
	),
)); ?>
</div>
<div class="modal-footer">
    Opportunity detail view.
</div>
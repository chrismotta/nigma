<?php
/* @var $this PublishersController */
/* @var $model Publishers */

// Config parameters depending if have to show Archived or Admin view
if( isset($isArchived) ) {
	$delete['icon']       = 'refresh';
	$delete['label']      = 'Restore';
	$delete['confirm']    = 'Are you sure you want to restore this publishers?';
	$breadcrumbs['title'] = 'Archived Publishers';
} else {
	$delete['icon']       = 'trash';
	$delete['label']      = 'Archive';
	$delete['confirm']    = 'Are you sure you want to archive this publishers?';
	$breadcrumbs['title'] = 'Publishers';
}

$this->breadcrumbs=array(
	$breadcrumbs['title'],
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#publishers-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<?php if( !isset($isArchived) )  : ?>
	<div class="botonera">
	<?php
	$this->widget('bootstrap.widgets.TbButton', array(
		'type'        => 'info',
		'label'       => 'Create Publisher',
		'block'       => false,
		'buttonType'  => 'ajaxButton',
		'url'         => 'create',
		'ajaxOptions' => array(
			'type'    => 'POST',
			'beforeSend' => 'function(data)
				{
			    	var dataInicial = "<div class=\"modal-header\"></div><div class=\"modal-body\" style=\"padding:100px 0px;text-align:center;\"><img src=\"'.  Yii::app()->theme->baseUrl .'/img/loading.gif\" width=\"40\" /></div><div class=\"modal-footer\"></div>";
					$("#modalPublishers").html(dataInicial);
					$("#modalPublishers").modal("toggle");
				}',
			'success' => 'function(data)
				{
	                    // console.log(this.url);
		                //alert("create");
						$("#modalPublishers").html(data);
				}',
			),
		'htmlOptions' => array('id' => 'create'),
		)
	);
	?>
	</div>
<?php endif; ?>
<br>

<?php $this->widget('bootstrap.widgets.TbExtendedGridView', array(
	'id'                       =>'publishers-grid',
	'fixedHeader'              => true,
	'headerOffset'             => 50,
	'dataProvider'             => $model->search(),
	'filter'                   => $model,
	'type'                     => 'striped condensed',
	'rowHtmlOptionsExpression' => 'array("data-row-id" => $data->providers_id)',
	'template'                 => '{items} {pager} {summary}',
	'columns'                  => array(
		// 'state',
		// 'zipcode',
		// 'address',
		// 'phone',
		// 'currency',
		// 'status',
		// 'contact_com',
		// 'email_com',
		// 'contact_adm',
		// 'email_adm',
		array(
			'name' => 'providers_id',
		),
		array(
			'name' => 'providers_name',
			'value' => '$data->providers->name',
		),
		/*
		array( 
			'name' => 'commercial_name',
		),
		array(
			'name'  =>'country_name',
			'value' => '$data->country ? $data->country->name : ""',		
		),
		array( 
			'name' => 'entity',
		),
		array( 
			'name' => 'tax_id',
		),
		array( 
			'name' => 'net_payment',
		),*/
		array(
			'name'  => 'account_manager_id',
			'value' => '$data->account_manager_id ? $data->accountManager->lastname . " " . $data->accountManager->name : ""',
		),/*
		array( 
			'name' => 'model',
		),
		array( 
			'name'  => 'RS_perc',
			'value' => 'number_format($data->RS_perc, 2)',
		),
		*/
		array( 
			'name' => 'rate',
			'value' => 'number_format($data->rate, 2)',
		),
		array(
			'class'             => 'bootstrap.widgets.TbButtonColumn',
			'headerHtmlOptions' => array('style' => "width: 120px"),
			'afterDelete'       => 'function(link, success, data) { if(data) alert(data); }',
			'buttons'           => array(
				'viewAjax' => array(
					'label' =>'Detail',
					'icon'  =>'eye-open',
					'click' =>'
				    function(){
				    	var id = $(this).parents("tr").attr("data-row-id");

						var dataInicial = "<div class=\"modal-header\"></div><div class=\"modal-body\" style=\"padding:100px 0px;text-align:center;\"><img src=\"'.  Yii::app()->theme->baseUrl .'/img/loading.gif\" width=\"40\" /></div><div class=\"modal-footer\"></div>";
						$("#modalPublishers").html(dataInicial);
						$("#modalPublishers").modal("toggle");

				    	$.post(
						"view/"+id,
						"",
						function(data)
							{
								//alert(data);
								$("#modalPublishers").html(data);
							}
						)
						return false;
				    }
				    ',
				),
				'updateAjax' => array(
					'label' => 'Update',
					'icon'  => 'pencil',
					'click' => '
				    function(){
				    	// get row id from data-row-id attribute
				    	var id = $(this).parents("tr").attr("data-row-id");
				    	
						var dataInicial = "<div class=\"modal-header\"></div><div class=\"modal-body\" style=\"padding:100px 0px;text-align:center;\"><img src=\"'.  Yii::app()->theme->baseUrl .'/img/loading.gif\" width=\"40\" /></div><div class=\"modal-footer\"></div>";
						
						$("#modalPublishers").html(dataInicial);
						$("#modalPublishers").modal("toggle");

				    	// use jquery post method to get updateAjax view in a modal window
				    	$.post(
						"update/"+id,
						"",
						function(data)
							{
								//alert(data);
								$("#modalPublishers").html(data);
							}
						)
						return false;
				    }
				    ',
				),
			),
			'deleteButtonIcon'   => $delete['icon'],
			'deleteButtonLabel'  => $delete['label'],
			'deleteConfirmation' => $delete['confirm'],
			'template' => '{viewAjax} {updateAjax} {delete}',
		),
	),
)); ?>

<?php $this->beginWidget('bootstrap.widgets.TbModal', array('id'=>'modalPublishers')); ?>

		<div class="modal-header"></div>
        <div class="modal-body"></div>
        <div class="modal-footer"></div>

<?php $this->endWidget(); ?>
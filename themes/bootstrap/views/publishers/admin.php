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
	$breadcrumbs['title'] = 'Manage Publishers';
}

$this->breadcrumbs=array(
	'Publishers'=>array('index'),
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
			'name' =>'providers_id',
		),
		array(
			'name'  =>'providers_name',
			'value' =>'$data->providers->name'
		),
		array( 
			'header' =>Providers::model()->getAttributeLabel('commercial_name'),
			'value'  =>'$data->providers->commercial_name',
		),
		array(
			'header' =>Providers::model()->getAttributeLabel('country_id'),
			'value'  =>'$data->providers->country ? $data->providers->country->name : ""',
		),
		array( 
			'header' =>Providers::model()->getAttributeLabel('entity'),
			'value'  =>'$data->providers->entity',
		),
		array( 
			'header' =>Providers::model()->getAttributeLabel('tax_id'),
			'value'  =>'$data->providers->tax_id',
		),
		array( 
			'header' =>Providers::model()->getAttributeLabel('net_payment'),
			'value'  =>'$data->providers->net_payment',
		),
		array(
			'name'  =>'account_manager_id',
			'value' =>'$data->account_manager_id ? $data->accountManager->lastname . " " . $data->accountManager->name : ""',
		),
		array( 
			'header' =>Providers::model()->getAttributeLabel('model'),
			'value'  =>'$data->providers->model',
		),
		array( 
			'name'  =>'RS_perc',
			'value' =>'number_format($data->RS_perc, 2)',
		),
		array( 
			'name'  =>'rate',
			'value' =>'number_format($data->rate, 2)',
		),
		array(
			'class'             => 'bootstrap.widgets.TbButtonColumn',
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
				'exportPdf' => array(
					'label'   => 'Export PDF',
					'icon'    => 'download',
					'url'     => 'Yii::app()->getBaseUrl(true) . "/providers/exportPdf/" . $data->providers_id',
					'options' => array('target' => '_blank'),
				),
				'uploadPdf' => array(
					'label' => 'Upload Signed IO',
					'icon'  => 'upload',
					'click' => '
				    function(){
				    	// get row id from data-row-id attribute
				    	var id = $(this).parents("tr").attr("data-row-id");

				    	var dataInicial = "<div class=\"modal-header\"></div><div class=\"modal-body\" style=\"padding:100px 0px;text-align:center;\"><img src=\"'.  Yii::app()->theme->baseUrl .'/img/loading.gif\" width=\"40\" /></div><div class=\"modal-footer\"></div>";
						$("#modalPublishers").html(dataInicial);
						$("#modalPublishers").modal("toggle");

				    	// use jquery post method to get updateAjax view in a modal window
				    	$.post(
						"'. Yii::app()->getBaseUrl(true) . '/providers/uploadPdf/"+id,
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
				'viewPdf' => array(
					'label'   => 'View Signed IO',
					'icon'    => 'file',
					'url'     => 'Yii::app()->getBaseUrl(true) . "/providers/viewPdf/" . $data->providers_id',
					'options' => array('target' => '_blank'),
					'visible' => '$data->providers->prospect >= 9 ? true : false',
				),
				'viewAgreement' => array(
					'label'   => 'View Signed Agreement',
					'icon'    => 'file',
					'url'     => 'Yii::app()->getBaseUrl(true) . "/providers/viewAgreement/" . $data->providers_id',
					'options' => array('target' => '_blank'),
					'visible' => '$data->providers->prospect >= 10 ? true : false',
				),
				'agreementPdf' => array(
					'label'   => 'Export Agreement',
					'icon'    => 'download',
					'url'     => 'Yii::app()->getBaseUrl(true) . "/providers/agreementPdf/" . $data->providers_id',
					'options' => array('target' => '_blank'),
				),
				'uploadPdf' => array(
					'label' => 'Upload Signed IO',
					'icon'  => 'upload',
					'click' => '
				    function(){
				    	// get row id from data-row-id attribute
				    	var id = $(this).parents("tr").attr("data-row-id");

				    	var dataInicial = "<div class=\"modal-header\"></div><div class=\"modal-body\" style=\"padding:100px 0px;text-align:center;\"><img src=\"'.  Yii::app()->theme->baseUrl .'/img/loading.gif\" width=\"40\" /></div><div class=\"modal-footer\"></div>";
						$("#modalPublishers").html(dataInicial);
						$("#modalPublishers").modal("toggle");

				    	// use jquery post method to get updateAjax view in a modal window
				    	$.post(
						"'. Yii::app()->getBaseUrl(true) . '/providers/uploadPdf/"+id,
						{"type":"io"},
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
				'uploadAgreement' => array(
					'label' => 'Upload Signed Agreement',
					'icon'  => 'upload',
					'click' => '
				    function(){
				    	// get row id from data-row-id attribute
				    	var id = $(this).parents("tr").attr("data-row-id");

				    	var dataInicial = "<div class=\"modal-header\"></div><div class=\"modal-body\" style=\"padding:100px 0px;text-align:center;\"><img src=\"'.  Yii::app()->theme->baseUrl .'/img/loading.gif\" width=\"40\" /></div><div class=\"modal-footer\"></div>";
						$("#modalPublishers").html(dataInicial);
						$("#modalPublishers").modal("toggle");

				    	// use jquery post method to get updateAjax view in a modal window
				    	$.post(
						"'. Yii::app()->getBaseUrl(true) . '/providers/uploadPdf/"+id,
						{"type":"agreement"},
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
				'externalForm' => array(
					'label' => 'External Form',
					'icon'  => 'repeat',
					'click' => '
				    function(){
				    	// get row id from data-row-id attribute
				    	var id = $(this).parents("tr").attr("data-row-id");

				    	var dataInicial = "<div class=\"modal-header\"></div><div class=\"modal-body\" style=\"padding:100px 0px;text-align:center;\"><img src=\"'.  Yii::app()->theme->baseUrl .'/img/loading.gif\" width=\"40\" /></div><div class=\"modal-footer\"></div>";
						$("#modalPublishers").html(dataInicial);
						$("#modalPublishers").modal("toggle");

				    	// use jquery post method to get updateAjax view in a modal window
				    	$.post(
						"'. Yii::app()->getBaseUrl(true) . '/providers/externalForm/"+id,
						{"type":"finance"},
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
			'template' => '{viewAjax} {updateAjax} {delete} {exportPdf} {uploadPdf} {viewPdf} {agreementPdf} {uploadAgreement} {viewAgreement} {externalForm}',
		),
	),
)); ?>

<?php $this->beginWidget('bootstrap.widgets.TbModal', array('id'=>'modalPublishers')); ?>

		<div class="modal-header"></div>
        <div class="modal-body"></div>
        <div class="modal-footer"></div>

<?php $this->endWidget(); ?>
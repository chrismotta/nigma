<?php
/* @var $this IosController */
/* @var $model Ios */

// Config parameters depending if have to show Archived or Admin view
if( isset($isArchived) ) {
	$delete['icon']       = 'refresh';
	$delete['label']      = 'Restore';
	$delete['confirm']    = 'Are you sure you want to restore this IO?';
	$breadcrumbs['title'] = 'Archived Insertion Orders';
} else {
	$delete['icon']       = 'trash';
	$delete['label']      = 'Archive';
	$delete['confirm']    = 'Are you sure you want to archive this IO?';
	$breadcrumbs['title'] = 'Manage Insertion Orders';
}

$this->breadcrumbs=array(
	'Ios'=>array('index'),
	$breadcrumbs['title']
);

$this->menu=array(
	array('label'=>'List Ios', 'url'=>array('index')),
	array('label'=>'Create Ios', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#ios-grid').yiiGridView('update', {
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
		'label'       => 'Create IO',
		'block'       => false,
		'buttonType'  => 'ajaxButton',
		'url'         => 'create',
		'ajaxOptions' => array(
			'type'    => 'POST',
			'beforeSend' => 'function(data)
				{
			    	var dataInicial = "<div class=\"modal-header\"></div><div class=\"modal-body\" style=\"padding:100px 0px;text-align:center;\"><img src=\"'.  Yii::app()->theme->baseUrl .'/img/loading.gif\" width=\"40\" /></div><div class=\"modal-footer\"></div>";
					$("#modalIos").html(dataInicial);
					$("#modalIos").modal("toggle");
				}',
			'success' => 'function(data)
				{
					$("#modalIos").html(data);
				}',
			),
		'htmlOptions' => array('id' => 'create'),
		)
	);
	?>
	</div>
<?php endif; ?>

<?php $this->widget('bootstrap.widgets.TbGridView', array(
	'id'                       =>'ios-grid',
	'dataProvider'             => $model->search(),
	'filter'                   => $model,
	'type'                     => 'striped condensed',
	'rowHtmlOptionsExpression' => 'array("data-row-id" => $data->id)',
	'template'                 => '{items} {pager} {summary}',
	'columns'=>array(
		array(
			'name'=>'id',
			'headerHtmlOptions' => array('style' => "width: 60px"),
		),
		array(
			'name'=>'advertiser_name',
			'value'=> '$data->advertisers->name',
			'headerHtmlOptions' => array('style' => "width: 80px"),
		),
		array(
			'name'=>'name',
			'headerHtmlOptions' => array('style' => "width: 100px"),
		),
		// 'status',
		'commercial_name',
		array(
		 	'name'=>'country_name',
		 	'value'=> '$data->country ? $data->country->name : ""',			
		),
		// 'address',
		// 'state',
		// 'zip_code',
		// 'phone',
		// 'email',
		'contact_com',
		'contact_adm',
		// 'currency',
		// 'ret',
		// 'tax_id',
		//'net_payment',
		array(
			'name'=>'com_lastname',
			'value'=> '$data->commercial ? $data->commercial->lastname . " " . $data->commercial->name : ""',
		),
		array(
			'name'=>'entity',
			'headerHtmlOptions' => array('style' => "width: 30px"),
		),
		array(
			'class'             => 'bootstrap.widgets.TbButtonColumn',
			'headerHtmlOptions' => array('style' => "width: 120px"),
			'afterDelete'       => 'function(link, success, data) { if(data) alert(data); }',
			'buttons'           => array(
				'viewAjax' => array(
					'label' =>'Detail',
					'icon'  =>'eye-open',
					'url'   => '"javascript:;"',
					'click' =>'
				    function(){
				    	var id = $(this).parents("tr").attr("data-row-id");
				    	$.post(
						"view/"+id,
						"",
						function(data)
							{
								//alert(data);
								$("#modalIos").html(data);
								$("#modalIos").modal("toggle");
							}
						)
				    }
				    ',
				),
				'updateAjax' => array(
					'label' => 'Update',
					'icon'  => 'pencil',
					'url'   => '"javascript:;"',
					'click' => '
				    function(){
				    	// get row id from data-row-id attribute
				    	var id = $(this).parents("tr").attr("data-row-id");
				    	
						var dataInicial = "<div class=\"modal-header\"></div><div class=\"modal-body\" style=\"padding:100px 0px;text-align:center;\"><img src=\"'.  Yii::app()->theme->baseUrl .'/img/loading.gif\" width=\"40\" /></div><div class=\"modal-footer\"></div>";
						$("#modalIos").html(dataInicial);
						$("#modalIos").modal("toggle");

				    	// use jquery post method to get updateAjax view in a modal window
				    	$.post(
						"update/"+id,
						"",
						function(data)
							{
								//alert(data);
								$("#modalIos").html(data);
							}
						)
				    }
				    ',
				),
				'duplicateAjax' => array(
					'label' => 'Duplicate',
					'icon'  => 'plus-sign',
					'url'   => '"javascript:;"',
					'click' => '
				    function(){
				    	// get row id from data-row-id attribute
				    	var id = $(this).parents("tr").attr("data-row-id");

						var dataInicial = "<div class=\"modal-header\"></div><div class=\"modal-body\" style=\"padding:100px 0px;text-align:center;\"><img src=\"'.  Yii::app()->theme->baseUrl .'/img/loading.gif\" width=\"40\" /></div><div class=\"modal-footer\"></div>";
						$("#modalIos").html(dataInicial);
						$("#modalIos").modal("toggle");

				    	// use jquery post method to get updateAjax view in a modal window
				    	$.post(
						"duplicate/"+id,
						"",
						function(data)
							{
								//alert(data);
								$("#modalIos").html(data);
							}
						)
				    }
				    ',
				),
				'generatePdf' => array(
					'label'   => 'Generate PDF',
					'icon'    => 'download',
					'url'     => 'Yii::app()->getBaseUrl(true) . "/ios/generatePdf/" . $data->id',
					'options' => array('target' => '_blank'),
					//'visible' => '$data->status == 10 ? false : true',
				),
				'uploadPdf' => array(
					'label' => 'Upload Signed IO',
					'icon'  => 'upload',
					'url'   => '"javascript:;"',
					'click' => '
				    function(){
				    	// get row id from data-row-id attribute
				    	var id = $(this).parents("tr").attr("data-row-id");
				    	// use jquery post method to get updateAjax view in a modal window
				    	$.post(
						"uploadPdf/"+id,
						"",
						function(data)
							{
								//alert(data);
								$("#modalIos").html(data);
								$("#modalIos").modal("toggle");
							}
						)
				    }
				    ',
				),
				'viewPdf' => array(
					'label'   => 'View Signed IO',
					'icon'    => 'file',
					'url'     => 'Yii::app()->getBaseUrl(true) . "/ios/viewPdf/" . $data->id',
					'options' => array('target' => '_blank'),
					'visible' => '$data->prospect == 10 ? true : false',
				)
			),
			'deleteButtonIcon'   => $delete['icon'],
			'deleteButtonLabel'  => $delete['label'],
			'deleteConfirmation' => $delete['confirm'],
			'template' => '{viewAjax} {updateAjax} {duplicateAjax} {generatePdf} {uploadPdf} {viewPdf} {delete}',
		),
	),
)); ?>

<?php $this->beginWidget('bootstrap.widgets.TbModal', array('id'=>'modalIos')); ?>

		<div class="modal-header"></div>
        <div class="modal-body"></div>
        <div class="modal-footer"></div>

<?php $this->endWidget(); ?>

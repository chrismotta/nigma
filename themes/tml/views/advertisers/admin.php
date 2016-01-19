<?php
/* @var $this AdvertisersController */
/* @var $model Advertisers */

// Config parameters depending if have to show Archived or Admin view
if( isset($isArchived) ) {
	$delete['icon']       = 'refresh';
	$delete['label']      = 'Restore';
	$delete['confirm']    = 'Are you sure you want to restore this advertiser?';
	$breadcrumbs['title'] = 'Archived Advertisers';
} else {
	$delete['icon']       = 'trash';
	$delete['label']      = 'Archive';
	$delete['confirm']    = 'Are you sure you want to archive this advertiser?';
	$breadcrumbs['title'] = 'Advertisers';
}

$this->breadcrumbs=array(
	$breadcrumbs['title'],
);

$this->menu=array(
	array('label'=>'List Advertisers', 'url'=>array('index')),
	array('label'=>'Create Advertisers', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#advertisers-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<?php if( !isset($isArchived) )  : ?>
	<div class="botonera">
	<?php
	// $this->widget('bootstrap.widgets.TbButton', array(
	// 	'type'        => 'info',
	// 	'label'       => 'Create Advertiser',
	// 	'block'       => false,
	// 	'buttonType'  => 'ajaxButton',
	// 	'url'         => 'create',
	// 	'ajaxOptions' => array(
	// 		'type'    => 'POST',
	// 		'beforeSend' => 'function(data)
	// 			{
	// 		    	var dataInicial = "<div class=\"modal-header\"></div><div class=\"modal-body\" style=\"padding:100px 0px;text-align:center;\"><img src=\"'.  Yii::app()->theme->baseUrl .'/img/loading.gif\" width=\"40\" /></div><div class=\"modal-footer\"></div>";
	// 				$("#modalAdvertiser").html(dataInicial);
	// 				$("#modalAdvertiser").modal("toggle");
	// 			}',
	// 		'success' => 'function(data)
	// 			{
	//                     // console.log(this.url);
	// 	                //alert("create");
	// 					$("#modalAdvertiser").html(data);
	// 			}',
	// 		),
	// 	'htmlOptions' => array('id' => 'create'),
	// 	)
	// );
	?>

	<?php $this->widget('bootstrap.widgets.TbButton', array(
		'type'        => 'info',
		'label'       => 'Create Advertiser',
		'block'       => false,
		'buttonType'  => 'linkButton',
		'url'         => 'create',
		'htmlOptions' => array(
			"data-grid-id"      => "advertisers-grid", 
			"data-modal-id"     => "modalAdvertiser", 
			"data-modal-title"  => "Create Advertiser", 
			'onclick'           => 'event.preventDefault(); openModal(this)',
			),
		)
	); ?>

	</div>
<?php endif; ?>

<?php $this->widget('application.components.NiExtendedGridView', array(
	'id'                       => 'advertisers-grid',
	'dataProvider'             => $model->search(),
	'filter'                   => $model,
	'type'                     => 'striped condensed',
	'template'                 => '{items} {pagerExt} {summary}',
	'rowHtmlOptionsExpression' => 'array(
		"data-row-id" => $data->id, 
		"class" => "deepLink",
		"onclick" => "deepLink(\"'.Yii::app()->createUrl('financeEntities/admin').'?advertiser=\"+$data->id)",
		)',
	'columns'=>array(
		array(
			'name'=>'id',
			'htmlOptions'=>array('style' => 'width: 100px'),
		),
		array(
			'name'=>'name',
		),
		'prefix',
		'cat',
		array(
			'name'=>'commercial_lastname',
			'value'=>'$data->commercial ? $data->commercial->lastname . " " .$data->commercial->name : ""',
		),
		array(
			'class'             => 'bootstrap.widgets.TbButtonColumn',
			'headerHtmlOptions' => array('style' => "width: 70px"),
			'htmlOptions' => array('onclick' => 'prevent=1;'),
			'afterDelete' => 'function(link, success, data) { if(data) alert(data); }',
			'buttons'           => array(
				'viewAjax' => array(
					'label' =>'Detail',
					'icon'  =>'eye-open',
					'click' =>'
				    function(event){
						// event.stopPropagation();
						// return false;

				    	var id = $(this).parents("tr").attr("data-row-id");

						var dataInicial = "<div class=\"modal-header\"></div><div class=\"modal-body\" style=\"padding:100px 0px;text-align:center;\"><img src=\"'.  Yii::app()->theme->baseUrl .'/img/loading.gif\" width=\"40\" /></div><div class=\"modal-footer\"></div>";
						$("#modalAdvertiser").html(dataInicial);
						$("#modalAdvertiser").modal("toggle");

				    	$.post(
						"viewAjax/"+id,
						"",
						function(data)
							{
								//alert(data);
								$("#modalAdvertiser").html(data);
							}
						)
						event.stopPropagation();
						return false;
				    }
				    ',
				),
				'updateIframe' => array(
					'label' => 'Update',
					'icon'  => 'pencil',
					'url'     => 'array("update", "id" => $data->id)',
					'options' => array(
						"data-grid-id"      => "advertisers-grid", 
						"data-modal-id"     => "modalAdvertiser", 
						"data-modal-title"  => "Update Advertiser", 
						'onclick'           => 'event.preventDefault(); openModal(this)',
						),
					),
				// 'updateAjax' => array(
				// 	'label' => 'Update',
				// 	'icon'  => 'pencil',
				// 	'click' => '
				//     function(){
				//     	// get row id from data-row-id attribute
				//     	var id = $(this).parents("tr").attr("data-row-id");

				// 		var dataInicial = "<div class=\"modal-header\"></div><div class=\"modal-body\" style=\"padding:100px 0px;text-align:center;\"><img src=\"'.  Yii::app()->theme->baseUrl .'/img/loading.gif\" width=\"40\" /></div><div class=\"modal-footer\"></div>";
				// 		$("#modalAdvertiser").html(dataInicial);
				// 		$("#modalAdvertiser").modal("toggle");

				//     	// use jquery post method to get updateAjax view in a modal window
				//     	$.post(
				// 		"update/"+id,
				// 		"",
				// 		function(data)
				// 			{
				// 				//alert(data);
				// 				$("#modalAdvertiser").html(data);
				// 			}
				// 		)

				// 		// event.stopPropagation();
				// 		// return false;
				//     }
				//     ',
				// ),
				'externalForm' => array(
					'label' => 'External Form',
					'icon'  => 'repeat',
					'click' => '
				    function(){
				    	// get row id from data-row-id attribute
				    	var id = $(this).parents("tr").attr("data-row-id");

						var dataInicial = "<div class=\"modal-header\"></div><div class=\"modal-body\" style=\"padding:100px 0px;text-align:center;\"><img src=\"'.  Yii::app()->theme->baseUrl .'/img/loading.gif\" width=\"40\" /></div><div class=\"modal-footer\"></div>";
						$("#modalAdvertiser").html(dataInicial);
						$("#modalAdvertiser").modal("toggle");

				    	// use jquery post method to get updateAjax view in a modal window
				    	$.post(
						"externalForm/"+id,
						"",
						function(data)
							{
								//alert(data);
								$("#modalAdvertiser").html(data);
							}
						)
						return false;
				    }
				    ',
				)
			),
			'deleteButtonIcon'   => $delete['icon'],
			'deleteButtonLabel'  => $delete['label'],
			'deleteConfirmation' => $delete['confirm'],
			'template' => '{viewAjax} {externalForm} {updateIframe} {delete}',
		),
	),
)); ?>

<?php BuildGridView::printModal($this, 'modalAdvertiser', 'Advertiser'); ?>

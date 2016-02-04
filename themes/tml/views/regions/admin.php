<?php
/* @var $this OpportunitiesController */
/* @var $model Opportunities */
// Config parameters depending if have to show Archived or Admin view
if( isset($isArchived) ) {
	$delete['icon']       = 'refresh';
	$delete['label']      = 'Restore';
	$delete['confirm']    = 'Are you sure you want to restore this finance entitie?';
	$breadcrumbs['title'] = 'Archived Finance Entities';
} else {
	$delete['icon']       = 'trash';
	$delete['label']      = 'Archive';
	$delete['confirm']    = 'Are you sure you want to archive this finance entitie?';
	$breadcrumbs['title'] = 'Regions';
}

$this->breadcrumbs=array(
	'Advertisers'=>array('advertisers/admin'),
	'FinanceEntities'=>array('financeEntities/admin'),
	$breadcrumbs['title'],
);


Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#opportunities-grid').yiiGridView('update', {
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
	// 	'label'       => 'Create Region',
	// 	'block'       => false,
	// 	'buttonType'  => 'ajaxButton',
	// 	'url'         => 'create',
	// 	'ajaxOptions' => array(
	// 		'type'    => 'POST',
	// 		'beforeSend' => 'function(data)
	// 			{
	// 		    	var dataInicial = "<div class=\"modal-header\"></div><div class=\"modal-body\" style=\"padding:100px 0px;text-align:center;\"><img src=\"'.  Yii::app()->theme->baseUrl .'/img/loading.gif\" width=\"40\" /></div><div class=\"modal-footer\"></div>";
	// 				$("#modalRegions").html(dataInicial);
	// 				$("#modalRegions").modal("toggle");
	// 			}',
	// 		'success' => 'function(data)
	// 			{
	//                     // console.log(this.url);
	// 	                //alert("create");
	// 					$("#modalRegions").html(data);
	// 			}',
	// 		),
	// 	'htmlOptions' => array('id' => 'create'),
	// 	)
	// );
	?>
	<?php $this->widget('bootstrap.widgets.TbButton', array(
		'type'        => 'info',
		'label'       => 'Create Region',
		'block'       => false,
		'buttonType'  => 'linkButton',
		'url'         => 'create',
		'htmlOptions' => array(
			"data-grid-id"      => "regions-grid", 
			"data-modal-id"     => "modalRegions", 
			"data-modal-title"  => "Create Region", 
			'onclick'           => 'event.preventDefault(); openModal(this)',
			),
		)
	); ?>
	</div>
<?php endif; ?>
<br>

<?php 

$form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id'=>'filters-form',
        'type'=>'search',
        'htmlOptions'=>array('class'=>'well'),
        // to enable ajax validation
        'enableAjaxValidation'=>true,
        'action' => Yii::app()->getBaseUrl() . '/regions/admin',
        'method' => 'GET',
        'clientOptions'=>array('validateOnSubmit'=>true, 'validateOnChange'=>true),
    )); ?> 

	<fieldset>
	<?php echo KHtml::filterFinanceEntities($financeEntities); ?>

    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'label'=>'Filter', 'htmlOptions' => array('class' => 'showLoading'))); ?>

    </fieldset>

<?php $this->endWidget(); ?>
<?php $this->widget('application.components.NiExtendedGridView', array(
	'id'                       => 'regions-grid',
	'dataProvider'             => $model->search($financeEntities),
	'filter'                   => $model,
	'type'                     => 'striped condensed',
	'rowHtmlOptionsExpression' => 'array(
		"data-row-id" => $data->id, 
		"class" => "deepLink",
		"onclick" => "deepLink(\"'.Yii::app()->createUrl('opportunities/admin').'?region=\"+$data->id)",
		)',
	'template'                 => '{items} {pagerExt} {summary}',
	'columns'                  =>array(
		array(
			'name'=>'id',
			'headerHtmlOptions' => array('style' => "width: 100px"),
		),
		array(
			'name'=>'finance_entities_name',
			'value'=>'$data->financeEntities->name',
			// 'headerHtmlOptions' => array('style' => "width: 60px"),
		),
		array(
			'name'=>'country_name',
			'value'=>'isset($data->country->name) ? $data->country->name : "no country"',
			// 'headerHtmlOptions' => array('style' => "width: 60px"),
		),
		array(
			'name'=>'region',
			// 'headerHtmlOptions' => array('style' => "width: 60px"),
		),
		array(
			'class'             => 'bootstrap.widgets.TbButtonColumn',
			'headerHtmlOptions' => array('style' => "width: 60px"),
			'afterDelete'       => 'function(link, success, data) { if(data) alert(data); }',
			'htmlOptions' => array('onclick' => 'prevent=1;'),
			'buttons'           => array(
				'viewAjax' => array(
					'label' =>'Detail',
					'icon'  =>'eye-open',
					'click' =>'
				    function(){
				    	var id = $(this).parents("tr").attr("data-row-id");

				    	var dataInicial = "<div class=\"modal-header\"></div><div class=\"modal-body\" style=\"padding:100px 0px;text-align:center;\"><img src=\"'.  Yii::app()->theme->baseUrl .'/img/loading.gif\" width=\"40\" /></div><div class=\"modal-footer\"></div>";
						$("#modalRegions").html(dataInicial);
						$("#modalRegions").modal("toggle");

				    	$.post(
						"view/"+id,
						"",
						function(data)
							{
								//alert(data);
								$("#modalRegions").html(data);
							}
						)
						return false;
				    }
				    ',
				),
				'updateIframe' => array(
					'label' => 'Update',
					'icon'  => 'pencil',
					'url'     => 'array("update", "id" => $data->id)',
					'options' => array(
						"data-grid-id"      => "regions-grid", 
						"data-modal-id"     => "modalRegions", 
						"data-modal-title"  => "Update Region", 
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
				// 		$("#modalRegions").html(dataInicial);
				// 		$("#modalRegions").modal("toggle");

				//     	// use jquery post method to get updateAjax view in a modal window
				//     	$.post(
				// 		"update/"+id,
				// 		"",
				// 		function(data)
				// 			{
				// 				//alert(data);
				// 				$("#modalRegions").html(data);
				// 			}
				// 		)
				// 		return false;
				//     }
				//     ',
				// ),
				'duplicateAjax' => array(
					'label' => 'Duplicate',
					'icon'  => 'plus-sign',
					'click' => '
				    function(){
				    	// get row id from data-row-id attribute
				    	var id = $(this).parents("tr").attr("data-row-id");

						var dataInicial = "<div class=\"modal-header\"></div><div class=\"modal-body\" style=\"padding:100px 0px;text-align:center;\"><img src=\"'.  Yii::app()->theme->baseUrl .'/img/loading.gif\" width=\"40\" /></div><div class=\"modal-footer\"></div>";
						$("#modalRegions").html(dataInicial);
						$("#modalRegions").modal("toggle");

				    	// use jquery post method to get updateAjax view in a modal window
				    	$.post(
						"duplicate/"+id,
						"",
						function(data)
							{
								//alert(data);
								$("#modalRegions").html(data);
							}
						)
						return false;
				    }
				    ',
				),
				/*'generatePdf' => array(
					'label'   => 'Generate PDF',
					'icon'    => 'download',
					'url'     => 'Yii::app()->getBaseUrl(true) . "/ios/generatePdf/" . $data->id',
					'options' => array('target' => '_blank'),
					//'visible' => '$data->status == 10 ? false : true',
				),
				'uploadPdf' => array(
					'label' => 'Upload Signed IO',
					'icon'  => 'upload',
					'click' => '
				    function(){
				    	// get row id from data-row-id attribute
				    	var id = $(this).parents("tr").attr("data-row-id");

				    	var dataInicial = "<div class=\"modal-header\"></div><div class=\"modal-body\" style=\"padding:100px 0px;text-align:center;\"><img src=\"'.  Yii::app()->theme->baseUrl .'/img/loading.gif\" width=\"40\" /></div><div class=\"modal-footer\"></div>";
						$("#modalRegions").html(dataInicial);
						$("#modalRegions").modal("toggle");

				    	// use jquery post method to get updateAjax view in a modal window
				    	$.post(
						"uploadPdf/"+id,
						"",
						function(data)
							{
								//alert(data);
								$("#modalRegions").html(data);
							}
						)
						return false;
				    }
				    ',
				),
				'viewPdf' => array(
					'label'   => 'View Signed IO',
					'icon'    => 'file',
					'url'     => 'Yii::app()->getBaseUrl(true) . "/ios/viewPdf/" . $data->id',
					'options' => array('target' => '_blank'),
					'visible' => '$data->prospect == 10 ? true : false',
				)*/
			),
			'deleteButtonIcon'   => $delete['icon'],
			'deleteButtonLabel'  => $delete['label'],
			'deleteConfirmation' => $delete['confirm'],
			//'template' => '{viewAjax} {updateAjax} {duplicateAjax} {generatePdf} {uploadPdf} {viewPdf} {delete}',
			'template' => '{viewAjax} {updateIframe} {delete}',
		),
	),
)); ?>

<?php BuildGridView::printModal($this, 'modalRegions', 'Region'); ?>

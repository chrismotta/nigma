<?php

// Config parameters depending if have to show Archived or Admin view
if( isset($isArchived) ) {
	$delete['icon']       = 'refresh';
	$delete['label']      = 'Restore';
	$delete['confirm']    = 'Are you sure you want to restore this vector?';
	$breadcrumbs['title'] = 'Archived Vectors';
} else {
	$delete['icon']       = 'trash';
	$delete['label']      = 'Archive';
	$delete['confirm']    = 'Are you sure you want to archive this vector?';
	$breadcrumbs['title'] = 'Manage vector';
}

$this->breadcrumbs=array(
	'Vectors'=>array('index'),
	$breadcrumbs['title'],
);

/*
$this->menu=array(
	array('label'=>'List Vectors','url'=>array('index')),
	array('label'=>'Create Vectors','url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('vectors-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
*/

?>

<?php if( !isset($isArchived) )  : ?>
	<div class="botonera">
	<?php
	$this->widget('bootstrap.widgets.TbButton', array(
		'type'        => 'info',
		'label'       => 'Create Vector',
		'block'       => false,
		'buttonType'  => 'ajaxButton',
		'url'         => 'create',
		'ajaxOptions' => array(
			'type'    => 'POST',
			'beforeSend' => 'function(data)
				{
			    	var dataInicial = "<div class=\"modal-header\"></div><div class=\"modal-body\" style=\"padding:100px 0px;text-align:center;\"><img src=\"'.  Yii::app()->theme->baseUrl .'/img/loading.gif\" width=\"40\" /></div><div class=\"modal-footer\"></div>";
					$("#modalVectors").html(dataInicial);
					$("#modalVectors").modal("toggle");
				}',
			'success' => 'function(data)
				{
	                    console.log(this.url);
		                //alert("create");
						$("#modalVectors").html(data);
				}',
			),
		'htmlOptions' => array('id' => 'createAjax'),
		)
	); ?>
	</div>
<?php endif; ?>

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'                       => 'vectors-grid',
	'dataProvider'             => $model->search(),
	'filter'                   => $model,
	'type'                     => 'striped condensed',
	'rowHtmlOptionsExpression' => 'array("data-row-id" => $data->id)',
	'template'                 =>'{items} {pager} {summary}',
	'columns'=>array(
		array(
			'name'              => 'id',
			'headerHtmlOptions' => array('style' => 'width: 80px'),
        ),
		array(
			'name'              => 'name',
			//'headerHtmlOptions' => array('style' => 'width: 80px'),
        ),
        array(
			'name'  => 'providers_id',
			'value' => '$data->providers->name',
			'headerHtmlOptions' => array('style' => 'width: 120px'),
        ),
        array(
			'name'  => 'rate',
			'value' => '$data->rate',
			'headerHtmlOptions' => array('style' => 'width: 120px'),
        ),        
        array(
			'name'  => 'campaigns_associated',
			'value' => 'join(CHtml::listData(VectorsHasCampaigns::model()->findAll(array("condition"=>"vectors_id=".$data->id)), "campaigns_id", "campaigns_id")," - ")',
			//'headerHtmlOptions' => array('style' => 'width: 80px'),
        ),
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
			'headerHtmlOptions' => array('style' => "width: 80px"),
			'buttons'           => array(
				'addCampaign' => array(
					'label' =>'Manage Campaigns',
					'icon'  =>'plus',
					'click' =>'
				    function(){
						var dataInicial = "<div class=\"modal-header\"></div><div class=\"modal-body\" style=\"padding:100px 0px;text-align:center;\"><img src=\"'.  Yii::app()->theme->baseUrl .'/img/loading.gif\" width=\"40\" /></div><div class=\"modal-footer\"></div>";
						$("#modalVectors").html(dataInicial);
						$("#modalVectors").modal("toggle");
				    	var id = $(this).parents("tr").attr("data-row-id");
				    	$.post(
						"updateRelation/"+id,
						"",
						function(data)
							{
								// alert(data);
								$("#modalVectors").html(data);
							}
						)
						return false;
				    }
				    ',
				),
				'addCampaignIframe' => array(
					'label' => 'Manage Campaigns',
					'icon'  => 'plus',
					'url'     => 'array("updateRelation", "id" => $data->id)',
					'options' => array(
						"data-grid-id"      => "regions-grid", 
						"data-modal-id"     => "modalVectors", 
						"data-modal-title"  => "Manage Campaigns", 
						'onclick'           => 'event.preventDefault(); openModal(this)',
						),
					),
				'updateAjax' => array(
					'label' =>'Update',
					'icon'  =>'pencil',
					'click' =>'
				    function(){
				    	var dataInicial = "<div class=\"modal-header\"></div><div class=\"modal-body\" style=\"padding:100px 0px;text-align:center;\"><img src=\"'.  Yii::app()->theme->baseUrl .'/img/loading.gif\" width=\"40\" /></div><div class=\"modal-footer\"></div>";
				    	$("#modalVectors").html(dataInicial);
						$("#modalVectors").modal("toggle");
				    	var id = $(this).parents("tr").attr("data-row-id");
				    	$.post(
						"update/"+id,
						"",
						function(data)
							{
								//alert(data);
								$("#modalVectors").html(data);
							}
						)
						return false;
				    }
				    ',
				),
				'duplicateIframe' => array(
					'label' => 'Duplicate',
					'icon'  => 'plus-sign',
					'url'     => 'array("duplicate", "id" => $data->id)',
					'options' => array(
						"data-grid-id"      => "regions-grid", 
						"data-modal-id"     => "modalVectors", 
						"data-modal-title"  => "Duplicate Vector", 
						'onclick'           => 'event.preventDefault(); openModal(this)',
					),
				),				
				'redirects' => array(
					'label' =>'Redirects',
					'icon'  =>'repeat',
					'click' =>'
				    function(){
				    	var id = $(this).parents("tr").attr("data-row-id");

				    	var dataInicial = "<div class=\"modal-header\"></div><div class=\"modal-body\" style=\"padding:100px 0px;text-align:center;\"><img src=\"'.  Yii::app()->theme->baseUrl .'/img/loading.gif\" width=\"40\" /></div><div class=\"modal-footer\"></div>";
						$("#modalVectors").html(dataInicial);
						$("#modalVectors").modal("toggle");

				    	$.post(
						"redirectAjax/"+id,
						"",
						function(data)
							{
								//alert(data);
								$("#modalVectors").html(data);
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
			'template' => '{addCampaignIframe} {updateAjax} {duplicateIframe} {redirects} {delete}',
		),
	),

)); ?>

<?php BuildGridView::printModal($this, 'modalVectors', 'Vector'); ?>

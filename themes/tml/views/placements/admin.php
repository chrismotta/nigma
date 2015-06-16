<?php
/* @var $this PlacementsController */
/* @var $model Placements */

// Config parameters depending if have to show Archived or Admin view
if( isset($isArchived) ) {
	$delete['icon']       = 'refresh';
	$delete['label']      = 'Restore';
	$delete['confirm']    = 'Are you sure you want to restore this placements?';
	$breadcrumbs['title'] = 'Archived Placements';
} else {
	$delete['icon']       = 'trash';
	$delete['label']      = 'Archive';
	$delete['confirm']    = 'Are you sure you want to archive this placements?';
	$breadcrumbs['title'] = 'Placements';
}

$this->breadcrumbs=array(
	'Publishers'=>array('publishers/admin'),
	'Sites'=>array('sites/admin'),
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
		'label'       => 'Create Placement',
		'block'       => false,
		'buttonType'  => 'ajaxButton',
		'url'         => 'create',
		'ajaxOptions' => array(
			'type'    => 'POST',
			'beforeSend' => 'function(data)
				{
			    	var dataInicial = "<div class=\"modal-header\"></div><div class=\"modal-body\" style=\"padding:100px 0px;text-align:center;\"><img src=\"'.  Yii::app()->theme->baseUrl .'/img/loading.gif\" width=\"40\" /></div><div class=\"modal-footer\"></div>";
					$("#modalPlacements").html(dataInicial);
					$("#modalPlacements").modal("toggle");
				}',
			'success' => 'function(data)
				{
	                    // console.log(this.url);
		                //alert("create");
						$("#modalPlacements").html(data);
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
	'id'                       =>'placements-grid',
	'fixedHeader'              => true,
	'headerOffset'             => 50,
	'dataProvider'             => $model->search(),
	'filter'                   => $model,
	'type'                     => 'striped condensed',
	'rowHtmlOptionsExpression' => 'array("data-row-id" => $data->id)',
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
			'name' => 'id',
		),
		array(
			'name' => 'name',
		),
		array( 
			'name'  => 'publishers_name',
			'value' => '$data->sites->publishersProviders->providers->name',
		),
		// array( 
		// 	'name'  => 'exchanges_name',
		// 	'value' => '$data->exchanges->name',
		// ),
		array( 
			'name'  => 'size',
			'value' => '$data->sizes->size',
		),
		'model',
		'publisher_percentage',
		'rate',
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
						$("#modalPlacements").html(dataInicial);
						$("#modalPlacements").modal("toggle");

				    	$.post(
						"view/"+id,
						"",
						function(data)
							{
								//alert(data);
								$("#modalPlacements").html(data);
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
						
						$("#modalPlacements").html(dataInicial);
						$("#modalPlacements").modal("toggle");

				    	// use jquery post method to get updateAjax view in a modal window
				    	$.post(
						"update/"+id,
						"",
						function(data)
							{
								//alert(data);
								$("#modalPlacements").html(data);
							}
						)
						return false;
				    }
				    ',
				),
				'waterfall' => array(
					'label' => 'Waterfall',
					'icon'  => 'tint',
					'click' => '
				    function(){
				    	// get row id from data-row-id attribute
				    	var id = $(this).parents("tr").attr("data-row-id");
				    	
						var dataInicial = "<img src=\"'.  Yii::app()->theme->baseUrl .'/img/loading.gif\" width=\"40\" />";
						
						$("#modalPlacements .modal-body").html(dataInicial);
						$("#modalPlacements").modal("toggle");

						var dataInicial = "<iframe src=\"'.$this->createUrl('placements/waterfall/1').'\" width=\"100%\" height=\"300px\" frameborder=\"0\" ></iframe>";
						$("#modalPlacements .modal-body").html(dataInicial);

				    	// use jquery post method to get updateAjax view in a modal window
				    	/*
				    	$.post(
						"waterfall/"+id,
						"",
						function(data)
							{
								//alert(data);
								console.log("modal")
								$("#modalPlacements").html(data);
							}
						)
						*/
						return false;
				    }
				    ',
				),
				'labelAjax' => array(
					'label' =>'Label',
					'icon'  =>'repeat',
					'click' =>'
				    function(){
				    	// get row id from data-row-id attribute
				    	var id = $(this).parents("tr").attr("data-row-id");
				    	
						var dataInicial = "<div class=\"modal-header\"></div><div class=\"modal-body\" style=\"padding:100px 0px;text-align:center;\"><img src=\"'.  Yii::app()->theme->baseUrl .'/img/loading.gif\" width=\"40\" /></div><div class=\"modal-footer\"></div>";
						
						$("#modalPlacements").html(dataInicial);
						$("#modalPlacements").modal("toggle");

				    	// use jquery post method to get updateAjax view in a modal window
				    	$.post(
						"labelAjax/"+id,
						"",
						function(data)
							{
								//alert(data);
								$("#modalPlacements").html(data);
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
			'template' => '{viewAjax} {updateAjax} {labelAjax} {waterfall} {delete}',
		),
	),
)); ?>

<?php $this->beginWidget('bootstrap.widgets.TbModal', array('id'=>'modalPlacements')); ?>

		<div class="modal-header">
		    <a class="close" data-dismiss="modal">&times;</a>
		    <h4>Waterfall for Placement <?php echo "#". $model->id; ?></h4>
		</div>
        <div class="modal-body"></div>
		<div class="modal-footer">
		    Edit Placemente Waterfall. Fields with <span class="required">*</span> are required.
		</div>

<?php $this->endWidget(); ?>
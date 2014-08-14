<?php
/* @var $this IosController */
/* @var $model Ios */

$this->breadcrumbs=array(
	'Ios'=>array('index'),
	'Manage Insertion Order',
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
		'success' => 'function(data)
			{
                    // console.log(this.url);
	                //alert("create");
					$("#modalIos").html(data);
					$("#modalIos").modal("toggle");
			}',
		),
	'htmlOptions' => array('id' => 'create'),
	)
);
?>
</div>

<?php $this->widget('bootstrap.widgets.TbGridView', array(
	'id'                       =>'ios-grid',
	'dataProvider'             => $model->search(),
	'filter'                   => $model,
	'type'                     => 'striped condensed',
	'rowHtmlOptionsExpression' => 'array("data-row-id" => $data->id)',
	'template'                 => '{items} {pager} {summary}',
	'columns'=>array(
		'id',
		array(
			'name'=>'advertiser_name',
			'value'=> '$data->advertisers->name'
		),
		// 'status',
		'name',
		array(
		 	'name'=>'country_name',
		 	'value'=> '$data->country ? $data->country->name : ""',			
		),
		// 'address',
		// 'state',
		// 'zip_code',
		// 'phone',
		// 'email',
		'contact_adm',
		// 'currency',
		// 'ret',
		// 'tax_id',
		// 'entity',
		'net_payment',
		array(
			'name'=>'commercial_lastname',
			'value'=> '$data->commercial ? $data->commercial->lastname . " " . $data->commercial->name : ""',
		),
		array(
			'class'             => 'bootstrap.widgets.TbButtonColumn',
			'headerHtmlOptions' => array('style' => "width: 85px"),
			'buttons'           => array(
				'viewAjax' => array(
					'label' =>'Detail',
					'icon'  =>'eye-open',
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
					'click' => '
				    function(){
				    	// get row id from data-row-id attribute
				    	var id = $(this).parents("tr").attr("data-row-id");
				    	// use jquery post method to get updateAjax view in a modal window
				    	$.post(
						"update/"+id,
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
				'duplicateAjax' => array(
					'label' => 'Duplicate',
					'icon'  => 'file',
					'click' => '
				    function(){
				    	// get row id from data-row-id attribute
				    	var id = $(this).parents("tr").attr("data-row-id");
				    	// use jquery post method to get updateAjax view in a modal window
				    	$.post(
						"duplicate/"+id,
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
				'generatePdf' => array(
					'label'   => 'Generate PDF',
					'icon'    => 'print',
					'url'     => 'Yii::app()->getBaseUrl(true) . "/ios/generatePdf/" . $data->id',
					'options' => array('target' => '_blank')
				)
			),
			'template' => '{viewAjax} {updateAjax} {duplicateAjax} {generatePdf} {delete}',
		),
	),
)); ?>

<?php $this->beginWidget('bootstrap.widgets.TbModal', array('id'=>'modalIos')); ?>

		<div class="modal-header"></div>
        <div class="modal-body"><h1>Insertion Order</h1></div>
        <div class="modal-footer"></div>

<?php $this->endWidget(); ?>

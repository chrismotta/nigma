<?php
$this->breadcrumbs=array(
	'Currency'=>array('admin'),
	'Manage Currency',
);

?>

<div class="botonera">
<?php
$this->widget('bootstrap.widgets.TbButton', array(
	'type'        => 'info',
	'label'       => 'Create Currency',
	'block'       => false,
	'buttonType'  => 'ajaxButton',
	'url'         => 'create',
	'ajaxOptions' => array(
		'type'    => 'POST',
		'success' => 'function(data)
			{
                    console.log(this.url);
	                //alert("create");
					$("#modalCurrency").html(data);
					$("#modalCurrency").modal("toggle");
			}',
		),
	'htmlOptions' => array('id' => 'createAjax'),
	)
);
?>
</div>

<?php // echo CHtml::link('Advanced Search','#',array('class'=>'search-button btn')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'                       => 'currency-grid',
	'dataProvider'             => $model->search(),
	'filter'                   => $model,
	'type'                     => 'striped condensed',
	'rowHtmlOptionsExpression' => 'array("data-row-id" => $data->id)',
	'template'                 =>'{items} {pager} {summary}',
	'columns'=>array(
		'id',
		'date',
		'ARS',
		'EUR',
		'BRL',
		'GBP',

		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
			'headerHtmlOptions' => array('style' => "width: 80px"),
			'buttons'           => array(
				'updateAjax' => array(
					'label' =>'Update',
					'icon'  => 'pencil',
					'click' =>'
				    function(){
				    	var id = $(this).parents("tr").attr("data-row-id");
				    	$.post(
						"update/"+id,
						"",
						function(data)
							{
								//alert(data);
								$("#modalCurrency").html(data);
								$("#modalCurrency").modal("toggle");
							}
						)
				    }
				    ',
				),
			),
			'template' => '{updateAjax} {delete}',
		),	
	),

)); ?>

<?php $this->beginWidget('bootstrap.widgets.TbModal', array('id'=>'modalCurrency')); ?>

		<div class="modal-header"></div>
        <div class="modal-body"><h1>Currency</h1></div>
        <div class="modal-footer"></div>

<?php $this->endWidget(); ?>
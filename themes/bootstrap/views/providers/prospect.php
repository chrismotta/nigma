<?php
/* @var $this AffiliatesController */
/* @var $model Affiliates */

$this->breadcrumbs=array(
	'Affiliates',
	'Manage',
);
?>

<div class="botonera">
	<?php
	$this->widget('bootstrap.widgets.TbButton', array(
		'type'        => 'info',
		'label'       => 'Create Prospect',
		'block'       => false,
		'buttonType'  => 'ajaxButton',
		'url'         => 'create',
		'ajaxOptions' => array(
			'type'    => 'POST',
			'beforeSend' => 'function(data)
				{
			    	var dataInicial = "<div class=\"modal-header\"></div><div class=\"modal-body\" style=\"padding:100px 0px;text-align:center;\"><img src=\"'.  Yii::app()->theme->baseUrl .'/img/loading.gif\" width=\"40\" /></div><div class=\"modal-footer\"></div>";
					$("#modalProspect").html(dataInicial);
					$("#modalProspect").modal("toggle");
				}',
			'success' => 'function(data)
				{
					$("#modalProspect").html(data);
				}',
			),
		'htmlOptions' => array('id' => 'create'),
		)
	);
	?>
</div>

<?php $this->widget('bootstrap.widgets.TbGridView', array(
	'id'                       =>'prospect-grid',
	'dataProvider'             => $model->search(1),
	'filter'                   => $model,
	'type'                     => 'striped condensed',
	'rowHtmlOptionsExpression' => 'array("data-row-id" => $data->id)',
	'template'                 => '{items} {pager} {summary}',
	'columns'=>array(
		array(
			'name'              =>'id',
			'headerHtmlOptions' => array('style' => "width: 60px"),
		),
		array(
			'name'              =>'name',
			'value'             =>'$data->name',
			'headerHtmlOptions' => array('style' => "width: 80px"),
		),
		array(
			'name'            =>'entity',
			'value'             => '$data->entity',
			'headerHtmlOptions' => array('style' => "width: 30px"),
		),
		array(
			'header'            =>'Type',
			'value'             => '$data->printType()',
			'headerHtmlOptions' => array('style' => "width: 30px"),
		),
		array(
			'class'             => 'bootstrap.widgets.TbButtonColumn',
			'headerHtmlOptions' => array('style' => "width: 120px"),
			'buttons'           => array(
				'externalForm' => array(
					'label' => 'External Form',
					'icon'  => 'repeat',
					'click' => '
				    function(){
				    	// get row id from data-row-id attribute
				    	var id = $(this).parents("tr").attr("data-row-id");

						var dataInicial = "<div class=\"modal-header\"></div><div class=\"modal-body\" style=\"padding:100px 0px;text-align:center;\"><img src=\"'.  Yii::app()->theme->baseUrl .'/img/loading.gif\" width=\"40\" /></div><div class=\"modal-footer\"></div>";
						$("#modalProspect").html(dataInicial);
						$("#modalProspect").modal("toggle");

				    	// use jquery post method to get updateAjax view in a modal window
				    	$.post(
						"'. Yii::app()->getBaseUrl(true) . '/providers/externalForm/"+id,
						{"type":"general"},
						function(data)
							{
								//alert(data);
								$("#modalProspect").html(data);
							}
						)
						return false;
				    }
				    ',
				),
			),
			'template' => '{externalForm}',
		),
	),
)); ?>

<?php $this->beginWidget('bootstrap.widgets.TbModal', array('id'=>'modalProspect')); ?>

		<div class="modal-header"></div>
        <div class="modal-body"></div>
        <div class="modal-footer"></div>

<?php $this->endWidget(); ?>
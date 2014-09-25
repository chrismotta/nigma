<?php
/* @var $this CampaignsController */
/* @var $model Campaigns */
$this->breadcrumbs=array(
	'Currency'=>array('index'),
	'Admin',
);
?>
<?php
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#campaigns-grid').yiiGridView('update', {
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
	'label'       => 'Create Currency',
	'block'       => false,
	'buttonType'  => 'ajaxButton',
	'url'         => 'createAjax',
	'ajaxOptions' => array(
		'type'    => 'POST',
		'beforeSend' => 'function(data)
			{
			    	var dataInicial = "<div class=\"modal-header\"></div><div class=\"modal-body\" style=\"padding:100px 0px;text-align:center;\"><img src=\"'.  Yii::app()->theme->baseUrl .'/img/loading.gif\" /></div><div class=\"modal-footer\"></div>";
					$("#modalCurrency").html(dataInicial);
					$("#modalCurrency").modal("toggle");
			}',
		'success' => 'function(data)
			{
                    //console.log(this.url);
	                //alert("create");
					$("#modalCurrency").html(data);
					//$("#modalCurrency").modal("toggle");
			}',
		),
	'htmlOptions' => array('id' => 'createAjax'),
	)
);
?>
</div>
<?php $this->widget('bootstrap.widgets.TbGridView', array(
	'id'                       => 'campaigns-grid',
	'dataProvider'             => $model->search(),
	'filter'                   => $model,
	'type'                     => 'striped condensed',
	'rowHtmlOptionsExpression' => 'array("data-row-id" => $data->id)',
	'template'                 =>'{items} {pager} {summary}',
	
	'columns'                  =>array(
		// para incluir columnas de tablas relacionadas con search y order
		// se usa la propiedad publica custom en 'name'
		// y la ruta relacional de la columna en 'value'
		'id',
		'date',
		'ARS',
		'EUR',
		'BRL',
		'GBP'
	),
)); ?>
<?php //echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php /* $this->renderPartial('_search',array(
	'model'=>$model,
)); */?>
</div><!-- search-form -->

<?php $this->beginWidget('bootstrap.widgets.TbModal', array('id'=>'modalCurrency')); ?>

		<div class="modal-header"></div>
        <div class="modal-body"></div>
        <div class="modal-footer"></div>

<?php $this->endWidget(); ?>

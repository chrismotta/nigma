<?php
$this->breadcrumbs=array(
	'Vectors'=>array('index'),
	'Manage Vectors',
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
		'success' => 'function(data)
			{
                    console.log(this.url);
	                //alert("create");
					$("#modalCampaigns").html(data);
					$("#modalCampaigns").modal("toggle");
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
			'class'=>'bootstrap.widgets.TbButtonColumn',
			'headerHtmlOptions' => array('style' => "width: 80px"),
			'buttons'           => array(
				'addCampaign' => array(
					'label' =>'Detail',
					'icon'  =>'plus',
					'click' =>'
				    function(){
				    	var id = $(this).parents("tr").attr("data-row-id");
				    	$.post(
						"addCampaign/"+id,
						"",
						function(data)
							{
								//alert(data);
								$("#modalCampaigns").html(data);
								$("#modalCampaigns").modal("toggle");
							}
						)
				    }
				    ',
				),
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
								$("#modalCampaigns").html(data);
								$("#modalCampaigns").modal("toggle");
							}
						)
				    }
				    ',
				),
			),
			'template' => '{addCampaign} {updateAjax} {delete}',
		),
	),

)); ?>

<?php $this->beginWidget('bootstrap.widgets.TbModal', array('id'=>'modalCampaigns')); ?>

		<div class="modal-header"></div>
        <div class="modal-body"><h1>Vectors</h1></div>
        <div class="modal-footer"></div>

<?php $this->endWidget(); ?>
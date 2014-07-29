<?php
/* @var $this CampaignsController */
/* @var $model Campaigns */

$this->breadcrumbs=array(
	'Campaigns'=>array('index'),
	'Manage Campaigns',
);

$this->menu=array(
	/*array('label'=>'List Campaigns', 'url'=>array('index')),*/
	array('label'=>'Create Campaigns', 'url'=>array('create')),
);
?>

<!--h2>Manage Campaigns</h2-->

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
	'label'       => 'Create Campaign',
	'block'       => false,
	'buttonType'  => 'ajaxButton',
	'url'         => 'createAjax',
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
		array(
			'name'              => 'advertisers_name',
			'value'             => '$data->opportunities->ios->advertisers->name',
			'headerHtmlOptions' => array('style' => 'width: 80px'),
        ),
		array(
			'name'              => 'opportunities_id',
			'headerHtmlOptions' => array('style' => 'width: 80px'),
        ),
		array(
			'name'              => 'id',
			'headerHtmlOptions' => array('style' => 'width: 80px'),
        ),
		array(
			'name'              => 'name',
			'headerHtmlOptions' => array('style' => 'width: 300px'),
        ),
		/*
		// ejemplos de como setear correctamente columnas relacionadas
		array(
			'name'   => 'opportunities_rate',
			'value'  => '$data->opportunities->rate',
        	'headerHtmlOptions' => array('style' => 'width: 80px'),
        ),
		array(
			'name'   => 'opportunities_carrier',
			'value'  => '$data->opportunities->carrier',
        	'headerHtmlOptions' => array('style' => 'width: 80px'),
        ),
		'offer_type',
		'currency',
		'budget_type',
		'budget', //sacar
		'model', //sacar
		'bid', //sacar
		'comment',
		*/
		array(
			'name'              => 'cap',
			'headerHtmlOptions' => array('style' => 'width: 60px'),
			'value'             => '"$ ".$data->cap',
        ),
		array(
			'name'              => 'status',
			'value'             => '$data->status', //== 0 ? "Active" : "Paused"',
			'headerHtmlOptions' => array('style' => 'width: 60px'),
        ),
		// array(
		// 	'name'              => 'date_start',
		// 	'value'             => 'date("d-m-Y", strtotime($data->date_start))',
		// 	'headerHtmlOptions' => array('style' => 'width: 70px'),
  //       ),
		// array(

		// 	'name'              => 'date_end',
		// 	'value'             => 'date("d-m-Y", strtotime($data->date_end))',
		// 	'headerHtmlOptions' => array('style' => 'width: 70px'),
  //       ),
		/*
		//ajax using CHtml::ajaxLink
		array(
            'type'=>'raw',
            'value'=>	'
            			CHtml::ajaxLink(
            				"<i class=\"icon-hand-up\"></i>",
	            			Yii::app()->controller->createUrl("redirectAjax"),
	        				array(
								"type" => "POST",
								"dataType" => "json",
								"data" => array("cid" => $data->primaryKey),
								"success" => "function( data )
									{
										$(\"#modalCampaigns .modal-header\").html(data[\"header\"]);
										$(\"#modalCampaigns .modal-body\").html(data[\"body\"]);
										$(\"#modalCampaigns .modal-footer\").html(data[\"footer\"]);
										$(\"#modalCampaigns\").modal(\"toggle\");
									}",
								),
							array(
								"class"=>"single-button-column",
								"rel"=>"tooltip",
								"data-original-title"=>"Redirects"
								)
						) . 
						CHtml::ajaxLink(
							"<i class=\"icon-pencil\"></i>",
	        				Yii::app()->controller->createUrl("updateAjax"),
	        				array(
								"type" => "POST",
								"dataType" => "json",
								"data" => array("cid" => $data->primaryKey),
								"success" => "function( data )
									{
										$(\"#modalCampaigns .modal-header\").html(data[\"header\"]);
										$(\"#modalCampaigns .modal-body\").html(data[\"body\"]);
										$(\"#modalCampaigns .modal-footer\").html(data[\"footer\"]);
										$(\"#modalCampaigns\").modal(\"toggle\");
									}",
								),
							array(
								"class"=>"single-button-column",
								"rel"=>"tooltip",
								"data-original-title"=>"Update"
								)
						);
						',
        	'headerHtmlOptions' => array('style' => 'width: 40px'),

        ),
        */
		array(
			'class'             => 'bootstrap.widgets.TbButtonColumn',
			'headerHtmlOptions' => array('style' => "width: 60px"),
			'buttons'           => array(
				'redirects' => array(
					'label' =>'Redirects',
					'icon'  =>'hand-up',
					'click' =>'
				    function(){
				    	var id = $(this).parents("tr").attr("data-row-id");
				    	$.post(
						"redirectAjax",
						"cid="+id,
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
					'label' => 'Update',
					'icon'  => 'pencil',
					'click' => '
				    function(){
				    	// get row id from data-row-id attribute
				    	var id = $(this).parents("tr").attr("data-row-id");
				    	// use jquery post method to get updateAjax view in a modal window
				    	$.post(
						"updateAjax/"+id,
						"cid="+id,
						function(data)
							{
								//alert(data);
								$("#modalCampaigns").html(data);
								$("#modalCampaigns").modal("toggle");
							}
						)
				    }
				    ',
				)
			),
			'template' => '{redirects} {updateAjax} {delete}',
		),
	),
)); ?>


<?php //echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php /* $this->renderPartial('_search',array(
	'model'=>$model,
)); */?>
</div><!-- search-form -->

<?php $this->beginWidget('bootstrap.widgets.TbModal', array('id'=>'modalCampaigns')); ?>

		<div class="modal-header"></div>
        <div class="modal-body"><h1>Campaigns</h1></div>
        <div class="modal-footer"></div>

<?php $this->endWidget(); ?>
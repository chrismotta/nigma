<?php
/* @var $this CampaignsController */
/* @var $model Campaigns */

$this->breadcrumbs=array(
	'Campaigns'=>array('index'),
	'Manage',
);

$this->menu=array(
	/*array('label'=>'List Campaigns', 'url'=>array('index')),*/
	array('label'=>'Create Campaigns', 'url'=>array('create')),
);

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

<h2>Manage Campaigns</h2>

<?php
/*
$this->widget('ext.rezvan.RDatePicker',array(
    'name'=>'Campaigns[date_start]',
    'value'=>$model->date_start,
    'options' => array(
        'autoclose'=>true,
        'format' => 'dd-mm-yyyy',
        'viewformat' => 'dd-mm-yyyy',
        'placement' => 'right'
    )
));
*/
?>

<?php $this->widget('bootstrap.widgets.TbGridView', array(
	'id' => 'campaigns-grid',
	'dataProvider' => $model->search(),
	'filter' => $model,
	'type' => 'striped condensed',
	'rowHtmlOptionsExpression' => 'array("row-id" => $data->id)',
	'columns'=>array(
		array(
			'name'   => 'id',
        	'headerHtmlOptions' => array('style' => 'width: 80px'),
        ),
		/*
		'rec',
		*/
		array(
			'name'   => 'opportunities_id',
        	'header' => 'Opp',
        	'headerHtmlOptions' => array('style' => 'width: 80px'),
        ),
		array(
			'name'   => 'name',
        	'headerHtmlOptions' => array('style' => 'width: 300px'),
        ),
		/*
		'campaign_categories_id',
		'offer_type',
		'currency',
		'budget_type',
		'budget', //sacar
		'model', //sacar
		'bid', //sacar
		'comment',
		*/
		array(
			'name'   => 'cap',
        	'headerHtmlOptions' => array('style' => 'width: 60px'),
        	'value'  => '"$ ".$data->cap',
        ),
		array(
			'name'   => 'status',
        	'value'  => '$data->status == 0 ? "Active" : "Paused"',
        	'headerHtmlOptions' => array('style' => 'width: 60px'),
        ),
		array(
			'name'   => 'date_start',
        	'value'  => 'date("d-m-Y", strtotime($data->date_start))',
        	'headerHtmlOptions' => array('style' => 'width: 70px'),
        ),
		array(

			'name'   => 'date_end',
        	'value'  => 'date("d-m-Y", strtotime($data->date_end))',
        	'headerHtmlOptions' => array('style' => 'width: 70px'),
        ),
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
										$(\"#myModal .modal-header\").html(data[\"header\"]);
										$(\"#myModal .modal-body\").html(data[\"body\"]);
										$(\"#myModal .modal-footer\").html(data[\"footer\"]);
										$(\"#myModal\").modal(\"toggle\");
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
										$(\"#myModal .modal-header\").html(data[\"header\"]);
										$(\"#myModal .modal-body\").html(data[\"body\"]);
										$(\"#myModal .modal-footer\").html(data[\"footer\"]);
										$(\"#myModal\").modal(\"toggle\");
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
			'class'=>'bootstrap.widgets.TbButtonColumn',
        	'headerHtmlOptions' => array('style' => "width: 60px"),
			'buttons'=>array(
				'redirects' => array(
				    'label'=>'Redirects',
                    'icon'=>'hand-up',
				    'click'=>'
				    function(){
				    	var id = $(this).parents("tr").attr("row-id");
				    	$.post(
						"redirectAjax",
						"cid="+id,
						function(data)
							{
								//alert(data);
								$("#myModal .modal-header").html(data["header"]);
								$("#myModal .modal-body").html(data["body"]);
								$("#myModal .modal-footer").html(data["footer"]);
								$("#myModal").modal("toggle");
							},
						"json"
						)
				    }
				    ',
				),
				'updateAjax' => array(
				    'label'=>'Update',
                    'icon'=>'pencil',
				    'click'=>'
				    function(){
				    	var id = $(this).parents("tr").attr("row-id");
				    	$.post(
						"updateAjax/"+id,
						"cid="+id,
						function(data)
							{
								//alert(data);
								$("#myModal").html(data);
								$("#myModal").modal("toggle");
							}
						)
				    }
				    ',
				)
			),
            'template'=>'{redirects} {updateAjax} {delete}',
		),
	),
)); ?>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php /* $this->renderPartial('_search',array(
	'model'=>$model,
)); */?>
</div><!-- search-form -->

<?php $this->beginWidget('bootstrap.widgets.TbModal', array('id'=>'myModal')); ?>

		<div class="modal-header"></div>
        <div class="modal-body"></div>
        <div class="modal-footer"></div>

<?php $this->endWidget(); ?>

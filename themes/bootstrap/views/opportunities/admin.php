<?php
/* @var $this OpportunitiesController */
/* @var $model Opportunities */

$this->breadcrumbs=array(
	'Opportunities'=>array('index'),
	'Manage Opportunities',
);

$this->menu=array(
	array('label'=>'List Opportunities', 'url'=>array('index')),
	array('label'=>'Create Opportunities', 'url'=>array('create')),
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

<div class="botonera">
<?php
$this->widget('bootstrap.widgets.TbButton', array(
	'type'        => 'info',
	'label'       => 'Create Opportunity',
	'block'       => false,
	'buttonType'  => 'ajaxButton',
	'url'         => 'create',
	'ajaxOptions' => array(
		'type'    => 'POST',
		'success' => 'function(data)
			{
                    // console.log(this.url);
	                //alert("create");
					$("#modalOpportunities").html(data);
					$("#modalOpportunities").modal("toggle");
			}',
		),
	'htmlOptions' => array('id' => 'create'),
	)
);
?>
</div>

<?php $this->widget('bootstrap.widgets.TbGridView', array(
	'id'                       => 'opportunities-grid',
	'dataProvider'             => $model->search(),
	'filter'                   => $model,
	'type'                     => 'striped condensed',
	'rowHtmlOptionsExpression' => 'array("data-row-id" => $data->id)',
	'template'                 => '{items} {pager} {summary}',
	'columns'                  =>array(
		'id',
		array(
			'name'  =>'carrier_isp',
			'value' =>'$data->carriers->isp',
		),
		array (
			'name'              => 'rate',
			'headerHtmlOptions' => array('style'=>'width: 60px'),
		),
		array(
			'name'              => 'model_adv',
			'headerHtmlOptions' => array('style'=>'width: 60px'),
		),
		'product',
		array(
			'name'  =>'advertiser_name',
			'value' =>'$data->ios->advertisers->name',
		),
		// array(
		// 	'name'  =>'account_manager_lastname',
		// 	'value' =>'$data->account_manager_id ? $data->accountManager->lastname . " " . $data->accountManager->name : ""',
		// ),
		// 'comment',
		// array(
		// 	'name'  =>'country_name',
		// 	'value' =>'$data->country_id ? $data->country->name : ""',
		// ),
		// array( 
		// 	'name'  =>'wifi',
		// 	'value' =>'$data->wifi ? "Habilitado" : "Inhabilitado"',
		// ),
		array(
			'name' => 'budget',
			'headerHtmlOptions' => array('style'=>'width: 80px'),
		),
		// 'server_to_server',
		array( 
			'name'  =>'startDate',
			'value' =>'date("d-m-Y", strtotime($data->startDate))',
			'headerHtmlOptions' => array('style'=>'width: 80px'),
		),
		array( 
			'name'  =>'endDate',
			'value' =>'date("d-m-Y", strtotime($data->endDate))',
			'headerHtmlOptions' => array('style'=>'width: 80px'),
		),
		array( 
			'name'  =>'ios_name',
			'value' =>'$data->ios->name',
		),
		array(
			'class'             => 'bootstrap.widgets.TbButtonColumn',
			'headerHtmlOptions' => array('style' => "width: 60px"),
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
								$("#modalOpportunities").html(data);
								$("#modalOpportunities").modal("toggle");
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
								$("#modalOpportunities").html(data);
								$("#modalOpportunities").modal("toggle");
							}
						)
				    }
				    ',
				)
			),
			'template' => '{viewAjax} {updateAjax} {delete}',
		),
	),
)); ?>

<?php $this->beginWidget('bootstrap.widgets.TbModal', array('id'=>'modalOpportunities')); ?>

		<div class="modal-header"></div>
        <div class="modal-body"><h1>Opportunities</h1></div>
        <div class="modal-footer"></div>

<?php $this->endWidget(); ?>
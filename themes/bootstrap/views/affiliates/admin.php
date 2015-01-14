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
		'label'       => 'Create Affiliate',
		'block'       => false,
		'buttonType'  => 'ajaxButton',
		'url'         => 'create',
		'ajaxOptions' => array(
			'type'    => 'POST',
			'beforeSend' => 'function(data)
				{
			    	var dataInicial = "<div class=\"modal-header\"></div><div class=\"modal-body\" style=\"padding:100px 0px;text-align:center;\"><img src=\"'.  Yii::app()->theme->baseUrl .'/img/loading.gif\" width=\"40\" /></div><div class=\"modal-footer\"></div>";
					$("#modalAffiliates").html(dataInicial);
					$("#modalAffiliates").modal("toggle");
				}',
			'success' => 'function(data)
				{
					$("#modalAffiliates").html(data);
				}',
			),
		'htmlOptions' => array('id' => 'create'),
		)
	);
	?>
</div>

<?php $this->widget('bootstrap.widgets.TbGridView', array(
	'id'                       =>'affiliates-grid',
	'dataProvider'             => $model->search(),
	'filter'                   => $model,
	'type'                     => 'striped condensed',
	'rowHtmlOptionsExpression' => 'array("data-row-id" => $data->providers_id)',
	'template'                 => '{items} {pager} {summary}',
	'columns'=>array(
		array(
			'name'              =>'providers_id',
			'headerHtmlOptions' => array('style' => "width: 60px"),
		),
		array(
			'name'              =>'providers_name',
			'value'             =>'$data->providers->name',
			'headerHtmlOptions' => array('style' => "width: 80px"),
		),
		array(
			'name'              =>'commercial_name',
			'value'             =>'$data->providers->commercial_name',
			'headerHtmlOptions' => array('style' => "width: 200px"),
		),
		// 'status',
		array(
			'name'              =>'country_name',
			'value'             => '$data->providers->country ? $data->providers->country->name : ""',			
			'headerHtmlOptions' => array('style' => "width: 80px"),
		),
		// 'address',
		// 'state',
		// 'zip_code',
		// 'phone',
		// 'email',
		array(
			'header'            =>'Contact Com',
			'value'             => '$data->providers->contact_com',
			'headerHtmlOptions' => array('style' => "width: 80px"),
		),
		array(
			'header'            =>'Contact Adm',
			'value'             => '$data->providers->contact_adm',
			'headerHtmlOptions' => array('style' => "width: 80px"),
		),
		// 'currency',
		// 'ret',
		// 'tax_id',
		//'net_payment',
		array(
			'header'            =>'Entity',
			'value'             => '$data->providers->entity',
			'headerHtmlOptions' => array('style' => "width: 30px"),
		),
		array(
			'class'             => 'bootstrap.widgets.TbButtonColumn',
			'headerHtmlOptions' => array('style' => "width: 120px"),
			'buttons'           => array(
				'viewAjax' => array(
					'label' =>'Detail',
					'icon'  =>'eye-open',
					'click' =>'
				    function(){
				    	var id = $(this).parents("tr").attr("data-row-id");

				    	var dataInicial = "<div class=\"modal-header\"></div><div class=\"modal-body\" style=\"padding:100px 0px;text-align:center;\"><img src=\"'.  Yii::app()->theme->baseUrl .'/img/loading.gif\" width=\"40\" /></div><div class=\"modal-footer\"></div>";
						$("#modalAffiliates").html(dataInicial);
						$("#modalAffiliates").modal("toggle");

				    	$.post(
						"view/"+id,
						"",
						function(data)
							{
								//alert(data);
								$("#modalAffiliates").html(data);
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
						$("#modalAffiliates").html(dataInicial);
						$("#modalAffiliates").modal("toggle");

				    	// use jquery post method to get updateAjax view in a modal window
				    	$.post(
						"update/"+id,
						"",
						function(data)
							{
								//alert(data);
								$("#modalAffiliates").html(data);
							}
						)
						return false;
				    }
				    ',
				),
				'exportPdf' => array(
					'label'   => 'Export PDF',
					'icon'    => 'download',
					'url'     => 'Yii::app()->getBaseUrl(true) . "/providers/exportPdf/" . $data->providers_id',
					'options' => array('target' => '_blank'),
				),
			),
			'template' => '{viewAjax} {updateAjax} {exportPdf}',
		),
	),
)); ?>

<?php $this->beginWidget('bootstrap.widgets.TbModal', array('id'=>'modalAffiliates')); ?>

		<div class="modal-header"></div>
        <div class="modal-body"></div>
        <div class="modal-footer"></div>

<?php $this->endWidget(); ?>
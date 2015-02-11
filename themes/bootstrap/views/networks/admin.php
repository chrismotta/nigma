<?php
/* @var $this NetworksController */
/* @var $model Networks */

$this->breadcrumbs=array(
	'Networks',
	'Manage',
);
?>

<div class="botonera">
	<?php
	$this->widget('bootstrap.widgets.TbButton', array(
		'type'        => 'info',
		'label'       => 'Create Network',
		'block'       => false,
		'buttonType'  => 'ajaxButton',
		'url'         => 'create',
		'ajaxOptions' => array(
			'type'    => 'POST',
			'beforeSend' => 'function(data)
				{
			    	var dataInicial = "<div class=\"modal-header\"></div><div class=\"modal-body\" style=\"padding:100px 0px;text-align:center;\"><img src=\"'.  Yii::app()->theme->baseUrl .'/img/loading.gif\" width=\"40\" /></div><div class=\"modal-footer\"></div>";
					$("#modalNetworks").html(dataInicial);
					$("#modalNetworks").modal("toggle");
				}',
			'success' => 'function(data)
				{
					$("#modalNetworks").html(data);
				}',
			),
		'htmlOptions' => array('id' => 'create'),
		)
	);
	?>
</div>

<?php $this->widget('bootstrap.widgets.TbGridView', array(
	'id'                       =>'networks-grid',
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
			'headerHtmlOptions' => array('style' => "width: 300px"),
		),
		array(
			'name'              =>'has_api',
			'value'             =>'$data->has_api ? "Yes" : "No"',
			'headerHtmlOptions' => array('style' => "width: 60px"),
		),
		array(
			'name'              =>'providers_has_s2s',
			'value'             =>'$data->providers->has_s2s ? "Yes" : "No"',
			'headerHtmlOptions' => array('style' => "width: 60px"),
		),
		array(
			'name'              =>'use_alternative_convention_name',
			'value'             =>'$data->use_alternative_convention_name ? "Yes" : "No"',
			'headerHtmlOptions' => array('style' => "width: 60px"),
		),
		array(
			'name'              =>'use_vectors',
			'value'             =>'$data->use_vectors ? "Yes" : "No"',
			'headerHtmlOptions' => array('style' => "width: 60px"),
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
						$("#modalNetworks").html(dataInicial);
						$("#modalNetworks").modal("toggle");

				    	$.post(
						"view/"+id,
						"",
						function(data)
							{
								//alert(data);
								$("#modalNetworks").html(data);
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
						$("#modalNetworks").html(dataInicial);
						$("#modalNetworks").modal("toggle");

				    	// use jquery post method to get updateAjax view in a modal window
				    	$.post(
						"update/"+id,
						"",
						function(data)
							{
								//alert(data);
								$("#modalNetworks").html(data);
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
				'uploadPdf' => array(
					'label' => 'Upload Signed IO',
					'icon'  => 'upload',
					'click' => '
				    function(){
				    	// get row id from data-row-id attribute
				    	var id = $(this).parents("tr").attr("data-row-id");

				    	var dataInicial = "<div class=\"modal-header\"></div><div class=\"modal-body\" style=\"padding:100px 0px;text-align:center;\"><img src=\"'.  Yii::app()->theme->baseUrl .'/img/loading.gif\" width=\"40\" /></div><div class=\"modal-footer\"></div>";
						$("#modalNetworks").html(dataInicial);
						$("#modalNetworks").modal("toggle");

				    	// use jquery post method to get updateAjax view in a modal window
				    	$.post(
						"'. Yii::app()->getBaseUrl(true) . '/providers/uploadPdf/"+id,
						{"type":"io"},
						function(data)
							{
								//alert(data);
								$("#modalNetworks").html(data);
							}
						)
						return false;
				    }
				    ',
				),
				'uploadAgreement' => array(
					'label' => 'Upload Signed Agreement',
					'icon'  => 'upload',
					'click' => '
				    function(){
				    	// get row id from data-row-id attribute
				    	var id = $(this).parents("tr").attr("data-row-id");

				    	var dataInicial = "<div class=\"modal-header\"></div><div class=\"modal-body\" style=\"padding:100px 0px;text-align:center;\"><img src=\"'.  Yii::app()->theme->baseUrl .'/img/loading.gif\" width=\"40\" /></div><div class=\"modal-footer\"></div>";
						$("#modalNetworks").html(dataInicial);
						$("#modalNetworks").modal("toggle");

				    	// use jquery post method to get updateAjax view in a modal window
				    	$.post(
						"'. Yii::app()->getBaseUrl(true) . '/providers/uploadPdf/"+id,
						{"type":"agreement"},
						function(data)
							{
								//alert(data);
								$("#modalNetworks").html(data);
							}
						)
						return false;
				    }
				    ',
				),
				'viewPdf' => array(
					'label'   => 'View Signed IO',
					'icon'    => 'file',
					'url'     => 'Yii::app()->getBaseUrl(true) . "/providers/viewPdf/" . $data->providers_id',
					'options' => array('target' => '_blank'),
					'visible' => '$data->providers->prospect == 10 ? true : false',
				),
				'viewAgreement' => array(
					'label'   => 'View Signed Agreement',
					'icon'    => 'file',
					'url'     => 'Yii::app()->getBaseUrl(true) . "/providers/viewAgreement/" . $data->providers_id',
					'options' => array('target' => '_blank'),
					'visible' => '$data->providers->prospect == 10 ? true : false',
				),
				'agreementPdf' => array(
					'label'   => 'Export Agreement',
					'icon'    => 'download',
					'url'     => 'Yii::app()->getBaseUrl(true) . "/providers/agreementPdf/" . $data->providers_id',
					'options' => array('target' => '_blank'),
				),
			),
			'template' => '{viewAjax} {updateAjax} {exportPdf} {uploadPdf} {viewPdf} {agreementPdf} {uploadAgreement} {viewAgreement}',
		),
	),
)); ?>

<?php $this->beginWidget('bootstrap.widgets.TbModal', array('id'=>'modalNetworks')); ?>

		<div class="modal-header"></div>
        <div class="modal-body"></div>
        <div class="modal-footer"></div>

<?php $this->endWidget(); ?>
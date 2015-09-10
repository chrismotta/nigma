<?php
/* @var $this IosController */
/* @var $model Ios */

$this->breadcrumbs=array(
	'Ioses'=>array('index'),
	'Manage',
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
			'beforeSend' => 'function(data)
				{
			    	var dataInicial = "<div class=\"modal-header\"></div><div class=\"modal-body\" style=\"padding:100px 0px;text-align:center;\"><img src=\"'.  Yii::app()->theme->baseUrl .'/img/loading.gif\" width=\"40\" /></div><div class=\"modal-footer\"></div>";
					$("#modalIos").html(dataInicial);
					$("#modalIos").modal("toggle");
				}',
			'success' => 'function(data)
				{
					$("#modalIos").html(data);
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
		array(
			'name'=>'id',
			'headerHtmlOptions' => array('style' => "width: 80px"),
		),
		array(
			'name'=>'date',
			'headerHtmlOptions' => array('style' => "width: 100px"),
		),
		array(
			'name'=>'financeEntitiesName',
			'value'=>'$data->financeEntities->name',
			// 'headerHtmlOptions' => array('style' => "width: 200px"),
		),
		array(
			'name'=>'budget',
			'value'=>'$data->budget ? $data->budget : "Open"',
			'headerHtmlOptions' => array('style' => "width: 80px"),
		),
		array(
			'name'=>'status',
			'headerHtmlOptions' => array('style' => "width: 80px"),
		),
		array(
			'class'             => 'bootstrap.widgets.TbButtonColumn',
			'headerHtmlOptions' => array('style' => "width: 120px"),
			'afterDelete'       => 'function(link, success, data) { if(data) alert(data); }',
			'buttons'           => array(
				'browsePdf' => array(
					'label' =>'View PDF',
					'icon'  =>'eye-open',
					'url'   => 'Yii::app()->getBaseUrl(true) . "/ios/browsePdf/" . $data->id',
					'options' => array('target' => '_blank'),
					/*'click' =>'
				    function(){
				    	var id = $(this).parents("tr").attr("data-row-id");

				    	var dataInicial = "<div class=\"modal-header\"></div><div class=\"modal-body\" style=\"padding:100px 0px;text-align:center;\"><img src=\"'.  Yii::app()->theme->baseUrl .'/img/loading.gif\" width=\"40\" /></div><div class=\"modal-footer\"></div>";
						$("#modalIos").html(dataInicial);
						$("#modalIos").modal("toggle");

				    	$.post(
						"view/"+id,
						"",
						function(data)
							{
								//alert(data);
								$("#modalIos").html(data);
							}
						)
						return false;
				    }
				    ',*/
				),
				'downloadPdf' => array(
					'label'   => 'Download PDF',
					'icon'    => 'download',
					'url'     => 'Yii::app()->getBaseUrl(true) . "/ios/downloadPdf/" . $data->id',
					// 'options' => array('target' => '_blank'),
					//'visible' => '$data->status == 10 ? false : true',
				),
				'uploadPdf' => array(
					'label' => 'Upload Signed IO',
					'icon'  => 'upload',
					'click' => '
				    function(){
				    	// get row id from data-row-id attribute
				    	var id = $(this).parents("tr").attr("data-row-id");

				    	var dataInicial = "<div class=\"modal-header\"></div><div class=\"modal-body\" style=\"padding:100px 0px;text-align:center;\"><img src=\"'.  Yii::app()->theme->baseUrl .'/img/loading.gif\" width=\"40\" /></div><div class=\"modal-footer\"></div>";
						$("#modalIos").html(dataInicial);
						$("#modalIos").modal("toggle");

				    	// use jquery post method to get updateAjax view in a modal window
				    	$.post(
						"uploadPdf/"+id,
						"",
						function(data)
							{
								//alert(data);
								$("#modalIos").html(data);
							}
						)
						return false;
				    }
				    ',
				),
				'viewPdf' => array(
					'label'   => 'View Signed IO',
					'icon'    => 'file',
					'url'     => 'Yii::app()->getBaseUrl(true) . "/ios/viewPdf/" . $data->id',
					'options' => array('target' => '_blank'),
					//'visible' => '$data->prospect == 10 ? true : false',
				)
			),
			'template' => '{browsePdf} {downloadPdf} {uploadPdf} {viewPdf} {delete}',
		),
	),
)); ?>

<?php BuildGridView::printModal($this, 'modalIos', 'Insertion Order'); ?>

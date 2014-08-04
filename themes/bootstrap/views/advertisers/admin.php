<?php
/* @var $this AdvertisersController */
/* @var $model Advertisers */

$this->breadcrumbs=array(
	'Advertisers'=>array('index'),
	'Manage Advertisers',
);

$this->menu=array(
	array('label'=>'List Advertisers', 'url'=>array('index')),
	array('label'=>'Create Advertisers', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#advertisers-grid').yiiGridView('update', {
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
	'label'       => 'Create Advertiser',
	'block'       => false,
	'buttonType'  => 'ajaxButton',
	'url'         => 'create',
	'ajaxOptions' => array(
		'type'    => 'POST',
		'success' => 'function(data)
			{
                    // console.log(this.url);
	                //alert("create");
					$("#modalAdvertiser").html(data);
					$("#modalAdvertiser").modal("toggle");
			}',
		),
	'htmlOptions' => array('id' => 'create'),
	)
);
?>
</div>

<?php $this->widget('bootstrap.widgets.TbGridView', array(
	'id'                       => 'advertisers-grid',
	'dataProvider'             => $model->search(),
	'filter'                   => $model,
	'type'                     => 'striped condensed',
	'rowHtmlOptionsExpression' => 'array("data-row-id" => $data->id)',
	'template'                 => '{items} {pager} {summary}',
	'columns'=>array(
		array(
			'name'=>'id',
			'htmlOptions'=>array('style' => 'width: 100px'),
		),
		'name',
		'cat',
		array(
			'name'=>'commercial_lastname',
			'value'=>'$data->commercial->lastname . " " .$data->commercial->name',
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
								$("#modalAdvertiser").html(data);
								$("#modalAdvertiser").modal("toggle");
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
								$("#modalAdvertiser").html(data);
								$("#modalAdvertiser").modal("toggle");
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

<?php $this->beginWidget('bootstrap.widgets.TbModal', array('id'=>'modalAdvertiser')); ?>

		<div class="modal-header"></div>
        <div class="modal-body"><h1>Advertiser</h1></div>
        <div class="modal-footer"></div>

<?php $this->endWidget(); ?>

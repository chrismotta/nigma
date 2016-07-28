<?php
$this->breadcrumbs=array(
	'Landings'=>array('index'),
	'Manage',
);

$this->menu=array(
array('label'=>'List Landings','url'=>array('index')),
array('label'=>'Create Landings','url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
$('.search-form').toggle();
return false;
});
$('.search-form form').submit(function(){
$.fn.yiiGridView.update('landings-grid', {
data: $(this).serialize()
});
return false;
});
");
?>

<?php

echo '<div class="botonera">';
$this->widget('bootstrap.widgets.TbButton', array(
	'type'        => 'info',
	'label'       => 'Create Landing',
	'block'       => false,
	'buttonType'  => 'linkButton',
	'url'         => array("create"),
	'htmlOptions' => array(
		// 	"data-grid-id"      => "campaigns-grid", 
		// 	"data-modal-id"     => "modalCampaigns", 
		// 	"data-modal-title"  => "Create Campaign", 
		// 	'onclick'           => 'event.preventDefault(); openModal(this)',
		'style' => 'float:right',
		),
	)
);
echo '</div>';

?>

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'landings-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'template' =>'{items} {pager} {summary}',
	'columns'=>array(
		'id',
		'name',
		'country_id',
		'default_color',
		'highlight_color',
		'byline_images_id',
		/*
		'background_color',
		'background_images_id',
		'headline',
		'byline',
		'input_legend',
		'input_label',
		'input_eg',
		'select_label',
		'select_options',
		'tyc_headline',
		'tyc_body',
		'checkbox_label',
		'button_label',
		'thankyou_msg',
		'validate_msg',
		*/
		array(
			'class'             => 'bootstrap.widgets.TbButtonColumn',
			'headerHtmlOptions' => array('style' => "width: 100px"),
			'buttons'           => array(
				'duplicate' => array(
					'label' => 'Duplicate',
					'icon'  => 'plus-sign',
					'url'     => 'array("duplicate", "id" => $data->id)',
					),
				),
			'template' => '{view} {update} {duplicate} {delete}',
			),
		),
	)); ?>

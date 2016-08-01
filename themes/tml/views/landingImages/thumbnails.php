<?php
$this->breadcrumbs=array(
	'Landing Images'=>array('index'),
	'Manage',
);

$form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'id'=>'landing-images-form',
    'type'=>'inline',
    'htmlOptions'=>array('class'=>'well'),
    // to enable ajax validation
    // 'enableAjaxValidation'=>true,
    // 'action' => Yii::app()->getBaseUrl() . '/' . Yii::app()->controller->getId().'/'.Yii::app()->controller->getAction()->getId(),
    // 'method' => 'GET',
    'clientOptions'=>array('validateOnSubmit'=>true, 'validateOnChange'=>true),
	));

echo '<fieldset>';

echo $form->dropDownListRow($model,'type',array(
	"null"=>"All",
	"Background"=>"Background",
	"HeadLine"=>"HeadLine",
	"ByLine"=>"ByLine",
	"Gallery"=>"Gallery",
	),
	array('class'=>'input-large')
	);
	  
$this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'label'=>'Filter', 'htmlOptions' => array('class' => 'showLoading')));

// echo '<div class="botonera">';
$this->widget('bootstrap.widgets.TbButton', array(
	'type'        => 'info',
	'label'       => 'Upload Image',
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
// echo '</div>';

echo '</fieldset>';

$this->endWidget();

?>





<?php

echo CHtml::openTag('div', array('class' => 'row-fluid'));
$this->widget(
    'bootstrap.widgets.TbThumbnails',
    array(
        'dataProvider' => $model->search(),
        'template' => "{items}\n{pager}",
        'itemView' => '_thumb',
    )
);
echo CHtml::closeTag('div');

?>

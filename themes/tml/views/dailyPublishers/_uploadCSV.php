<?php
$form = $this->beginWidget(
    'bootstrap.widgets.TbActiveForm',
    array(
		'id'                   => 'upload-form',
		'type'                 => 'horizontal',
		'htmlOptions'          => array('enctype' => 'multipart/form-data', 'class'=>'well'),
		// to enable ajax validation
		'enableAjaxValidation' => false,
		'clientOptions'        => array('validateOnSubmit'=>true, 'validateOnChange'=>true),
    )
);

echo $form->dropDownListRow($model, 'exchanges_id', $exchanges, 
            array(
              'prompt'   => 'Select Exchange', 
              'encode'   => false,
              'class'    => 'span2', 
            ));
echo $form->datepickerRow($model, 'date', array(
            'options' => array(
                'autoclose'      => true,
                'todayHighlight' => true,
                'clearBtn'       => true,
                'format'         => 'yyyy-mm-dd',
                'viewformat'     => 'dd-mm-yyyy',
                'placement'      => 'right',
            ),
            'htmlOptions' => array(
                'class' => 'span2',
            )),
            array(
                'append' => '<label for="DailyPublishers_date"><i class="icon-calendar"></i></label>',
            ));
echo $form->fileFieldRow($model, 'csvFile');
// ...
// echo CHtml::submitButton('Submit');<div class="form-actions">

echo '<div class="form-actions">';
$this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'success', 'label'=>'Submit'));
$this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'reset', 'type'=>'reset', 'label'=>'Reset'));
echo '</div>';

$this->endWidget();
?>
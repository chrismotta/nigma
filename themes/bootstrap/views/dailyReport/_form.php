<?php
/* @var $this DailyReportController */
/* @var $model DailyReport */
/* @var $form CActiveForm */
/* @var $networks */
/* @var $campaigns */
?>

<div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h4>Daily Report <?php echo $model->isNewRecord ? "" : "#". $model->id; ?></h4>
</div>


<div class="modal-body">

    <?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id'=>'daily-report-form',
        'type'=>'horizontal',
        'htmlOptions'=>array('class'=>'well'),
        // to enable ajax validation
        'enableAjaxValidation'=>true,
        'clientOptions'=>array('validateOnSubmit'=>true, 'validateOnChange'=>true),
    )); ?>
    <fieldset>
        <?php 

        echo $form->dropDownListRow($model, 'campaigns_id', $campaigns, array('prompt' => 'Select campaign'));
        echo $form->dropDownListRow($model, 'networks_id', $networks, array('prompt' => 'Select a network'));
        echo '<hr>';
        echo $form->textFieldRow($model, 'imp', array('class'=>'span3'));
        echo $form->textFieldRow($model, 'clics', array('class'=>'span3'));
        echo $form->textFieldRow($model, 'conv_api', array('class'=>'span3'));
        echo $form->textFieldRow($model, 'conv_adv', array('class'=>'span3'));
        echo $form->textFieldRow($model, 'spend', array('class'=>'span3', 'prepend'=>'$'));
        echo $form->textFieldRow($model, 'model', array('class'=>'span3'));
        echo $form->textFieldRow($model, 'value', array('class'=>'span3'));
        echo $form->textFieldRow($model, 'date', array('class'=>'span3'));
        echo $form->checkboxRow($model, 'is_from_api', array('disabled'=>true));

        ?>

    <?php //echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
    <div class="form-actions">
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'success', 'label'=>'Submit')); ?>
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'reset', 'type'=>'reset', 'label'=>'Reset')); ?>
    </div>
    </fieldset>

    <?php $this->endWidget(); ?>


</div>

<div class="modal-footer">
    Create new Daily Report. Fields with <span class="required">*</span> are required.
</div>
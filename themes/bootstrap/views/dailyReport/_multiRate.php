<?php
/* @var $this DailyReportController */
/* @var $model DailyReport */
/* @var $form CActiveForm */
/* @var $networks */
/* @var $campaigns */
?>

<div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h4>Multi Rate</h4>
</div>


<div class="modal-body">

    <?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id'=>'daily-report-form',
        'type'=>'inline',
        'htmlOptions'=>array('class'=>'well'),
        // to enable ajax validation
        'enableAjaxValidation'=>true,
        'clientOptions'=>array('validateOnSubmit'=>true, 'validateOnChange'=>true),
    )); ?>
    <fieldset>
        <?php 
            foreach ($daily_carriers as $carriers) {
                echo $form->textFieldRow($carriers, 'rate', array('class'=>'input-small'));
                echo $form->textFieldRow($carriers, 'conv', array('class'=>'input-small'));
            }
        ?>

    <?php //echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
    <div class="form-actions">
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'success', 'label'=>'Submit', 'htmlOptions' => array('name' => 'multiRate-submit'))); ?>
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'reset', 'type'=>'reset', 'label'=>'Reset')); ?>
    </div>
    </fieldset>

    <?php $this->endWidget(); ?>


</div>

<div class="modal-footer">
    Add multi rate info. Fields with <span class="required">*</span> are required.
</div>
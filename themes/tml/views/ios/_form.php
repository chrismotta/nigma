<?php
/* @var $this IosController */
/* @var $model Ios */
/* @var $form CActiveForm 
 *
 * @var currency
 * @var entity
 * @var commercial
 * @var advertiser
 * @var country
 */
?>

<div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h4>Ios <?php echo $model->isNewRecord ? "" : "#". $model->id; ?></h4>
</div>


<div class="modal-body">

    <?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id'                   =>'ios-form',
        'type'                 =>'horizontal',
        'htmlOptions'          =>array('class'=>'well'),
        // to enable ajax validation
        'enableAjaxValidation' =>true,
        'clientOptions'        =>array('validateOnSubmit'=>true, 'validateOnChange'=>true),
    )); ?>
    <fieldset>
        <?php 

        if ( ! $model->isNewRecord ) {
            echo $form->textFieldRow($model, 'id', array('type'=>'hidden', 'class'=>'span3', 'readonly'=>true));
            echo $form->textFieldRow($model, 'date', array('class'=>'span3'));
        }
        else
            echo $form->textFieldRow($model, 'date', array('class'=>'span3', 'value'=>date('Y-m-d',strtotime('NOW'))));
            echo $form->textFieldRow($model, 'finance_entities_id', array('class'=>'span3'));
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
    Edit Insertion Order attributes. Fields with <span class="required">*</span> are required.
</div>
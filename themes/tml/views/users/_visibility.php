<?php
/* @var $this UsersController */
/* @var $model Users */
/* @var $form CActiveForm */
?>

    <?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id'                   =>'visibility-form',
        'type'                 =>'horizontal',
        'htmlOptions'          => array('class'=>'well'),
        // to enable ajax validation
        'enableAjaxValidation' =>false,
        // 'clientOptions'        =>array('validateOnSubmit'=>true, 'validateOnChange'=>true),
    )); ?>
    <fieldset>
        
        <h5>User Data</h5>
        <?php $this->widget('bootstrap.widgets.TbDetailView', array(
            'type'       =>'condensed',
            'data'       =>$user,
            'attributes' =>array(
                'name',
                'lastname',
                array(
                    'name'  => 'advertiser',
                    'value' => isset($advertiser->name) ? $advertiser->name : null,
                    ),
            ),
        )); ?>
        
        <h5>Select visibility</h5>
        <?php 
        echo $form->hiddenField($model, 'users_id');
        echo $form->checkboxRow($model, 'country');
        echo $form->checkboxRow($model, 'carrier');
        echo $form->checkboxRow($model, 'rate');
        echo $form->checkboxRow($model, 'imp');
        echo $form->checkboxRow($model, 'clicks');
        echo $form->checkboxRow($model, 'conv');
        echo $form->checkboxRow($model, 'spend');
        ?> 
        

    <?php //echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
    <div class="form-actions">
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'success', 'label'=>'Submit')); ?>
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'reset', 'type'=>'reset', 'label'=>'Reset')); ?>
    </div>
    </fieldset>

    <?php $this->endWidget(); ?>
    
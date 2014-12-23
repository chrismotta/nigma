<?php
/* @var $this AffiliatesController */
/* @var $model Affiliates */
/* @var $form CActiveForm */
?>

<div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h4>Affiliates <?php echo $modelAffi->isNewRecord ? "" : "#". $modelAffi->providers_id; ?></h4>
</div>


<div class="modal-body">

    <?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
		'id'                   =>'affiliates-form',
		'type'                 =>'horizontal',
		'htmlOptions'          =>array('class'=>'well'),
		// to enable ajax validation
		'enableAjaxValidation' =>true,
		'clientOptions'        =>array('validateOnSubmit'=>true, 'validateOnChange'=>true),
    )); ?>
    <fieldset>
        <?php 

        if ( ! $modelAffi->isNewRecord ) {
    		echo $form->textFieldRow($modelAffi, 'providers_id', array('type'=>'hidden', 'class'=>'span3', 'readonly'=>true));
    	}

        echo $form->textFieldRow($modelProv, 'prefix', array('class'=>'span3'));
        echo $form->textFieldRow($modelProv, 'name', array('class'=>'span3'));
        echo $form->dropDownListRow($modelProv, 'currency', $currency, array('prompt' => 'Select a currency'));
        
        
        echo $form->dropDownListRow($modelAffi, 'entity', $entity, array('prompt' => 'Select an entity'));
        echo $form->textFieldRow($modelAffi, 'commercial_name', array('class'=>'span3'));
        echo $form->textFieldRow($modelAffi, 'tax_id', array('class'=>'span3'));
        echo $form->dropDownListRow($modelAffi, 'country_id', $country, array('prompt' => 'Select a country'));
        echo $form->textFieldRow($modelAffi, 'address', array('class'=>'span3'));
        echo $form->textFieldRow($modelAffi, 'state', array('class'=>'span3'));
        echo $form->textFieldRow($modelAffi, 'zip_code', array('class'=>'span3'));
        echo $form->textFieldRow($modelAffi, 'phone', array('class'=>'span3'));
        echo $form->textFieldRow($modelAffi, 'contact_com', array('class'=>'span3'));
        echo $form->textFieldRow($modelAffi, 'email_com', array('class'=>'span3'));
        echo $form->textFieldRow($modelAffi, 'contact_adm', array('class'=>'span3'));
        echo $form->textFieldRow($modelAffi, 'email_adm', array('class'=>'span3'));
        
        echo $form->dropDownListRow($modelAffi, 'users_id', $users, array('prompt' => 'Select associate username'));
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
    Edit Affiliates attributes. Fields with <span class="required">*</span> are required.
</div>
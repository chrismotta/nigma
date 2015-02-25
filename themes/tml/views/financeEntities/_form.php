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
    <h4>Finance Entitie <?php echo $model->isNewRecord ? "" : "#". $model->id; ?></h4>
</div>


<div class="modal-body">

    <?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
		'id'                   =>'financeEntities-form',
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
        }

        echo $form->hiddenField($model, 'status', array('type'=>"hidden",'value'=>'Active') );
        echo $form->dropDownListRow($model, 'advertisers_id', $advertiser, array('prompt' => 'Select an advertiser'));
        //echo $form->textFieldRow($model, 'status', array('class'=>'span3'));
        echo $form->textFieldRow($model, 'name', array('class'=>'span3'));
        echo $form->textFieldRow($model, 'commercial_name', array('class'=>'span3'));
        echo $form->textFieldRow($model, 'tax_id', array('class'=>'span3'));
        echo $form->dropDownListRow($model, 'country_id', $country, array('prompt' => 'Select a country'));
        echo $form->textFieldRow($model, 'address', array('class'=>'span3'));
        echo $form->textFieldRow($model, 'state', array('class'=>'span3'));
        echo $form->textFieldRow($model, 'zip_code', array('class'=>'span3'));
        echo $form->textFieldRow($model, 'phone', array('class'=>'span3'));
        echo $form->textFieldRow($model, 'contact_com', array('class'=>'span3'));
        echo $form->textFieldRow($model, 'email_com', array('class'=>'span3'));
        echo $form->textFieldRow($model, 'contact_adm', array('class'=>'span3'));
        echo $form->textFieldRow($model, 'email_adm', array('class'=>'span3'));
        echo $form->dropDownListRow($model, 'currency', $currency, array('prompt' => 'Select a currency'));
        echo $form->textFieldRow($model, 'ret', array('class'=>'span3'));
        echo $form->hiddenField($model, 'commercial_id', array('type'=>"hidden") );
        //echo $form->textFieldRow($commercial, 'username', array('class'=>'span3', 'readonly'=>true, 'labelOptions'=>array('label'=>$model->getAttributeLabel('commercial_id'))) );
        echo $form->dropDownListRow($model, 'entity', $entity, array('prompt' => 'Select an entity'));
        echo $form->textFieldRow($model, 'net_payment', array('class'=>'span3'));
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
    Edit Finance Entities attributes. Fields with <span class="required">*</span> are required.
</div>
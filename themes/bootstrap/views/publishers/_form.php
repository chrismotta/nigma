<?php
/* @var $this PublishersController */
/* @var $model Publishers */
/* @var $form CActiveForm */
/* @var $currency currency */
/* @var $model_publ model_publ */
?>

<div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h4>Publisher <?php echo $model->isNewRecord ? "" : "#". $model->id; ?></h4>
</div>


<div class="modal-body">

    <?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
		'id'                   =>'publishers-form',
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
        echo $form->hiddenField($model, 'account_manager_id', array('type'=>"hidden") );
        echo $form->dropDownListRow($model, 'entity', $entity, array('prompt' => 'Select an entity'));
        echo $form->textFieldRow($model, 'net_payment', array('class'=>'span3'));
        echo $form->radioButtonListRow($model, 'model', $model_publ);
        echo $form->textFieldRow($model, 'RS_perc', array('class'=>'span3'));
        echo $form->textFieldRow($model, 'rate', array('class'=>'span3'));
    ?>

    <div class="form-actions">
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'success', 'label'=>'Submit')); ?>
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'reset', 'type'=>'reset', 'label'=>'Reset')); ?>
    </div>
    </fieldset>

    <?php $this->endWidget(); ?>
</div>

<div class="modal-footer">
    Edit Publisher attributes. Fields with <span class="required">*</span> are required.
</div>
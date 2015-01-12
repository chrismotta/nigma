<?php
/* @var $this NetworksController */
/* @var $model Networks */
/* @var $form CActiveForm */
?>

<div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h4>Networks <?php echo $modelNetw->isNewRecord ? "" : "#". $modelNetw->providers_id; ?></h4>
</div>


<div class="modal-body">

    <?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
		'id'                   =>'networks-form',
		'type'                 =>'horizontal',
		'htmlOptions'          =>array('class'=>'well'),
		// to enable ajax validation
		'enableAjaxValidation' =>true,
		'clientOptions'        =>array('validateOnSubmit'=>true, 'validateOnChange'=>true),
    )); ?>
    <fieldset>
        <?php 

        if ( ! $modelNetw->isNewRecord ) {
    		echo $form->textFieldRow($modelNetw, 'providers_id', array('type'=>'hidden', 'class'=>'span3', 'readonly'=>true));
    	}

        echo $form->textFieldRow($modelProv, 'prefix', array('class'=>'span3'));
        echo $form->textFieldRow($modelProv, 'name', array('class'=>'span3'));
        echo $form->dropDownListRow($modelProv, 'currency', $currency, array('prompt' => 'Select a currency'));
        echo $form->checkboxRow($modelProv, 'has_s2s', array(
                'onChange' => '
                  if (this.checked == "1")
                    $(".has_s2s").show();
                  else
                    $(".has_s2s").hide();

                  return;
                  '
            ));
        echo '<div style="display: ' . ($modelProv->has_s2s ? 'block' : 'none') . ';" class="has_s2s">';
        echo $form->textFieldRow($modelProv, 'callback', array('class'=>'span3'));
        echo $form->checkboxRow($modelProv, 'has_token', array(
                'onChange' => '
                  if (this.checked == "1")
                    $(".has_token").show();
                  else
                    $(".has_token").hide();

                  return;
                  '
            ));
        echo '<div style="display: ' . ($modelProv->has_token ? 'block' : 'none') . ';" class="has_token">';
        echo $form->textFieldRow($modelProv, 'placeholder', array('class'=>'span3'));
        echo '</div>';
        echo '</div>';
        echo '<hr/>';

        echo $form->textFieldRow($modelNetw, 'percent_off', array('class'=>'span3'));
        echo $form->textFieldRow($modelNetw, 'url', array('class'=>'span3'));
        echo $form->checkboxRow($modelNetw, 'has_api');
        echo $form->checkboxRow($modelNetw, 'use_vectors');
        echo $form->textFieldRow($modelNetw, 'query_string', array('class'=>'span3'));
        echo $form->textFieldRow($modelNetw, 'token1', array('class'=>'span3'));
        echo $form->textFieldRow($modelNetw, 'token2', array('class'=>'span3'));
        echo $form->textFieldRow($modelNetw, 'token3', array('class'=>'span3'));

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
    Edit Networks attributes. Fields with <span class="required">*</span> are required.
</div>
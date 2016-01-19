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


    <?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
		'id'                   =>'regions-form',
		'type'                 =>'horizontal',
		'htmlOptions'          =>array('class'=>'well'),
		// to enable ajax validation
		'enableAjaxValidation' =>true,
		'clientOptions'        =>array('validateOnSubmit'=>true, 'validateOnChange'=>true),
    )); ?>
    <fieldset>
        <?php 

        // if ( ! $model->isNewRecord ) {
    	// 	echo $form->textFieldRow($model, 'id', array('type'=>'hidden', 'class'=>'span3', 'readonly'=>true));
    	// }
        echo $form->dropDownListRow($model, 'finance_entities_id', $financeEntities, array('prompt' => 'Select a finance entities'));
        echo $form->dropDownListRow($model, 'country_id', $country, array('prompt' => 'Select a country'));
        echo $form->textFieldRow($model, 'region', array('class'=>'span3'));
        ?>
        
    <?php //echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
    <div class="form-actions">
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'success', 'label'=>'Submit')); ?>
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'reset', 'type'=>'reset', 'label'=>'Reset')); ?>
    </div>
    </fieldset>

    <?php $this->endWidget(); ?>

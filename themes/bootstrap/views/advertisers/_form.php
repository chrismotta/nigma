<?php
/* @var $this AdvertisersController
 * @var $model Advertiser 
 * @var $form CActiveForm 
 * @var $categories cat list values
 * @var $userInfo User info
 * @var $commercial User
 */
?>

<div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h4>Advertiser <?php echo $model->isNewRecord ? "" : "#". $model->id; ?></h4>
</div>


<div class="modal-body">

    <?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
		'id'                   =>'advertisers-form',
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
    	// echo $form->textFieldRow($model, 'id', array('class'=>'span3', 'readonly'=>true));
        echo $form->textFieldRow($model, 'name', array('class'=>'span3'));
        echo $form->textFieldRow($model, 'prefix', array('class'=>'span3'));
        echo $form->dropDownListRow($model, 'cat', $categories, array('prompt' => 'Select a category'));

        echo $form->hiddenField($model, 'commercial_id', array('type'=>"hidden") );
        //echo $form->textFieldRow($commercial, 'username', array('class'=>'span3', 'readonly'=>true, 'labelOptions'=>array('label'=>$model->getAttributeLabel('commercial_id'))) );
        echo $form->dropDownListRow($model, 'users_id', $users, array('prompt' => 'Not assigned'));
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
    Edit advertisers attributes. Fields with <span class="required">*</span> are required.
</div>

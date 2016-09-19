<?php
/* @var $this VectorsController */
/* @var $model Vector */
?>

<div class="modal-header">
	<a class="close" data-dismiss="modal">&times;</a>
    <h4>Vector <?php echo $model->isNewRecord ? "" : "#". $model->id; ?></h4>
</div>


<div class="modal-body">
	<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
		'id'                   =>'vectors-form',
		'type'                 =>'horizontal',
		'htmlOptions'          =>array('class'=>'well'),
		// to enable ajax validation
		'enableAjaxValidation' =>true,
		'clientOptions'        =>array('validateOnSubmit'=>true, 'validateOnChange'=>true),
	)); ?>
	<fieldset>

	<?php 
		echo $form->dropDownListRow($model, 'providers_id', $providers, array('prompt' => 'Select traffic source'));
		echo $form->textFieldRow($model, 'name', array('class'=>'span3')); 
		echo $form->textFieldRow($model, 'rate', array('class'=>'span3')); 
	?>

	<div class="form-actions">
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'success', 'label'=>'Submit', 'htmlOptions' => array('name' => 'submit'))); ?>
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'reset', 'type'=>'reset', 'label'=>'Reset')); ?>
    </div>
	</fieldset>
	<?php $this->endWidget(); ?>
</div>

<div class="modal-footer">
	Fields with <span class="required">*</span> are required.
</div>
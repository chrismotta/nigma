<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'landings-form',
	'enableAjaxValidation'=>false,
)); ?>

<p class="help-block">Fields with <span class="required">*</span> are required.</p>

<?php echo $form->errorSummary($model); ?>

	<?php echo $form->textFieldRow($model,'default_color',array('class'=>'span5','maxlength'=>45)); ?>

	<?php echo $form->textFieldRow($model,'highlight_color',array('class'=>'span5','maxlength'=>45)); ?>

	<?php echo $form->textFieldRow($model,'background_color',array('class'=>'span5','maxlength'=>45)); ?>

	<?php echo $form->textFieldRow($model,'background_images_id',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'headline',array('class'=>'span5','maxlength'=>128)); ?>

	<?php echo $form->textFieldRow($model,'byline',array('class'=>'span5','maxlength'=>128)); ?>

	<?php echo $form->textFieldRow($model,'input_legend',array('class'=>'span5','maxlength'=>128)); ?>

	<?php echo $form->textFieldRow($model,'input_label',array('class'=>'span5','maxlength'=>45)); ?>

	<?php echo $form->textFieldRow($model,'input_eg',array('class'=>'span5','maxlength'=>45)); ?>

	<?php echo $form->textFieldRow($model,'select_label',array('class'=>'span5','maxlength'=>45)); ?>

	<?php echo $form->textFieldRow($model,'select_options',array('class'=>'span5','maxlength'=>256)); ?>

	<?php echo $form->textFieldRow($model,'tyc_headline',array('class'=>'span5','maxlength'=>128)); ?>

	<?php echo $form->textFieldRow($model,'tyc_body',array('class'=>'span5','maxlength'=>256)); ?>

	<?php echo $form->textFieldRow($model,'checkbox_label',array('class'=>'span5','maxlength'=>128)); ?>

	<?php echo $form->textFieldRow($model,'button_label',array('class'=>'span5','maxlength'=>45)); ?>

<div class="form-actions">
	<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Create' : 'Save',
		)); ?>
</div>

<?php $this->endWidget(); ?>

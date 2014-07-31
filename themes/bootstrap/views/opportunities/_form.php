<?php
/* @var $this OpportunitiesController */
/* @var $model Opportunities */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'opportunities-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'carriers_id'); ?>
		<?php echo $form->textField($model,'carriers_id'); ?>
		<?php echo $form->error($model,'carriers_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'rate'); ?>
		<?php echo $form->textField($model,'rate',array('size'=>11,'maxlength'=>11)); ?>
		<?php echo $form->error($model,'rate'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'model_adv'); ?>
		<?php echo $form->textField($model,'model_adv',array('size'=>3,'maxlength'=>3)); ?>
		<?php echo $form->error($model,'model_adv'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'product'); ?>
		<?php echo $form->textField($model,'product',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'product'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'account_manager_id'); ?>
		<?php echo $form->textField($model,'account_manager_id'); ?>
		<?php echo $form->error($model,'account_manager_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'comment'); ?>
		<?php echo $form->textField($model,'comment',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'comment'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'country_id'); ?>
		<?php echo $form->textField($model,'country_id'); ?>
		<?php echo $form->error($model,'country_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'wifi'); ?>
		<?php echo $form->textField($model,'wifi'); ?>
		<?php echo $form->error($model,'wifi'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'budget'); ?>
		<?php echo $form->textField($model,'budget',array('size'=>11,'maxlength'=>11)); ?>
		<?php echo $form->error($model,'budget'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'server_to_server'); ?>
		<?php echo $form->textField($model,'server_to_server',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'server_to_server'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'startDate'); ?>
		<?php echo $form->textField($model,'startDate'); ?>
		<?php echo $form->error($model,'startDate'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'endDate'); ?>
		<?php echo $form->textField($model,'endDate'); ?>
		<?php echo $form->error($model,'endDate'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'ios_id'); ?>
		<?php echo $form->textField($model,'ios_id'); ?>
		<?php echo $form->error($model,'ios_id'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
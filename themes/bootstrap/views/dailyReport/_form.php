<?php
/* @var $this DailyReportController */
/* @var $model DailyReport */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'daily-report-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'campaigns_id'); ?>
		<?php echo $form->textField($model,'campaigns_id'); ?>
		<?php echo $form->error($model,'campaigns_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'networks_id'); ?>
		<?php echo $form->textField($model,'networks_id'); ?>
		<?php echo $form->error($model,'networks_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'imp'); ?>
		<?php echo $form->textField($model,'imp'); ?>
		<?php echo $form->error($model,'imp'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'clics'); ?>
		<?php echo $form->textField($model,'clics'); ?>
		<?php echo $form->error($model,'clics'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'conv_api'); ?>
		<?php echo $form->textField($model,'conv_api'); ?>
		<?php echo $form->error($model,'conv_api'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'conv_adv'); ?>
		<?php echo $form->textField($model,'conv_adv'); ?>
		<?php echo $form->error($model,'conv_adv'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'spend'); ?>
		<?php echo $form->textField($model,'spend',array('size'=>11,'maxlength'=>11)); ?>
		<?php echo $form->error($model,'spend'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'model'); ?>
		<?php echo $form->textField($model,'model'); ?>
		<?php echo $form->error($model,'model'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'value'); ?>
		<?php echo $form->textField($model,'value'); ?>
		<?php echo $form->error($model,'value'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'date'); ?>
		<?php echo $form->textField($model,'date'); ?>
		<?php echo $form->error($model,'date'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
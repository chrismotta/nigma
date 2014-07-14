<?php
/* @var $this CampaignsController */
/* @var $model Campaigns */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'campaigns-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<!--div class="row">
		<?php echo $form->labelEx($model,'rec'); ?>
		<?php echo $form->textField($model,'rec'); ?>
		<?php echo $form->error($model,'rec'); ?>
	</div-->

	<div class="row">
		<?php echo $form->labelEx($model,'opportunities_id'); ?>
		<?php echo $form->textField($model,'opportunities_id'); ?>
		<?php echo $form->error($model,'opportunities_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'campaign_categories_id'); ?>
		<?php echo $form->textField($model,'campaign_categories_id'); ?>
		<?php echo $form->error($model,'campaign_categories_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'offer_type'); ?>
		<?php echo $form->textField($model,'offer_type'); ?>
		<?php echo $form->error($model,'offer_type'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'currency'); ?>
		<?php echo $form->textField($model,'currency'); ?>
		<?php echo $form->error($model,'currency'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'budget_type'); ?>
		<?php echo $form->textField($model,'budget_type'); ?>
		<?php echo $form->error($model,'budget_type'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'budget'); ?>
		<?php echo $form->textField($model,'budget',array('size'=>11,'maxlength'=>11)); ?>
		<?php echo $form->error($model,'budget'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'cap'); ?>
		<?php echo $form->textField($model,'cap',array('size'=>11,'maxlength'=>11)); ?>
		<?php echo $form->error($model,'cap'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'model'); ?>
		<?php echo $form->textField($model,'model'); ?>
		<?php echo $form->error($model,'model'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'bid'); ?>
		<?php echo $form->textField($model,'bid',array('size'=>11,'maxlength'=>11)); ?>
		<?php echo $form->error($model,'bid'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'comment'); ?>
		<?php echo $form->textField($model,'comment',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($model,'comment'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'status'); ?>
		<?php echo $form->textField($model,'status'); ?>
		<?php echo $form->error($model,'status'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'date_start'); ?>
		<?php echo $form->textField($model,'date_start'); ?>
		<?php echo $form->error($model,'date_start'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'date_end'); ?>
		<?php echo $form->textField($model,'date_end'); ?>
		<?php echo $form->error($model,'date_end'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
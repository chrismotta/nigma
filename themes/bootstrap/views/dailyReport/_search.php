<?php
/* @var $this DailyReportController */
/* @var $model DailyReport */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'id'); ?>
		<?php echo $form->textField($model,'id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'campaigns_id'); ?>
		<?php echo $form->textField($model,'campaigns_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'networks_id'); ?>
		<?php echo $form->textField($model,'networks_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'imp'); ?>
		<?php echo $form->textField($model,'imp'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'clics'); ?>
		<?php echo $form->textField($model,'clics'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'conv_api'); ?>
		<?php echo $form->textField($model,'conv_api'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'conv_adv'); ?>
		<?php echo $form->textField($model,'conv_adv'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'spend'); ?>
		<?php echo $form->textField($model,'spend',array('size'=>11,'maxlength'=>11)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'model'); ?>
		<?php echo $form->textField($model,'model'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'value'); ?>
		<?php echo $form->textField($model,'value'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'date'); ?>
		<?php echo $form->textField($model,'date'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->
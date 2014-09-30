<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'campaigns-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="help-block">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<?php echo $form->textFieldRow($model,'name',array('class'=>'span5','maxlength'=>128)); ?>

	<?php echo $form->textFieldRow($model,'networks_id',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'campaign_categories_id',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'wifi',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'formats_id',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'cap',array('class'=>'span5','maxlength'=>11)); ?>

	<?php echo $form->textFieldRow($model,'model',array('class'=>'span5','maxlength'=>3)); ?>

	<?php echo $form->textFieldRow($model,'ip',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'devices_id',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'url',array('class'=>'span5','maxlength'=>256)); ?>

	<?php echo $form->textFieldRow($model,'status',array('class'=>'span5','maxlength'=>8)); ?>

	<?php echo $form->textAreaRow($model,'comment',array('rows'=>6, 'cols'=>50, 'class'=>'span8')); ?>

	<?php echo $form->textFieldRow($model,'opportunities_id',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'banner_sizes_id',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'post_data',array('class'=>'span5')); ?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Create' : 'Save',
		)); ?>
	</div>

<?php $this->endWidget(); ?>

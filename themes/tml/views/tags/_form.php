<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'tags-form',
	'enableAjaxValidation'=>false,
)); ?>

<p class="help-block">Fields with <span class="required">*</span> are required.</p>

<?php echo $form->errorSummary($model); ?>

	<?php echo $form->textFieldRow($model,'campaigns_id',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'banner_sizes_id',array('class'=>'span5')); ?>

	<?php echo $form->dropDownListRow($model,'type',array("iframe"=>"iframe","javascript"=>"javascript",),array('class'=>'input-large')); ?>

	<?php echo $form->textAreaRow(
        $model,
        'code',
        array('class' => 'span5', 'rows' => 5)
        ); ?>
        
	<?php echo $form->textAreaRow(
        $model,
        'comment',
        array('class' => 'span5', 'rows' => 5)
        ); ?>

<div class="form-actions">
	<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Create' : 'Save',
		)); ?>
</div>

<?php $this->endWidget(); ?>

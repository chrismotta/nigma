<div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h4>Update Currency</h4>
</div>
<div class="modal-body">
<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'                   =>'currency-form',
	'type'                 =>'horizontal',
	'htmlOptions'          =>array('class'=>'well'),
	'enableAjaxValidation' =>false,
	'clientOptions'        =>array('validateOnSubmit'=>true, 'validateOnChange'=>true),
)); ?>

	<?php echo $form->errorSummary($model); ?>

	<?php echo $form->textFieldRow($model,'ARS',array('class'=>'span2','maxlength'=>255)); ?>
	<?php echo $form->textFieldRow($model,'EUR',array('class'=>'span2','maxlength'=>255)); ?>
	<?php echo $form->textFieldRow($model,'BRL',array('class'=>'span2','maxlength'=>255)); ?>	
	<?php echo $form->textFieldRow($model,'GBP',array('class'=>'span2','maxlength'=>255)); ?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType' =>'submit',
			'type'       =>'success',
			'label'      =>$model->isNewRecord ? 'Create' : 'Save',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
</div>
<div class="modal-footer">
    All values correspond to <span class="required">U$D 1</span>
</div>
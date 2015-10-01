
<div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h4><?php echo $action ?> Site <?php echo $model->isNewRecord ? "" : "#". $model->id; ?></h4>
</div>

<div class="modal-body">

	<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
		'id'=>'sites-form',
		'enableAjaxValidation'=>false,
		'type'                 =>'horizontal',
	)); ?>

	<?php echo $form->errorSummary($model); ?>

		<?php 

		if ( $model->isNewRecord )
	  		echo $form->dropDownListRow($model, 'providers_id', $publishers, array('prompt' => 'Select a traffic source'));

		echo $form->textFieldRow($model,'name',array('class'=>'span3','maxlength'=>255));
		// echo $form->textFieldRow($model,'model',array('class'=>'span5','maxlength'=>255));
  		echo $form->dropDownListRow($model, 'model', $model_pub, array('prompt' => 'Select a model'));
		echo $form->textFieldRow($model,'rate',array('class'=>'span2','maxlength'=>255), array('append' => '$'));
		echo $form->textFieldRow($model,'publisher_percentage',array('class'=>'span2','maxlength'=>255), array('prepend' => '%'));
		
		?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
				'buttonType'=>'submit',
				'type'=>'primary',
				'label'=>$model->isNewRecord ? 'Create' : 'Save',
			)); ?>
	</div>

	<?php $this->endWidget(); ?>

</div>

<div class="modal-footer">
    Edit Site attributes. Fields with <span class="required">*</span> are required.
</div>
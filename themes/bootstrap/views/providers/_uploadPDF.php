<?php
/* @var $this ProvidersController */
/* @var $model Providers */
?>

<div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h4>Provider <?php echo "#".$model->id ?></h4>
</div>

<div class="modal-body">

	<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
		'id'                   =>'providers-uploadfile-form',
		'type'                 =>'horizontal',
		'htmlOptions'          =>array('class'=>'well', 'enctype'=>'multipart/form-data'),
		// to enable ajax validation
		'enableAjaxValidation' =>true,
		'clientOptions'        =>array('validateOnSubmit'=>true, 'validateOnChange'=>true),
    )); ?>
    <fieldset>
	
		<small>Select file signed by Provider</small><hr>
		<div class="control-group">
	        <?php echo CHtml::label('File: ', 'file', array('class'=>'control-label')); ?>
			<div class="controls">
	        	<?php echo CHtml::fileField('upload-file', 'file', array('class'=>'')); ?>
	        </div>
	    </div>
        
    <?php //echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
    <div class="form-actions">
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'success', 'label'=>'Upload', 'htmlOptions'=>array('name'=>'submit'))); ?>
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'reset', 'type'=>'reset', 'label'=>'Reset')); ?>
    </div>
    </fieldset>

    <?php $this->endWidget(); ?>
</div>

<div class="modal-footer">
    Upload signed file
</div>
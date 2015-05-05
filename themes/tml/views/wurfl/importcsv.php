<div class="form">
 
<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id'=>'service-form',
    'enableAjaxValidation'=>false,
    'method'=>'post',
    'type'=>'horizontal',
    'htmlOptions'=>array(
        'enctype'=>'multipart/form-data'
    )
)); ?>
 
    <fieldset>
        <legend>
            <p class="note">Fields with <span class="required">*</span> are required.</p>
        </legend>
 
        <?php echo $form->errorSummary($model, 'Opps!!!', null, array('class'=>'alert alert-error span12')); ?>
 
        <div class="control-group">     
            <div class="span4">
                                <div class="control-group <?php if ($model->hasErrors('postcode')) echo "error"; ?>">
        <?php echo $form->labelEx($model,'file'); ?>
        <?php echo $form->fileField($model,'file'); ?>
        <?php echo $form->error($model,'file'); ?>
                            </div>
 
 
            </div>
        </div>
 
        <div class="form-actions">
            <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'primary', 'icon'=>'ok white', 'label'=>'UPLOAD')); ?>
            <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'reset', 'icon'=>'remove', 'label'=>'Reset')); ?>
        </div>
 
    </fieldset>
 
<?php $this->endWidget(); ?>
 
</div><!-- form -->
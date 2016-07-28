<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'landings-form',
	'enableAjaxValidation'=>false,
)); ?>

<p class="help-block">Fields with <span class="required">*</span> are required.</p>

<?php echo $form->errorSummary($model); ?>

	<?php echo $form->textFieldRow($model,'name',array('class'=>'span5','maxlength'=>128)); ?>

	<?php echo $form->textFieldRow($model,'country_id',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'default_color',array('class'=>'span5','maxlength'=>45)); ?>

	<?php echo $form->textFieldRow($model,'highlight_color',array('class'=>'span5','maxlength'=>45)); ?>

	<?php echo $form->textFieldRow($model,'background_color',array('class'=>'span5','maxlength'=>45)); ?>

	<?php echo $form->textFieldRow($model,'headline',array('class'=>'span5','maxlength'=>128)); ?>

	<?php echo $form->textFieldRow($model,'byline',array('class'=>'span5','maxlength'=>128)); ?>

	<?php echo $form->textFieldRow($model,'input_legend',array('class'=>'span5','maxlength'=>128)); ?>

	<?php echo $form->textFieldRow($model,'input_label',array('class'=>'span5','maxlength'=>45)); ?>

	<?php echo $form->textFieldRow($model,'input_eg',array('class'=>'span5','maxlength'=>45)); ?>

	<?php echo $form->textFieldRow($model,'select_label',array('class'=>'span5','maxlength'=>45)); ?>

	<?php echo $form->textFieldRow($model,'select_options',array('class'=>'span5','maxlength'=>256)); ?>

	<?php echo $form->textFieldRow($model,'tyc_headline',array('class'=>'span5','maxlength'=>128)); ?>

	<?php // echo $form->textAreaRow($model,'tyc_body',array('class'=>'span5', 'rows'=>'4')); ?>

	<?php echo $form->ckEditorRow(
            $model,
            'tyc_body',
            array(
                'editorOptions' => array(
                    'fullpage' => 'js:true',
                    'width' => '640',
                    'resize_maxWidth' => '640',
                    'resize_minWidth' => '320',
                )
            )
        ); ?>
    <code style="margin-bottom:10px;display: inline-block;">Email macro: $$mail$$</code>

    <br/>

	<?php echo $form->textFieldRow($model,'checkbox_label',array('class'=>'span5','maxlength'=>128)); ?>

	<?php echo $form->textFieldRow($model,'button_label',array('class'=>'span5','maxlength'=>45)); ?>

	<?php echo $form->textFieldRow($model,'thankyou_msg',array('class'=>'span5','maxlength'=>128)); ?>

	<?php echo $form->textFieldRow($model,'validate_msg',array('class'=>'span5','maxlength'=>128)); ?>

	<?php echo $form->dropDownListRow($model, 'background_images_id', $background_images_id, 
		array( 'prompt' => 'No background image', )); ?>
	<?php echo $form->dropDownListRow($model, 'headline_images_id', $headline_images_id, 
		array('prompt' => 'No headline image',)); ?>
	<?php echo $form->dropDownListRow($model, 'byline_images_id', $byline_images_id, 
		array('prompt' => 'No byline image')); ?>

<div class="form-actions">
	<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Create' : 'Save',
		)); ?>
</div>

<?php $this->endWidget(); ?>

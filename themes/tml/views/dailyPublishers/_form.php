<div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h4>Exchange Report #<?php $model->id; ?></h4>
</div>


<div class="modal-body">

<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'daily-publishers-form',
    'type'=>'horizontal',
    'htmlOptions'=>array('class'=>'well'),
	'enableAjaxValidation'=>true,
    'clientOptions'=>array('validateOnSubmit'=>true, 'validateOnChange'=>true),
)); ?>

<?php echo $form->errorSummary($model); ?>

	<?php echo $form->datepickerRow($model,'date',
		array(
			'options'=>array(
                'autoclose'      => true,
                'todayHighlight' => true,
                'format'         => 'yyyy-mm-dd',
                'viewformat'     => 'dd-mm-yyyy',
                'placement'      => 'right',
	            ),
	        'htmlOptions'=>array(
				'class'    =>'span2',
				'disabled' => true,
	        	)
	        ),
        array(
        	'prepend'=>'<i class="icon-calendar"></i>'
        	)
        ); ?>

	<?php echo $form->textFieldRow($model,'placements_id',array('class'=>'span3', 'disabled' => true)); ?>

	<?php echo $form->textFieldRow($model,'exchanges_id',array('class'=>'span3', 'disabled' => true)); ?>

	<?php echo $form->textFieldRow($model,'country_id',array('class'=>'span3', 'disabled' => true)); ?>

	<?php echo $form->textFieldRow($model,'devices_id',array('class'=>'span3', 'disabled' => true)); ?>

	<hr/>

	<?php echo $form->textFieldRow($model,'ad_request',array('class'=>'span3','maxlength'=>11)); ?>

	<?php echo $form->textFieldRow($model,'imp_exchange',array('class'=>'span3','maxlength'=>11)); ?>

	<?php echo $form->textFieldRow($model,'imp_publishers',array('class'=>'span3','maxlength'=>11)); ?>

	<?php echo $form->textFieldRow($model,'imp_passback',array('class'=>'span3','maxlength'=>11)); ?>

	<?php // echo $form->textFieldRow($model,'imp_count',array('class'=>'span3','maxlength'=>11)); ?>

	<?php echo $form->textFieldRow($model,'clicks',array('class'=>'span3')); ?>

	<?php echo $form->textFieldRow($model,'revenue',array('class'=>'span3','maxlength'=>11)); ?>

	<?php // echo $form->textFieldRow($model,'spend',array('class'=>'span3','maxlength'=>11)); ?>

	<?php // echo $form->textFieldRow($model,'profit',array('class'=>'span3','maxlength'=>11)); ?>

	<?php // echo $form->textFieldRow($model,'profit_percent',array('class'=>'span3','maxlength'=>11)); ?>

	<?php // echo $form->textFieldRow($model,'eCPM',array('class'=>'span3','maxlength'=>11)); ?>

	<hr/>
	
	<?php echo $form->textAreaRow($model,'comment',array('class'=>'span3','maxlength'=>255, 'rows' => 5)); ?>

<div class="form-actions">
	<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'success',
			'label'=>$model->isNewRecord ? 'Create' : 'Save',
		)); ?>
        <?php $this->widget('bootstrap.widgets.TbButton', array(
        	'buttonType'=>'reset', 
        	'type'=>'reset', 
        	'label'=>'Reset')); ?>

</div>

<?php $this->endWidget(); ?>


</div>

<div class="modal-footer">
    Update Exchange Report. Fields with <span class="required">*</span> are required.
</div>
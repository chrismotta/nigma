<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

		<?php echo $form->textFieldRow($model,'id',array('class'=>'span5')); ?>

		<?php echo $form->textFieldRow($model,'providers_id',array('class'=>'span5')); ?>

		<?php echo $form->textFieldRow($model,'exchanges_id',array('class'=>'span5')); ?>

		<?php echo $form->dropDownListRow($model,'status',array("Started"=>"Started","Processing"=>"Processing","Completed"=>"Completed","Error"=>"Error",),array('class'=>'input-large')); ?>

		<?php echo $form->textFieldRow($model,'start_time',array('class'=>'span5')); ?>

		<?php echo $form->textFieldRow($model,'end_time',array('class'=>'span5')); ?>

		<?php echo $form->datepickerRow($model,'data_date',array('options'=>array(),'htmlOptions'=>array('class'=>'span5')),array('prepend'=>'<i class="icon-calendar"></i>','append'=>'Click on Month/Year at top to select a different year or type in (mm/dd/yyyy).')); ?>

		<?php echo $form->textAreaRow($model,'message',array('rows'=>6, 'cols'=>50, 'class'=>'span8')); ?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType' => 'submit',
			'type'=>'primary',
			'label'=>'Search',
		)); ?>
	</div>

<?php $this->endWidget(); ?>

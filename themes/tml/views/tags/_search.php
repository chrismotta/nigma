<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

		<?php echo $form->textFieldRow($model,'id',array('class'=>'span5')); ?>

		<?php echo $form->textFieldRow($model,'campaigns_id',array('class'=>'span5')); ?>

		<?php echo $form->textFieldRow($model,'banner_sizes_id',array('class'=>'span5')); ?>

		<?php echo $form->dropDownListRow($model,'type',array("iframe"=>"iframe","javascript"=>"javascript",),array('class'=>'input-large')); ?>

		<?php echo $form->textFieldRow($model,'code',array('class'=>'span5','maxlength'=>255)); ?>

		<?php echo $form->textFieldRow($model,'comment',array('class'=>'span5','maxlength'=>128)); ?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType' => 'submit',
			'type'=>'primary',
			'label'=>'Search',
		)); ?>
	</div>

<?php $this->endWidget(); ?>

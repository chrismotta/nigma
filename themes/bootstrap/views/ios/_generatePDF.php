<?php
/* @var $this IosController */
/* @var $model Ios */
?>

<div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h4>Io <?php echo "#".$model->id ?></h4>
</div>

<div class="modal-body">

	<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
		'id'                   =>'ios-pdf-form',
		'type'                 =>'horizontal',
		'htmlOptions'          =>array('class'=>'well'),
		// to enable ajax validation
		'enableAjaxValidation' =>true,
		'clientOptions'        =>array('validateOnSubmit'=>true, 'validateOnChange'=>true),
    )); ?>

	<h5>Select Opportunities:</h5>
	<div class="controls form-inline" style="margin-left: 30px; margin-bottom: 20px;">
	<?php echo CHtml::checkBoxList('opp_ids', array(true), CHtml::listData($model->getOpportunities(), 'id', 'virtualName'), array()); ?>
	</div>

    <div class="form-actions">
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'success', 'label'=>'Generate PDF', 'htmlOptions' => array('name' => 'submit'))); ?>
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'cancel', 'type'=>'reset', 'label'=>'Cancel')); ?>
    </div>
    </fieldset>

    <?php $this->endWidget(); ?>

</div>

<div class="modal-footer">
    PDF generation.
</div>
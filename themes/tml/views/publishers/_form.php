<?php
/* @var $this PublishersController */
/* @var $model Publishers */
/* @var $form CActiveForm */
/* @var $currency currency */
/* @var $model_publ model_publ */
?>

<div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h4>Publisher <?php echo $model->isNewRecord ? "" : "#". $model->id; ?></h4>
</div>


<div class="modal-body">

    <?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
		'id'                   =>'publishers-form',
		'type'                 =>'horizontal',
		'htmlOptions'          =>array('class'=>'well'),
		// to enable ajax validation
		'enableAjaxValidation' =>true,
		'clientOptions'        =>array('validateOnSubmit'=>true, 'validateOnChange'=>true),
    )); ?>
    <fieldset>
	<?php 

        if ( ! $model->isNewRecord ) {
    		echo $form->textFieldRow($model, 'providers_d', array('type'=>'hidden', 'class'=>'span3', 'readonly'=>true));
    	}

        $this->renderPartial('/providers/_form', array(
            'form'  => $form,
            'model' => $modelProv,
        ));

        echo $form->hiddenField($model, 'account_manager_id', array('type'=>"hidden") );
        echo $form->textFieldRow($model, 'RS_perc', array('class'=>'span3'));
        echo $form->textFieldRow($model, 'rate', array('class'=>'span3'));
    ?>

    <div class="form-actions">
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'success', 'label'=>'Submit')); ?>
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'reset', 'type'=>'reset', 'label'=>'Reset')); ?>
    </div>
    </fieldset>

    <?php $this->endWidget(); ?>
</div>

<div class="modal-footer">
    Edit Publisher attributes. Fields with <span class="required">*</span> are required.
</div>
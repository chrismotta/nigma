<?php
/* @var $this PublishersController */
/* @var $model Publishers */
/* @var $form CActiveForm */
/* @var $currency currency */
/* @var $model_publ model_publ */
?>

<div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h4>Publisher <?php echo $modelProvs->isNewRecord ? "" : "#". $modelProvs->id; ?></h4>
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

        // if ( ! $modelProvs->isNewRecord )
    		// echo $form->textFieldRow($modelProvs, 'providers_id', array('type'=>'hidden', 'class'=>'span3', 'readonly'=>true));

        $this->renderPartial('/providers/_form', array(
            'form'  => $form,
            'model' => $modelProvs,
        ));

        echo $form->hiddenField($modelPubl, 'account_manager_id', array('type'=>"hidden") );
        echo $form->textFieldRow($modelPubl, 'RS_perc', array('class'=>'span2'), array('prepend'=>'%'));
        echo $form->textFieldRow($modelPubl, 'rate', array('class'=>'span2'), array('prepend'=>'$'));
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
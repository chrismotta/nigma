<?php
/* @var $this UsersController */
/* @var $model Users */

$this->breadcrumbs=array(
	// 'Users'=>array('index'),
	'Manage Users',
);

$this->menu=array(
	// array('label'=>'List Users', 'url'=>array('index')),
	// array('label'=>'Create Users', 'url'=>array('create')),
);

?>

<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'id'                   =>'users-form',
	'type'                 =>'horizontal',
	'htmlOptions'          =>array('class'=>'well'),
	// to enable ajax validation
	'enableAjaxValidation' =>true,
	'clientOptions'        =>array('validateOnSubmit'=>true, 'validateOnChange'=>true),
)); ?>
<fieldset>
    <?php 

    if ( ! $model->isNewRecord ) {
		echo $form->hiddenField($model, 'id');
	}
	echo $form->textFieldRow($model, 'username', array('class'=>'span3','readonly'=>true));
	echo $form->textFieldRow($model, 'name', array('class'=>'span3'));
    echo $form->textFieldRow($model, 'lastname', array('class'=>'span3'));

    echo $form->passwordFieldRow($model, 'password', array('class'=>'span3'));
    echo $form->passwordFieldRow($model, 'repeat_password', array('class'=>'span3'));

    echo $form->textFieldRow($model, 'email', array('class'=>'span3'));
    //echo $form->radioButtonListRow($model, 'status', $status);

    ?>

<?php //echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
<div class="form-actions">
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'success', 'label'=>'Submit')); ?>
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'reset', 'type'=>'reset', 'label'=>'Reset')); ?>
</div>
</fieldset>

<?php $this->endWidget(); ?>
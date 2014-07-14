<?php
/* @var $this CampaignsController */
/* @var $model Campaigns */
/* @var $form CActiveForm */
?>

<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'id'=>'campaigns-form',
    'type'=>'horizontal',
    'htmlOptions'=>array('class'=>'well'),
)); ?>

<fieldset>
    <?php 
    echo $form->textFieldRow($model, 'name');
    echo $form->dropDownListRow($model, 'campaign_categories_id', array('Select category', 'Adult', 'Autos'));
    echo $form->radioButtonListInlineRow($model, 'offer_type', array('CPC','CPM'));
    echo $form->textFieldRow($model, 'currency', array('prepend'=>'$'));
    echo $form->radioButtonListInlineRow($model, 'budget_type', array('CPC','CPM'));
    echo $form->textFieldRow($model, 'budget', array('prepend'=>'$'));
    echo $form->textFieldRow($model, 'cap', array('prepend'=>'$'));
    echo $form->radioButtonListInlineRow($model, 'model', array('CPC','CPM'));
    

    ?>
</fieldset>

<?php $this->endWidget(); ?>

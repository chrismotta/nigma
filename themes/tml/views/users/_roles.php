<?php
/* @var $this UsersController */
/* @var $model Users */
/* @var $form CActiveForm */
?>

    <?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
		'id'                   =>'users-form',
		'type'                 =>'horizontal',
		'htmlOptions'          => array('class'=>'well'),
		// to enable ajax validation
		'enableAjaxValidation' =>true,
		'clientOptions'        =>array('validateOnSubmit'=>true, 'validateOnChange'=>true),
    )); ?>
    <fieldset>
        
        <h5>User</h5>
        <?php $this->widget('bootstrap.widgets.TbDetailView', array(
            'type'=>'striped bordered condensed',
            'data'=>$model,
            'attributes'=>array(
                'id',
                'name',
                'lastname'
            ),
        )); ?>
        
        <h5>Select roles</h5>
        <?php 

        $userRol = array_keys(Yii::app()->authManager->getRoles(Yii::app()->user->id));
        if ( in_array('media_buyer_admin', $userRol, true) )
            $userRestriction = array('publisher','publisherCPM');

        if ( in_array('operation_manager', $userRol, true) )
            $userRestriction = array('publisher','publisherCPM');        

        foreach($roles as $data) {
            
            if(isset($userRestriction))
                if(!in_array($data->name, $userRestriction)) continue;

            $dataRow['name']  = $data->name;
            $dataRow['type']  = 'raw';
            $dataRow['label'] = '';
            
            // Create array config for attribute property for DetailView            
            $isAssigned       = Yii::app()->authManager->checkAccess($data->name, $model->id);
            $dataRow['value'] = CHtml::checkbox($data->name, $isAssigned, array('style'=>'margin:0 5px') ) . " " . CHtml::label($data->name, $data->name, array('class'=>'label'));
            $attributesGrid[] =  $dataRow;

            // Create array for data property for DetailView
            $rName            = $dataRow['name'];
            $dataGrid[$rName] = $dataRow['value'];

        } 

        $this->widget('bootstrap.widgets.TbDetailView', array(
            'type'       => 'striped bordered condensed',
            'data'       => $dataGrid,
            'attributes' => $attributesGrid,
        ));
    
        ?> 
        

    <?php //echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
    <div class="form-actions">
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'success', 'label'=>'Submit', 'htmlOptions' => array('name' => 'submit') )); ?>
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'reset', 'type'=>'reset', 'label'=>'Reset')); ?>
    </div>
    </fieldset>

    <?php $this->endWidget(); ?>

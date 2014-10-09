<?php
/* @var $this UsersController */
/* @var $model Users */
/* @var $form CActiveForm */
?>

<div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h4>Permissions User <?php echo "#". $model->id; ?></h4>
</div>


<div class="modal-body">

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
        <?php foreach($roles as $data) {
            $dataRow['name']  = $data->name;
            $dataRow['type']  = 'raw';
            $dataRow['label'] = '';
            
            // Create array config for attribute property for DetailView            
            $isAssigned       = Yii::app()->authManager->checkAccess($data->name, $model->id);
            $dataRow['value'] = CHtml::checkbox($data->name, $isAssigned, array('class'=>'span1') ) . " " . CHtml::label($data->name, $data->name, array('class'=>'label'));
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
</div>

<div class="modal-footer">
    Edit User attributes. Fields with <span class="required">*</span> are required.
</div>

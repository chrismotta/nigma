<?php
/* @var $this IosController */
/* @var $model Ios */
/* @var $form CActiveForm 
 *
 * @var currency
 * @var entity
 * @var commercial
 * @var advertiser
 * @var country
 */
?>

<div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h4>IOs <?php echo $model->isNewRecord ? "" : "#". $model->id; ?></h4>
</div>


<div class="modal-body">

    <?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id'                   =>'ios-form',
        'type'                 =>'horizontal',
        'htmlOptions'          =>array('class'=>'well'),
        // to enable ajax validation
        'enableAjaxValidation' =>true,
        'clientOptions'        =>array('validateOnSubmit'=>true, 'validateOnChange'=>true),
    )); ?>
    <fieldset>
        <?php 

        if ( ! $model->isNewRecord ) {
            echo $form->textFieldRow($model, 'id', array('type'=>'hidden', 'class'=>'span3', 'readonly'=>true));
        }
            echo $form->datepickerRow($model, 'date', array(
                    'options' => array(
                        'autoclose'      => true,
                        'todayHighlight' => true,
                        'format'         => 'yyyy-mm-dd',
                        'viewformat'     => 'dd-mm-yyyy',
                        'placement'      => 'right',
                    ),
                    'htmlOptions' => array(
                        'class' => 'span3',
                        'id'=>'Ios_date_form'
                    )),
                    array(
                        'append' => '<label for="Ios_date_form"><i class="icon-calendar"></i></label>',
                    )
            );
            echo'<div class="control-group">
                    <label class="control-label required" for="Ios_date">Fiance Enttity <span class="required">*</span></label>
                    <div class="controls">';
            echo KHtml::filterFinanceEntities(null,array('class'=>'span3'),'check','Ios[finance_entities_id]');
            //echo $form->textFieldRow($model, 'finance_entities_id', array('class'=>'span3'));
            echo '</div></div><br><span id="opp_ids"></span>';
        ?>
        
    <?php //echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
    <div class="form-actions">
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'success', 'label'=>'Submit')); ?>
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'reset', 'type'=>'reset', 'label'=>'Reset')); ?>
    </div>
    </fieldset>

    <?php $this->endWidget(); ?>
</div>

<div class="modal-footer">
    Edit Insertion Order attributes. Fields with <span class="required">*</span> are required.
</div>
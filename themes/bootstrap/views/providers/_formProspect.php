<?php
/* @var $this AffiliatesController */
/* @var $model Affiliates */
/* @var $form CActiveForm */
?>

<div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h4>Prospects</h4>
</div>


<div class="modal-body">

    <?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
		'id'                   =>'providers-form',
		'type'                 =>'horizontal',
		'htmlOptions'          =>array('class'=>'well'),
		// to enable ajax validation
		'enableAjaxValidation' =>true,
		'clientOptions'        =>array('validateOnSubmit'=>true, 'validateOnChange'=>true),
    )); ?>
    <fieldset>
        <?php 

        echo $form->textFieldRow($model, 'name', array('class'=>'span3'));
        // echo 'Entity: '.$form->dropDownList($model, 'entity', KHtml::enumItem($model, 'entity')).'<br/>';
        echo $form->dropDownListRow($model, 'entity', KHtml::enumItem($model, 'entity'));
        echo '<div class="control-group">';
        echo '  <label class="control-label required" for="Providers_types">Type <span class="required">*</span></label>';
        echo '  <div class="controls">';
        echo CHtml::dropDownList('type', null, $model->getAllTypes(), array());
        echo '  </div>';
        echo '</div>';
        echo $form->dropDownListRow($model, 'model', KHtml::enumItem($model, 'model'));
        echo $form->textFieldRow($model, 'net_payment', array('class'=>'span3'));
        echo $form->dropDownListRow($model, 'deal', KHtml::enumItem($model, 'deal'), array(
            'onChange' => ' 
              if ($("#Providers_deal").val() == "POST-PAYMENT")
                $(".post_payment_amount").show();
              else
                $(".post_payment_amount").hide();
            ',
          ));
        echo '<div style="display: ' . ($model->deal == 'POST-PAYMENT' ? 'block' : 'none') . '" class="post_payment_amount">';
        echo $form->textFieldRow($model, 'post_payment_amount', array('class'=>'span3'));
        echo '</div>';
        echo $form->datepickerRow($model, 'start_date', array(
                'options' => array(
                    'autoclose'      => true,
                    'todayHighlight' => true,
                    'clearBtn'       => true,
                    'format'         => 'yyyy-mm-dd',
                    'viewformat'     => 'dd-mm-yyyy',
                    'placement'      => 'right',
                ),
                'htmlOptions' => array(
                    'class' => 'span3',
                )),
                array(
                    'append' => '<label for="Providers_start_date"><i class="icon-calendar"></i></label>',
                )
        );
        echo $form->datepickerRow($model, 'end_date', array(
                'options' => array(
                    'autoclose'      => true,
                    'todayHighlight' => true,
                    'clearBtn'       => true,
                    'format'         => 'yyyy-mm-dd',
                    'viewformat'     => 'dd-mm-yyyy',
                    'placement'      => 'right',
                ),
                'htmlOptions' => array(
                    'class' => 'span3',
                )),
                array(
                    'append' => '<label for="Providers_end_date"><i class="icon-calendar"></i></label>',
                )
        );
        echo $form->textFieldRow($model, 'daily_cap', array('class'=>'span3'));
        echo $form->textFieldRow($model, 'sizes', array('class'=>'span3'));
        echo $form->dropDownListRow($model, 'currency', KHtml::enumItem($model, 'currency'), array('prompt' => 'Select a currency'));

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
    Edit Prospects attributes. Fields with <span class="required">*</span> are required.
</div>
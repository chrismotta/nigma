<?php
/* @var $this OpportunitiesController
 * @var $model Opportunities
 * @var $form CActiveForm 
 * @var $advertiser Advertisers[]
 * @var $ios Ios[]
 * @var $account Users[]
 * @var $country GeoLocation[]
 * @var $carrier Carriers[]
 * @var model_adv Model Adv values
 * @var wifi true-false
 */
?>

<div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h4>Opportunity <?php echo $model->isNewRecord ? "" : "#". $model->id; ?></h4>
</div>


<div class="modal-body">

    <?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
		'id'                   =>'opportunities-form',
		'type'                 =>'horizontal',
		'htmlOptions'          =>array('class'=>'well'),
		// to enable ajax validation
		'enableAjaxValidation' =>true,
		'clientOptions'        =>array('validateOnSubmit'=>true, 'validateOnChange'=>true),
    )); ?>
    <fieldset>

      <?php 

      if ( $model->isNewRecord ) {
        echo $form->dropDownListRow($model, 'advertiser_name', $advertiser, 
            array(
              'prompt'   => 'Select an advertiser', 
              'onChange' => '
                  if ( ! this.value) {
                    return;
                  }
                  $.post(
                      "getIos/"+this.value,
                      "",
                      function(data)
                      {
                          // alert(data);
                        $(".ios-dropdownlist").html(data);
                      }
                  )
                  '
            ));
        echo $form->dropDownListRow($model, 'ios_id', $ios, array('class'=>'ios-dropdownlist', 'prompt' => 'Select an IOs'));
      } else {
        echo $form->textFieldRow($model, 'id', array('type'=>'hidden', 'class'=>'span3', 'readonly'=>true, ));
        echo $form->textFieldRow($advertiser, 'name', array('class'=>'span3', 'readonly'=>true, 'labelOptions'=>array('label'=>$ios->getAttributeLabel('advertisers_id')) ));
        echo $form->textFieldRow($ios, 'name', array('class'=>'span3', 'readonly'=>true, 'labelOptions'=>array('label'=>$model->getAttributeLabel('ios_id')) ));
      
      }

      echo $form->dropDownListRow($model, 'country_id', $country, 
        array(
          'prompt' => 'Select a country', 
          'onChange' => '
                  if ( ! this.value) {
                    return;
                  }
                  $.post(
                      "getCarriers/"+this.value,
                      "",
                      function(data)
                      {
                        // alert(data);
                        $(".carriers-dropdownlist").html(data);
                      }
                  )
                  ',
        ));
      echo $form->checkboxRow($model, 'multi_carrier', 
            array(
              'onChange' => '
                  if (this.checked == "1") {
                    //alert("ok");
                    $("#Opportunities_carriers_id option:eq(0)").prop("selected", true);
                    $("#Opportunities_carriers_id").prop("disabled", true);
                    $("#Opportunities_rate").prop("disabled", true);
                    $("#Opportunities_rate").val("");
                  }else{
                    //alert("no");
                    $("#Opportunities_carriers_id").prop("disabled", false);
                    $("#Opportunities_rate").prop("disabled", false);
                    $("#Opportunities_rate").val("");
                  }
                  return;
                  '
            ));
      echo $form->dropDownListRow($model, 'carriers_id', $carrier, array('class'=>'carriers-dropdownlist', 'prompt' => 'Select a carrier', 'encode'=>false));
      echo $form->textFieldRow($model, 'rate', array('class'=>'span3'));
      echo $form->radioButtonListInlineRow($model, 'model_adv', $model_adv);
      echo $form->textFieldRow($model, 'product', array('class'=>'span3'));
      echo $form->dropDownListRow($model, 'account_manager_id', $account , array('prompt' => 'Select an Account Manager'));
      echo $form->checkboxRow($model, 'wifi');
      echo $form->textFieldRow($model, 'budget', array('class'=>'span3'));
      echo $form->textFieldRow($model, 'server_to_server', array('class'=>'span3'));
      echo $form->textFieldRow($model, 'startDate', array('class'=>'span3'));
      echo $form->textFieldRow($model, 'endDate', array('class'=>'span3'));
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
    Edit Opportunity attributes. Fields with <span class="required">*</span> are required.
</div>
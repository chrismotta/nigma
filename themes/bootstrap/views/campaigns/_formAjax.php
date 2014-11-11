<?php
/* @var $this CampaignsController
 * @var $model Campaigns 
 * @var $form CActiveForm 
 */
?>

<div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h4><?php echo $action ?> campaign <?php echo $action=="Update" ? "#".$model->id : "" ?></h4>
</div>


<div class="modal-body">

<?php 
/*
// interacción con oportunidades desactivada

if($action == "Create"){ ?>


    <div class="botonera-l">
    <?php
    $this->widget('bootstrap.widgets.TbButtonGroup', array(
        'toggle' => 'radio',
        'type'   => 'info',
        'buttons'=>array(
            array(
                'label'       => 'New Opportunity',
                'buttonType'  => 'button',
                'htmlOptions' => array(
                    'id'      => 'new_opp', 
                    'onclick' => '$("#formOpp").show("3000");'
                    ),
                ),
            array(
                'label'       => 'Existent Opportunity',
                'buttonType'  => 'button',
                'active'      => true,
                'htmlOptions' => array(
                    'id'      => 'exis_opp', 
                    'onclick' => '$("#formOpp").hide("3000");'
                    ),
                )
            ),
        )
    );
    ?>
    </div>




    <div id="formOpp" class="botonera-l" style="display:none" >
    <?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id'=>'opportunities-form',
        'type'=>'horizontal',
        'htmlOptions'=>array('class'=>'well'),
        // to enable ajax validation
        'enableAjaxValidation'=>true,
        'clientOptions'=>array('validateOnSubmit'=>true, 'validateOnChange'=>true),
    )); ?>
    <fieldset>
        <h5 class="form-subtittle">New Opportunity</h5>
        <?php
        echo $form->textFieldRow($modelOpp, 'ios_id', array('class'=>'span4'));
        echo $form->textFieldRow($modelOpp, 'account_manager_id', array('class'=>'span4'));
        echo $form->textFieldRow($modelOpp, 'carriers_id', array('class'=>'span4'));
        echo $form->textFieldRow($modelOpp, 'product', array('class'=>'span4'));
        echo $form->radioButtonListInlineRow($modelOpp, 'model_adv', array('CPC','CPM'));
        echo $form->textFieldRow($modelOpp, 'budget', array('prepend'=>'$'));
        echo $form->textFieldRow($modelOpp, 'rate', array('prepend'=>'$'));
        ?>
        <div class="form-actions">
        <?php 
        $this->widget(
            'bootstrap.widgets.TbButton', 
            array(
                'buttonType'  =>'ajaxSubmit', 
                'url'         =>'', 
                'type'        =>'success', 
                'label'       =>'Submit',
                'ajaxOptions' => array(
                    'success'=>'function(data) {

                        console.log(data);
                        //alert("success");


                        if(data=="successfull"){

                            $("#formOpp").hide("3000");
                            $("#new_opp").removeClass("active");
                            $("#exis_opp").addClass("active");
                        }
                    }'
                )
            )
        ) 
        ?>
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'reset', 'type'=>'reset', 'label'=>'Reset')); ?>
        </div>
    </fieldset>
    <?php $this->endWidget(); ?>
    </div>


<?php } 
*/
?>



    <?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id'=>'campaigns-form',
        'type'=>'horizontal',
        'htmlOptions'=>array('class'=>'well'),
        // to enable ajax validation
        'enableAjaxValidation'=>true,
        'clientOptions'=>array('validateOnSubmit'=>true, 'validateOnChange'=>true),
    )); ?>
    <fieldset>
        <!--h5 class="form-subtittle">Campaign</h5-->

        <?php 

        if($action == "Create"){
            echo $form->dropDownListRow($model, 'opportunities_id', $opportunities, array('prompt' => 'Select an opportunitiy'));
        }

        echo $form->textFieldRow($model, 'name', array('class'=>'span3'));
        echo '<hr/>';
        echo $form->dropDownListRow($model, 'networks_id', $networks, array('prompt' => 'Select a network'));
        echo $form->dropDownListRow($model, 'campaign_categories_id', $categories, array('prompt' => 'Select a category'));
        echo $form->checkboxRow($model, 'wifi');
        echo $form->checkboxRow($model, 'ip');
        echo $form->dropDownListRow($model, 'formats_id', $formats, array('prompt' => 'Select a format'));
        echo $form->dropDownListRow($model, 'devices_id', $devices, array('prompt' => 'Select a format'));
        echo '<hr/>';
        echo $form->textFieldRow($model, 'cap', array('prepend'=>'$'));
        echo $form->radioButtonListRow($model, 'model', $campModel);
        echo '<hr/>';
        echo $form->textFieldRow($model, 'url', array('class'=>'span3'));

        if( FilterManager::model()->isUserTotalAccess('campaign.post_data') ) {
            echo '<hr/>';
            echo $form->checkboxRow($model, 'post_data');
        }

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
    Edit campaign attributes. Fields with <span class="required">*</span> are required.
</div>

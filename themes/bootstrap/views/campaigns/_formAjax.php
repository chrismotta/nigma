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

<?php if($action == "Create"){ ?>


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
        echo $form->textFieldRow($modelOpp, 'manager_id', array('class'=>'span4'));
        echo $form->textFieldRow($modelOpp, 'carrier', array('class'=>'span4'));
        echo $form->textFieldRow($modelOpp, 'product', array('class'=>'span4'));
        echo $form->radioButtonListInlineRow($modelOpp, 'model', array('CPC','CPM'));
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


<?php } ?>



    <?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id'=>'campaigns-form',
        'type'=>'horizontal',
        'htmlOptions'=>array('class'=>'well'),
        // to enable ajax validation
        'enableAjaxValidation'=>true,
        'clientOptions'=>array('validateOnSubmit'=>true, 'validateOnChange'=>true),
    )); ?>
    <fieldset>
        <h5 class="form-subtittle">Campaign</h5>

        <?php 

        $opportunities = array('test: 1','test: 2');
        $offer_type = array('VAS', 'App Owners', 'Branding', 'Lead Generation');
        $currency = array('Peso', 'Dolar', 'Euro', 'Real');
        $budget_type = array('Open','Fixed', 'Payment');
        $status = array('Active', 'Paused', 'Inactive');


        if($action == "Create"){
            echo $form->dropDownListRow($model, 'opportunities_id', $opportunities);
        }

        echo $form->textFieldRow($model, 'name', array('class'=>'span4'));
        echo $form->radioButtonListInlineRow($model, 'status', $status);
        echo '<hr/>';
        echo $form->dropDownListRow($model, 'campaign_categories_id', $categories, array('prompt' => 'Select a category'));
        echo $form->dropDownListRow($model, 'offer_type', $offer_type);
        echo $form->dropDownListRow($model, 'currency', $currency);
        echo '<hr/>';
        echo $form->radioButtonListInlineRow($model, 'budget_type', $budget_type);
        echo $form->textFieldRow($model, 'budget', array('prepend'=>'$'));
        echo $form->textFieldRow($model, 'cap', array('prepend'=>'$'));
        echo $form->radioButtonListInlineRow($model, 'model', array('CPC','CPM'));
        echo $form->textFieldRow($model, 'bid', array('prepend'=>'$'));
        echo '<hr/>';

        // DATE PICKERS >>
        ?>

        <div class="control-group ">
            <label class="control-label required" for="Campaigns_name">Start Date <span class="required">*</span>
            </label>
            <div class="controls">
            <?php
                $this->widget('ext.rezvan.RDatePicker',array(
                    'model'     => $model,
                    'attribute' => 'date_start',
                    'options'   => array(
                        'autoclose'  =>true,
                        'format'     => 'yyyy-mm-dd',
                        'viewformat' => 'yyyy-mm-dd',
                        'placement'  => 'right',
                    ),
                    'htmlOptions' =>array(
                        'class' =>'span2'
                    )
                ));
            ?>
            </div>
        </div>
        <div class="control-group ">
            <label class="control-label required" for="Campaigns_name">End Date <span class="required">*</span>
            </label>
            <div class="controls">
            <?php
                $this->widget('ext.rezvan.RDatePicker',array(
                    'model'     => $model,
                    'attribute' => 'date_end',
                    'options'   => array(
                        'autoclose'  =>true,
                        'format'     => 'yyyy-mm-dd',
                        'viewformat' => 'yyyy-mm-dd',
                        'placement'  => 'right',
                    ),
                    'htmlOptions' => array(
                        'class' =>'span2'
                    )
                ));
            ?>
            </div>
        </div>

        <?php
        echo '<hr/>';
        echo $form->textAreaRow($model, 'comment', array('class'=>'span4', 'rows'=>5)); 

        ?>
        <div class="row">
    </div>
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

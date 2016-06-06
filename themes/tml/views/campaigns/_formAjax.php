<?php
/* @var $this CampaignsController
 * @var $model Campaigns 
 * @var $form CActiveForm 
 */
?>


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

        $getProviders = $this->createUrl('campaigns/getProviders');
        $getProviderCurrency = $this->createUrl('campaigns/getProviderCurrency');
        $getDefaultExternalRate = $this->createUrl('campaigns/getDefaultExternalRate');

        if($action == 'Create' || $action == 'Duplicate'){
            echo $form->dropDownListRow($model, 'advertisers_name', $advertisers, 
              array(
                'prompt'   => 'Select an advertiser', 
                'onChange' => '
                    if ( ! this.value) {
                      return;
                    }
                    $.post(
                        "getOppByAdv/"+this.value,
                        "",
                        function(data)
                        {
                            // console.log(data);
                            $(".opportunities-dropdownlist").html(data);
                        }
                    )
                    '
                ));
            echo $form->dropDownListRow($model, 'opportunities_id', $opportunities, array(
                'prompt' => 'Select an opportunitiy',
                'class'  => 'opportunities-dropdownlist',
                ));
        } else {
            echo $form->hiddenField($model, 'opportunities_id', array('class' => 'opportunities-dropdownlist'));
        }

        echo $form->textFieldRow($model, 'name', array('class'=>'span3'));
        echo $form->checkboxRow($model, 'editable');
        echo '<hr/>';

        echo '<div class="control-group">';
        echo CHtml::label('Traffic Source Type <span class="required">*</span>', '', array('class' => 'control-label'));
        echo '<div class="controls">';
        echo CHtml::dropDownList('', $modelProv->getType(), $providers_type, array(
            'prompt'   => 'Select traffic source type',
            'class'    => 'provider-type-dropdown',
            'onChange' => '
                if ( ! this.value)
                    return;

                if (this.value == 1) // if is affiliate show external rate
                    $(".external-rate").show();
                else
                    $(".external-rate").hide();

                $.post(
                    "'.$getProviders.'/"+this.value,
                    "",
                    function(data)
                    {
                        // alert(data);
                      $(".providers-dropdownlist").html(data);
                    }
                  )
                '
        ));
        echo '</div>'; echo '</div>';
        echo $form->dropDownListRow($model, 'providers_id', $providers, array(
            'class'    =>'providers-dropdownlist', 
            'prompt'   => 'Select traffic source',
            'onChange' => '
                if ( $(".provider-type-dropdown").val() != 1 )
                    return;
                
                $.post(
                    "'.$getProviderCurrency.'/"+this.value,
                    "",
                    function(data)
                    {
                      // alert(data);
                      $(".prov-currency").html(data);
                    }
                )
                
                if ( ! $(".opportunities-dropdownlist").val() )
                    return;

                $.post(
                    "'.$getDefaultExternalRate.'/"+$(".opportunities-dropdownlist").val()+"?p_id="+this.value,
                    "",
                    function(data)
                    {
                        // alert(data);
                        $("#Campaigns_external_rate").val(data);
                    }
                )
            ',
        ));
        $display = 'display: none;';
        if ($modelProv->getType() == 1) { // is affiliate
            $display = 'display: block;';
        }
        echo '<div style="' . $display . '" class="external-rate">';
        echo $form->textFieldRow($model, 'external_rate', array('class' => 'span2'), array( 'prepend' => '<p class="prov-currency">' . $modelProv->currency . '</p>' ));
        echo '</div>';

        echo $form->dropDownListRow($model, 'campaign_categories_id', $categories, array('prompt' => 'Select a category'));
        echo $form->checkboxRow($model, 'wifi');
        echo $form->checkboxRow($model, 'ip');
        echo $form->dropDownListRow($model, 'formats_id', $formats, array('prompt' => 'Select a format'));
        echo $form->dropDownListRow($model, 'devices_id', $devices, array('prompt' => 'Select a format'));
        
        echo '<hr/>';
        
        echo $form->textFieldRow($model, 'cap', array('prepend'=>'$'));
        echo $form->radioButtonListRow($model, 'model', $campModel, array(
            'onChange' => '
                if (this.value == "CPM") // if is CPM show environment
                    $(".environment").show();
                else
                    $(".environment").hide();
                '
        ));

        $display = $model->model == 'CPM' ? 'display:block;' : 'display:none;';
        echo '<div style="' . $display . '" class="environment">';
        echo $form->radioButtonListRow($model, 'environment', $environment);
        echo '</div>';

        $cat = isset($model->opportunities->regions->financeEntities->advertisers->cat) ? $model->opportunities->regions->financeEntities->advertisers->cat : 'VAS';
        $display = $cat == 'VAS' ? 'display:block;' : 'display:none;';
        echo '<div style="' . $display . '" >';
        echo $form->radioButtonListRow($model, 'flow', $flow);
        echo '</div>';

        echo '<div>';
        echo $form->radioButtonListRow($model, 'inventory_type', $inventory_type);
        echo '</div>';

        echo '<hr/>';
        
        echo $form->textFieldRow($model, 'url', array('class'=>'span3'));
        ?>
        <div id='macros'>
            Outgoing Macros:
            <br>
        <?php
        foreach (ClicksLog::model()->macros() as $key => $value) {
            echo CHtml::label($key, $key, array('class'=>'label')).' ';
            Yii::app()->clientScript->registerScript('register_script_name', "
                $('#macros label').click(function(){
                   $('#Campaigns_url').val( $('#Campaigns_url').val() + $(this).text());
                });
            ", CClientScript::POS_READY);
        }?>
        </div>
        <div id='macros'>
            Query String Macros:
            <div style="font-size:13px">
            Add QS_paramname=&lt;publisher_macro&gt; in the redirect URL and 
        <?php echo CHtml::label('{QS_paramname}', '{QS_paramname}', array('class'=>'label')).' '; ?> 
            in some part of campaign's URL
            </div>
        </div>
        <?php
        echo "<hr>";
        if(!$model->isNewRecord)
            echo $form->radioButtonListRow($model, 'status', $campStatus);
        echo $form->textAreaRow($model, 'comment', array('class'=>'span3', 'rows'=>5));

        /*
        if( FilterManager::model()->isUserTotalAccess('campaign.post_data') ) {
            echo '<hr/>';
            echo $form->checkboxRow($model, 'post_data');
        }
        */
        ?>

    <?php //echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
    <div class="form-actions">
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'success', 'label'=>'Submit')); ?>
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'reset', 'type'=>'reset', 'label'=>'Reset')); ?>
    </div>
    </fieldset>

    <?php $this->endWidget(); ?>


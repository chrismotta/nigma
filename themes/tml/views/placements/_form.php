<?php
/* @var $this PlacementsController */
/* @var $model Placements */
/* @var $form CActiveForm */
/* @var $sizes sizes[] */
/* @var $exchanges exchanges[] */
/* @var $publishers publishers[] */
?>

<div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h4>Placement <?php echo $model->isNewRecord ? "" : "#". $model->id; ?></h4>
</div>


<div class="modal-body">

    <?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
		'id'                   =>'placements-form',
		'type'                 =>'horizontal',
		'htmlOptions'          =>array('class'=>'well'),
		// to enable ajax validation
		'enableAjaxValidation' =>true,
		'clientOptions'        =>array('validateOnSubmit'=>true, 'validateOnChange'=>true),
    )); ?>
    <fieldset>
	<?php 

    	if ( $model->isNewRecord ) {
            echo $form->dropDownListRow($model, 'publishers_name', $publishers, 
                array(
                    'prompt'   => 'Select a publisher',
                    'onChange' => '
                        if ( ! this.value) {
                            $(".sites-dropdownlist").html("<option value=\"\">Select a site</option>");
                            $(".sites-dropdownlist").prop( "disabled", true );
                            return;
                        }
                        $.post(
                            "getSites/"+this.value,
                            "",
                            function(data)
                            {
                                $(".sites-dropdownlist").html(data);
                                $(".sites-dropdownlist").prop("disabled", false);
                            }
                        )
                    '
                    ));
      		echo $form->dropDownListRow($model, 'sites_id', $sites, 
                array(
                    'prompt'   => 'Select a site',
                    'class'    => 'sites-dropdownlist',
                    'disabled' => true,
                    ));
      	}
      
        // echo $form->dropDownListRow($model, 'exchanges_id', $exchanges, array('prompt' => 'Select exchange'));
        echo $form->dropDownListRow($model, 'sizes_id', $sizes, array('prompt' => 'Select size'));
        
        echo $form->textFieldRow($model, 'name', array('class'=>'span3'));
        echo $form->textFieldRow($model, 'product', array('class'=>'span3'));
        echo $form->dropDownListRow($model, 'model', $model_pub, array('prompt' => 'Select a model'));
        echo $form->textFieldRow($model,'rate',array('class'=>'span2','maxlength'=>255), array('append' => '$'));
        echo $form->textFieldRow($model,'publisher_percentage',array('class'=>'span2','maxlength'=>255), array('prepend' => '%'));
        
    ?>

    <div class="form-actions">
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'success', 'label'=>'Submit')); ?>
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'reset', 'type'=>'reset', 'label'=>'Reset')); ?>
    </div>
    </fieldset>

    <?php $this->endWidget(); ?>
</div>

<div class="modal-footer">
    Edit Publisher attributes. Fields with <span class="required">*</span> are required.
</div>
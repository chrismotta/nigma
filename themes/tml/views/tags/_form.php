<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
    'id'=>'tags-form',
    'enableAjaxValidation'=>false,
)); ?>

<?php echo $form->errorSummary($model); ?>
    
    <?php echo $form->textFieldRow($model,'name',array('class'=>'span5')); ?>

    <?php 
    if(isset($model->campaigns_id)){
        echo $form->hiddenField($model,'campaigns_id'); 
    }else{
        echo $form->textFieldRow($model,'campaigns_id',array('class'=>'span5')); 
    }
    ?>

    <?php 
    // echo $form->textFieldRow($model,'banner_sizes_id',array('class'=>'span5')); 

        echo $form->dropDownListRow($model, 'banner_sizes_id', $bannerSizes, array('prompt' => 'Select a size')); ?>

    <?php echo $form->radioButtonListRow($model, 'type', array('Tag'=>'Tag','URL'=>'URL')); ?>

    <?php echo $form->textAreaRow(
        $model,
        'code',
        array('class' => 'span5', 'rows' => 5)
        ); ?>

    <div id='macros' style="margin: -8px 0 10px 0;">
    <small>MACROS: </small> 
    <?php
    foreach (ImpLog::model()->macros() as $key => $value) {
        echo CHtml::label($key, $key, array('class'=>'label')).' ';
        // Yii::app()->clientScript->registerScript('register_script_name', "
        //     $('#macros label').click(function(){
        //        $('#Tags_code').val( $('#Tags_code').val() + $(this).text());
        //     });
        // ", CClientScript::POS_READY);
    }?>
    </div>
        
    <?php echo $form->textAreaRow(
        $model,
        'comment',
        array('class' => 'span5', 'rows' => 3)
        ); ?>

    <?php echo $form->textFieldRow($model, 'freq_cap',array('style'=>'width:50px')).' /24'; ?>

    <?php 
    $countries = GeoLocation::getCountryNames();
    echo $form->dropDownListRow($model, 'country', $countries, array('prompt' => 'Select Country')); ?>

    <?php echo $form->dropDownListRow($model,'connection_type',array("WIFI"=>"WIFI","3G"=>"3G",),array('class'=>'input-large','prompt'=>'Select Connection')); ?>

    <?php echo $form->dropDownListRow($model,'device_type',array("Desktop"=>"Desktop","Mobile"=>"Mobile","Tablet"=>"Tablet","Mobile+Tablet"=>"Mobile+Tablet",),array('class'=>'input-large','prompt'=>'Select Device Type')); ?>

    <?php echo $form->dropDownListRow($model,'os',array("Android"=>"Android","iOS"=>"iOS","Windows"=>"Windows","BlackBerry"=>"BlackBerry",),array('class'=>'input-large','prompt'=>'Select OS')); ?>

    <?php echo $form->textFieldRow($model,'os_version',array('class'=>'span5','maxlength'=>45)); ?>

    <?php echo $form->checkBoxRow($model, 'analyze'); ?>

<div class="form-actions alert-info top20">
	<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'success',
			'label'=>$model->isNewRecord ? 'Create' : 'Save',
		)); ?> 
    <?php $this->widget('bootstrap.widgets.TbButton', array(
            'buttonType'=>'reset', 
            'type'=>'reset', 
            'label'=>'Reset'
        )); ?>
</div>

<?php $this->endWidget(); ?>

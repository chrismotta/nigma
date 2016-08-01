<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'landing-images-form',
	'enableAjaxValidation'=>false,
    'type'=>'inline',
	'htmlOptions' => array(
        'enctype' => 'multipart/form-data',
    	'class'=>'well',
    ),
)); ?>

<?php echo $form->errorSummary($model); ?>

<?php 
echo '<div class="col-md-12" style="margin-bottom:10px">';
echo '<div class="label-select">Image Type</div>';
echo $form->dropDownListRow($model,'type',array(
    "Background"=>"Background",
    "HeadLine"=>"HeadLine",
    "ByLine"=>"ByLine",
    "Gallery"=>"Gallery",
    ),array('class'=>'input-large')); 
echo '</div>';
?>

<?php
echo '<div class="col-md-12">';
echo '<div class="form-group">';

if($model->isNewRecord){
    echo CHtml::activeFileField(
    	$model, 
    	'LandingImages[image]'
    );  
    echo $form->error($model,'image'); 
}else{
	$src = $model->getImagePath($model->file_name);	
	echo '<img src="'.$src.'" />';
}

echo '</div>';
echo '</div>';
?>

<br/>

<?php $this->widget('bootstrap.widgets.TbButton', array(
	'buttonType'=>'submit',
	'type'=>'primary',
	'label'=>$model->isNewRecord ? 'Create' : 'Save',
)); ?>

<?php $this->endWidget(); ?>


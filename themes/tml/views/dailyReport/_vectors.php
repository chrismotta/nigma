<?php
/* @var $this DailyReportController */
/* @var $model DailyReport */
/* @var $form CActiveForm */
/* @var $dailyVectors */
?>

<div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h4>Daily Report #<?php echo $model->id ?> - Vector</h4>
</div>


<div class="modal-body">

    <?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id'=>'daily-report-form',
        'type'=>'inline',
        'htmlOptions'=>array('class'=>'well'),
        // to enable ajax validation
        'enableAjaxValidation'=>true,
        'clientOptions'=>array('validateOnSubmit'=>true, 'validateOnChange'=>true),
    )); ?>
    <fieldset>
        <?php 

        $i = 1;
        $data = array();
        foreach ($dailyVectors as $dv) {
            $data[] = array(
                "id"           => $form->hiddenField($dv, 'id', array('type'=>"hidden", 'name'=>'DailyVectors' . $i . '[id]') ),
                "campaigns_id" => CHtml::label($dv->campaigns_id, "", array()),
                "vectors_id"   => CHtml::label($dv->vectors_id, "", array()),
                "currency"     => CHtml::label($dv->campaigns->opportunities->ios->currency, "", array('name' => 'DailyVectors' . $i . '[currency]')),
                "daily_id"     => $form->hiddenField($dv, 'daily_report_id', array('type'=>"hidden", 'name'=>'DailyVectors' . $i . '[daily_report_id]') ),
                "rate"         => $form->textFieldRow($dv, 'rate', array('class'=>'input-small', 'name'=>'DailyVectors' . $i . '[rate]')),
                "conv"         => $form->textFieldRow($dv, 'conv', array('class'=>'input-small', 'name'=>'DailyVectors' . $i . '[conv]')),
            );
            $i++;
        }

        $this->widget('bootstrap.widgets.TbGridView', array(
            'type'         =>'striped condensed',
            'dataProvider' => new CArrayDataProvider($data),
            'template'     => "{items}",
            'columns'      =>array(
                array('name'=>'id', 'type' => 'raw', 'header'=>''),
                array('name'=>'campaigns_id', 'type' => 'raw', 'header'=>''),
                array('name'=>'vectors_id', 'type' => 'raw', 'header'=>'Carrier'),
                array('name'=>'currency', 'type' => 'raw', 'header'=>'Currency'),
                array('name'=>'daily_id', 'type' => 'raw', 'header'=>''),
                array('name'=>'rate', 'type' => 'raw', 'header'=>'Rate'),
                array('name'=>'conv', 'type' => 'raw', 'header'=>'Conv'),
            ),
        ));
        ?>

    <div class="form-actions">
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'success', 'label'=>'Submit', 'htmlOptions' => array('name' => 'vectors-submit'))); ?>
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'reset', 'type'=>'reset', 'label'=>'Reset')); ?>
    </div>
    </fieldset>

    <?php $this->endWidget(); ?>


</div>

<div class="modal-footer">
    Add vectors info. Fields with <span class="required">*</span> are required.
</div>
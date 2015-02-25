<?php
/* @var $this DailyReportController */
/* @var $model DailyReport */
/* @var $form CActiveForm */
/* @var $multi_rates */
/* @var $currency */
?>
<?php $queryString = json_encode($_POST) ?>
<div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h4>Daily Report #<?php echo $model->id ?> - Multi Rate</h4>
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
        foreach ($multi_rates as $multi_rate) {
            $data[] = array(
                "carrier_id" => $form->hiddenField($multi_rate, 'carriers_id_carrier', array('type'=>"hidden", 'name'=>'MultiRate' . $i . '[carriers_id_carrier]') ),
                "carrier"  => CHtml::label($multi_rate->carriersIdCarrier->mobile_brand, "", array()),
                "currency" => CHtml::label($currency, "", array('name' => 'MultiRate' . $i . '[currency]')),
                "id"       => $form->hiddenField($multi_rate, 'id', array('type'=>"hidden", 'name'=>'MultiRate' . $i . '[id]') ),
                "daily_id" => $form->hiddenField($multi_rate, 'daily_report_id', array('type'=>"hidden", 'name'=>'MultiRate' . $i . '[daily_report_id]') ),
                "rate"     => $form->textFieldRow($multi_rate, 'rate', array('class'=>'input-small', 'name'=>'MultiRate' . $i . '[rate]')),
                "conv"     => $form->textFieldRow($multi_rate, 'conv', array('class'=>'input-small', 'name'=>'MultiRate' . $i . '[conv]')),
            );
            $i++;
        }

        $this->widget('bootstrap.widgets.TbGridView', array(
            'type'         =>'striped condensed',
            'dataProvider' => new CArrayDataProvider($data),
            'template'     => "{items}",
            'columns'      =>array(
                array('name'=>'carrier_id', 'type' => 'raw', 'header'=>''),
                array('name'=>'carrier', 'type' => 'raw', 'header'=>'Carrier'),
                array('name'=>'currency', 'type' => 'raw', 'header'=>'Currency'),
                array('name'=>'id', 'type' => 'raw', 'header'=>''),
                array('name'=>'daily_id', 'type' => 'raw', 'header'=>''),
                array('name'=>'rate', 'type' => 'raw', 'header'=>'Rate'),
                array('name'=>'conv', 'type' => 'raw', 'header'=>'Conv'),
            ),
        ));
        ?>

    <div class="form-actions">
        <?php echo CHtml::hiddenField('query_string', $queryString) ?>
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'success', 'label'=>'Submit', 'htmlOptions' => array('name' => 'multiRate-submit'))); ?>
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'reset', 'type'=>'reset', 'label'=>'Reset')); ?>
    </div>
    </fieldset>

    <?php $this->endWidget(); ?>


</div>

<div class="modal-footer">
    Add multi rate info. Fields with <span class="required">*</span> are required.
</div>
<?php 
/* @var $this DailyReportController */
/* @var $form CActiveForm */
?>

<div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h4>Excel Report</h4>
</div>


<div class="modal-body">

    <?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id'=>'excel-report-daily-form',
        'type'=>'horizontal',
        'htmlOptions'=>array('class'=>'well'),
        // to enable ajax validation
        'enableAjaxValidation'=>true,
        'clientOptions'=>array('validateOnSubmit'=>true, 'validateOnChange'=>true),
    )); ?>
    <fieldset>

        <div class="control-group">
            <?php echo CHtml::label("From:", 'excel-dateStart', array('class'=>'control-label')); ?>

            <div class="controls">
                <?php $this->widget('ext.rezvan.RDatePicker',array(
                    'name'  => 'excel-dateStart',
                    'value' => date('d-m-Y', strtotime('yesterday')),
                    'htmlOptions' => array(
                        'style' => 'width: 80px',
                    ),
                    'options' => array(
                        'autoclose'      => true,
                        'format'         => 'dd-mm-yyyy',
                        'viewformat'     => 'dd-mm-yyyy',
                        'placement'      => 'right',
                    ),
                )); ?>
            </div>
        </div>
            
        <div class="control-group">
            <?php echo CHtml::label("To:", 'excel-endDate', array('class'=>'control-label')); ?>
            
            <div class="controls">
                <?php $this->widget('ext.rezvan.RDatePicker',array(
                    'name'  => 'excel-endDate',
                    'value' => date('d-m-Y', strtotime('yesterday')),
                    'htmlOptions' => array(
                        'style' => 'width: 80px',
                    ),
                    'options' => array(
                        'autoclose'      => true,
                        'format'         => 'dd-mm-yyyy',
                        'viewformat'     => 'dd-mm-yyyy',
                        'placement'      => 'right',
                    ),
                )); ?>
            </div>
        </div>

    <div class="form-actions">
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'success', 'label'=>'Download', 'htmlOptions' => array('name' => 'excel-report-daily'))); ?>
    </div>
    </fieldset>

    <?php $this->endWidget(); ?>


</div>

<div class="modal-footer">
    Excel Report Daily Report. Search by <span class="required">date</span>.
</div>
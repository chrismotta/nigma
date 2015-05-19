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
        'id'                   =>'excel-report-form',
        // 'type'                 =>'horizontal',
        'htmlOptions'          =>array('class'=>'well', 'style'=>'text-align:center'),
        // to enable ajax validation
        'enableAjaxValidation' =>false,
        // 'clientOptions'        =>array('validateOnSubmit'=>true, 'validateOnChange'=>true),
    )); ?>

    <fieldset>
       <label><div class="input-prepend input-append">
            <div class="controls">
                <span class="add-on" style="width:35px">From</span>
                <?php $this->widget('bootstrap.widgets.TbDatePicker',array(
                    'name'  => 'excel-dateStart',
                    'value' => date('d-m-Y', strtotime($dateStart)),
                    'htmlOptions' => array(
                        'style' => 'width: 80px',
                    ),
                    'options' => array(
                        'todayBtn'       => true,
                        'autoclose'      => true,
                        'todayHighlight' => true,
                        'format'         => 'dd-mm-yyyy',
                        'viewformat'     => 'dd-mm-yyyy',
                        'placement'      => 'right',
                ))); ?>
                <span class="add-on"><i class="icon-calendar"></i></span>
            </div>
        </div></label>
            
        <label><div class="input-append">
            <div class="controls">
                <span class="add-on" style="width:35px">To</span>
                <?php $this->widget('bootstrap.widgets.TbDatePicker',array(
                    'name'  => 'excel-dateEnd',
                    'value' => date('d-m-Y', strtotime($dateEnd)),
                    'htmlOptions' => array(
                        'style' => 'width: 80px',
                    ),
                    'options' => array(
                        'todayBtn'       => true,
                        'autoclose'      => true,
                        'todayHighlight' => true,
                        'format'         => 'dd-mm-yyyy',
                        'viewformat'     => 'dd-mm-yyyy',
                        'placement'      => 'right',
                ))); ?>
                <span class="add-on"><i class="icon-calendar"></i></span>
            </div>
        </div></label>
    
        <?php 
        echo CHtml::hiddenField('sum', '0', array('id'=>'sum'));

        $this->widget(
            'bootstrap.widgets.TbButtonGroup',
            array(
                'toggle' => 'radio',
                // 'type' => 'inverse',
                'buttons' => array(
                    array('label' => 'Daily Stats', 'active'=>boolval(1-$sum), 'htmlOptions'=>array('onclick'=>'$("#sum").val("0");')),
                    array('label' => 'Merged Stats', 'active'=>boolval(0-$sum), 'htmlOptions'=>array('onclick'=>'$("#sum").val("1");')),
                ),

            )
        ); ?>

    <div class="form-actions" style='margin-top:20px'>
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'success', 'label'=>'Download', 'htmlOptions' => array('name' => 'excel-report-form'))); ?>
    </div>

    </div>
    </fieldset>

<?php $this->endWidget(); ?>

</div>

<div class="modal-footer">
    
</div>
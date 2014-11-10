<?php 
/* @var $this SemController */
/* @var $form CActiveForm */
/* @var $report_type String */
?>

<div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h4>Excel Report - SEM <?php echo ucwords($report_type); ?></h4>
</div>


<div class="modal-body">

    <?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id'                   =>'excel-report-sem-form',
        'type'                 =>'horizontal',
        'htmlOptions'          =>array('class'=>'well'),
        // to enable ajax validation
        'enableAjaxValidation' =>true,
        'clientOptions'        =>array('validateOnSubmit'=>true, 'validateOnChange'=>true),
    )); ?>
    <fieldset>
        <?php echo CHtml::hiddenField('excel-report', $report_type, array()); ?>

        <label><div class="input-append">
            <?php echo CHtml::label("From:", 'excel-dateStart', array('class'=>'control-label')); ?>

            <div class="controls">
                <?php 
                $tmp       = new DateTime('today');
                $tmp       = $tmp->sub(new DateInterval('P1W'));
                $this->widget('bootstrap.widgets.TbDatePicker',array(
                    'name'  => 'excel-dateStart',
                    'value' => $tmp->format('d-m-Y'),
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
        <br/>
        </div></label>
            
        <label><div class="input-append">
            <?php echo CHtml::label("To:", 'excel-dateEnd', array('class'=>'control-label')); ?>
            
            <div class="controls">
                <?php $this->widget('bootstrap.widgets.TbDatePicker',array(
                    'name'  => 'excel-dateEnd',
                    'value' => date('d-m-Y', strtotime('yesterday')),
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
        <br/>
        </div></label>

        <div class="input-append">
            <?php echo CHtml::label("Campaign:", 'excel-campaign', array('class'=>'control-label')); ?>

            <div class="controls">
                <?php echo KHtml::filterCampaigns(NULL, array(4, 31), 'excel-campaign', array('class' => 'span3')); ?>
            </div>
        <br/>
        </div>

    <div class="form-actions">
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'success', 'label'=>'Download', 'htmlOptions' => array('name' => 'excel-report-sem'))); ?>
    </div>
    </fieldset>

    <?php $this->endWidget(); ?>


</div>

<div class="modal-footer">
    Excel Report SEM. Search by <span class="required">date</span> and <span class="required">campaign</span>.
</div>
<?php 
/* @var $this DailyReportController */
/* @var $form CActiveForm */
$dateStart = isset($_GET['dateStart']) ? $_GET['dateStart'] : 'today';
$dateEnd = isset($_GET['dateEnd']) ? $_GET['dateEnd'] : 'today';
$cid = isset($_GET['id']) ? $_GET['id'] : null;
?>

<div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h4>Excel Report</h4>
</div>


<div class="modal-body">

    <?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id'=>'excel-traffic-form',
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
                    'value' => date('d-m-Y', strtotime($dateStart)),
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
            <input type="hidden" value="<?php echo $cid ?>" id="id" name="id">
            <?php echo CHtml::label("To:", 'excel-dateEnd', array('class'=>'control-label')); ?>
            
            <div class="controls">
                <?php $this->widget('ext.rezvan.RDatePicker',array(
                    'name'  => 'excel-dateEnd',
                    'value' => date('d-m-Y', strtotime($dateEnd)),
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
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'success', 'label'=>'Download', 'htmlOptions' => array('name' => 'excel-traffic'))); ?>
    </div>
    </fieldset>

    <?php $this->endWidget(); ?>


</div>

<div class="modal-footer">
    Excel Traffic Report. Search by <span class="required">date</span>.
</div>
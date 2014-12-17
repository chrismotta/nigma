<?php 
/* @var $this DailyReportController */
/* @var $form CActiveForm */
Yii::app()->clientscript->scriptMap['*.js'] = false;
$dateStart      = isset($_GET['dateStart']) ? $_GET['dateStart'] : 'yesterday' ;
$dateEnd        = isset($_GET['dateEnd']) ? $_GET['dateEnd'] : 'yesterday';
$accountManager = isset($_GET['accountManager']) ? $_GET['accountManager'] : NULL;
$opportunitie   = isset($_GET['opportunitie']) ? $_GET['opportunitie'] : NULL;
$opportunities  = isset($_GET['opportunities']) ? $_GET['opportunities'] : NULL;
$providers      = isset($_GET['providers']) ? $_GET['providers'] : NULL;
$adv_categories = isset($_GET['advertisers-cat']) ? $_GET['advertisers-cat'] : NULL;
$sum            = isset($_GET['sum']) ? $_GET['sum'] : 0;
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
        <label><div class="input-append">
            <?php echo CHtml::label("From:", 'excel-dateStart', array('class'=>'control-label')); ?>

            <div class="controls">
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
        <br/>
        </div></label>
            
        <label><div class="input-append">
            <?php echo CHtml::label("To:", 'excel-dateEnd', array('class'=>'control-label')); ?>
            
            <div class="controls">
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
        <br/>
        </div></label>
        <?php
       if (FilterManager::model()->isUserTotalAccess('daily'))
            KHtml::filterAccountManagersMulti($accountManager,array('style' => "width: 40%; margin-left: 1em",'id' => 'excel-accountManager-select'),'excel-opportunities-select','excel-accountManager');
        KHtml::filterOpportunitiesMulti($opportunities, $accountManager, array('style' => "width: 40%; margin-left: 1em",'id' => 'excel-opportunities-select'),'excel-opportunities');
        KHtml::filterProvidersMulti($providers, NULL, array('style' => "width: 40%; margin-left: 1em",'id' => 'excel-providers-select'),'excel-providers');
        KHtml::filterAdvertisersCategoryMulti($adv_categories, array('style' => "width: 40%; margin-left: 1em",'id' => 'excel-advertisers-cat-select'),'excel-advertisers-cat');
        ?>
        <div class="input-append">
            <?php echo CHtml::label("SUM:", 'excel-dateEnd', array('class'=>'control-label')); ?>
            <div class="controls">
                <?php echo CHtml::checkBox('sum', $sum, array('style'=>'vertical-align:bottom;')); ?>
           </div>
        <br/>
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
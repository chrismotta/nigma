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
        'id'=>'excel-clients-form',
        'type'=>'horizontal',
        'htmlOptions'=>array('class'=>'well'),
        // to enable ajax validation
        'enableAjaxValidation'=>true,
        'clientOptions'=>array('validateOnSubmit'=>true, 'validateOnChange'=>true),
    )); ?>

    <fieldset>
        <?php
            $months[0]  ='Select a month';
            $months[1]  ='January';
            $months[2]  ='February';
            $months[3]  ='March';
            $months[4]  ='April';
            $months[5]  ='May';
            $months[6]  ='June';
            $months[7]  ='July';
            $months[8]  ='August';
            $months[9]  ='September';
            $months[10] ='October';
            $months[11] ='November';
            $months[12] ='December';
            $years[0]   ='Select a year';
            foreach (range(date('Y'), 2014) as $year) {
                $years[$year]=$year;
            }
            $criteria=new CDbCriteria;
            $criteria->select='entity';
            $criteria->group='entity';
            $criteria->addCondition('entity!=""');
            $io=new Ios;
            $entity=$io->findAll($criteria);
            $entities[0]='All entities';
            foreach ($entity as $value) {
                $entities[$value->entity]=$value->entity;
            }
        echo $form->dropDownList(new DailyReport,'date',$months,array('name'=>'month', 'options' => array(isset($_GET['month']) ? $_GET['month'] : 0=>array('selected'=>true))));
        echo $form->dropDownList(new DailyReport,'date',$years,array('name'=>'year','options' => array(isset($_GET['year']) ? $_GET['year'] : 0=>array('selected'=>true))));
        echo $form->dropDownList(new Ios,'entity',$entities,array('name'=>'entity','options' => array(isset($_GET['entity']) ? $_GET['entity'] : 0=>array('selected'=>true))));
        //echo CHtml::dropDownList($years,'year',$years);
                    ?>
    
    <div class="form-actions">
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'success', 'label'=>'Download', 'htmlOptions' => array('name' => 'excel-clients-form'))); ?>
    </div>

    </div>
    </fieldset>

<?php $this->endWidget(); ?>

</div>

<div class="modal-footer">
    Excel Report Daily Report. Search by <span class="required">date</span>.
</div>
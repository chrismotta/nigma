<?php
/* @var $this DailyReportController */
/* @var $model DailyReport */
/* @var $form CActiveForm */
/* @var $multi_rates */
/* @var $currency */
?>

<div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h4>Io #<?php echo $id ?> - Multi Rate</h4>
</div>


<div class="modal-body">

    <?php 
        $this->widget('bootstrap.widgets.TbGridView', array(
            'type'         =>'striped condensed',
            'dataProvider' => $dataProvider,
            'template'     => "{items}",
            'columns'      =>array(
                array('name'=>'rate', 'type' => 'raw', 'header'=>'Rate'),
                array('name'=>'conv', 'type' => 'raw', 'header'=>'Conv.'),
                array('name'=>'revenue', 'type' => 'raw', 'header'=>'Revenue'),
            ),
        ));
        ?>



</div>

<div class="modal-footer">
</div>
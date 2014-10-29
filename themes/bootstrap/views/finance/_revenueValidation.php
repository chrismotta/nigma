<?php 
/* @var $this DailyReportController */
/* @var $form CActiveForm */
 ?>
<div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h4><?php echo $io->commercial_name; ?></h4>
</div>

<div class="modal-body">
<h5>Commercial Contact: <?php echo $io->contact_com; ?></h5>
<h5>Administrative Contact: <?php echo $io->contact_adm; ?></h5>
<?php 
    $this->widget('yiibooster.widgets.TbGroupGridView', array(
    'id'                         => 'revenue-validation-grid',
    //'fixedHeader'              => true,
    //'headerOffset'             => 50,
    'dataProvider'               => $dataProvider,
    //'filter'                     => $filtersForm,
    //'filter'                   => $model,
    'type'                       => 'striped condensed',    
    //'rowHtmlOptionsExpression'   => 'array("data-row-id" => "1")',
    //'rowHtmlOptionsExpression' => 'array("data-row-id" => $data->id, "data-row-net-id" => $data->networks_id, "data-row-c-id" => $data->campaigns_id)',
    'template'                   => '{items} {pager} {summary}',
    'columns'                    => array(
        // array(
        //     'name'              =>  'id',
        //     'value'             =>'$data["id"]',    
        //     'headerHtmlOptions' => array('width' => '60'),
        //     'header'            =>'ID',                           
        //     ),     
        // array(
        //     'name'                =>'id',
        //     'value'               =>'$data["name"]',
        //     'htmlOptions'       => array('id'=>'alignLeft'),        
        //     'header'              =>'Commercial Name',
        //     //'footer'              =>'Totals:',      
        //     ),  
        array(
            'name'              =>'model',
            'value'             =>'$data["model"]', 
            'headerHtmlOptions' => array('width' => '80'),
            'header'            =>'Model',      
            ),
        array(
            'name'              =>'entity',
            'value'             =>'$data["entity"]',
            'headerHtmlOptions' => array('width' => '80'),  
            'header'            =>'Entity',    
            ),  
        array(
            'name'              =>'currency',
            'value'             =>'$data["currency"]',
            'headerHtmlOptions' => array('width' => '80'),      
            'header'            =>'Currency',   
            ),
        array(
            'name'              =>'rate',
            'value'             =>'$data["rate"] ? $data["rate"] : "Multi"',
            'headerHtmlOptions' => array('width' => '80'),  
            'htmlOptions'       => array('style'=>'text-align:right;'), 
            //'footer'          => $totals['rate'],
            'header'            =>'Rate',   
        ),  
        // array(
        //     'name'              => 'mr',
        //     'header'            => '',
        //     'filter'            => '',
        //     'headerHtmlOptions' => array('class'=>'plusMR'),
        //     'filterHtmlOptions' => array('class'=>'plusMR'),
        //     'htmlOptions'       => array('class'=>'plusMR'),
        //     'type'              => 'raw',
        //     'value'             =>  '
        //         $data["rate"] === NULL && !isset($data["carrier"]) ?
        //             CHtml::link(
        //                     "<i class=\"icon-plus\"></i>",
        //                     "javascript:;",
        //                     array(
        //                         "onClick" => CHtml::ajax( array(
        //                             "type"    => "POST",
        //                             "url"     => "multiRate?id=" . $data["id"] ."&month='.$month.'&year='.$year.'" ,
        //                             "success" => "function( data )
        //                                 {
        //                                     $(\"#modalClients\").html(data);
        //                                     $(\"#modalClients\").modal(\"toggle\");
        //                                 }",
        //                             )),
        //                         "style"               => "width: 20px",
        //                         "rel"                 => "tooltip",
        //                         "data-original-title" => "Update"
        //                         )
        //                 ) 
        //         : null
        //         '
        //         ,
        // ),
        array(
            'name'              =>'conv',
            'header'            =>'Clics/Imp/Conv',
            'value'             =>'$data["conv"]',  
            'headerHtmlOptions' => array('width' => '80'),  
            'htmlOptions'       => array('style'=>'text-align:right;'), 
            //'footer'          => $totals['conv'],
        ),
        array(
            'name'              =>'revenue',
            'header'            =>'Revenue',
            'value'             =>'$data["revenue"]',
            'headerHtmlOptions' => array('width' => '80'),
            'htmlOptions'       => array('style'=>'text-align:right;'),     
            //'footer'          => $totals['revenue'],
        ),
    ),
    //'mergeColumns' => array('id','name'),
)); ?>
<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id'=>'revenue-validation-form',
        'type'=>'horizontal',
        'htmlOptions'=>array('class'=>'well'),
        // to enable ajax validation
        'enableAjaxValidation'=>true,
        'clientOptions'=>array('validateOnSubmit'=>true, 'validateOnChange'=>true),
    )); ?>
    
    <?php
    echo $form->hiddenField(new RevenueValidation,'ios_id',array('name'=>'ios_id', 'value' => $io->id,));
    echo $form->hiddenField(new RevenueValidation,'period',array('name'=>'period', 'value' => date('Y-m-d', strtotime($year.'-'.$month.'-01')),));
    ?>

    <div class="form-actions">
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'success', 'label'=>'Send Mail', 'htmlOptions' => array('name' => 'revenue-validation-form'))); ?>
    </div>
<?php $this->endWidget(); ?>
</div>

<div class="modal-footer">
    
</div>
<?php 
/* @var $this FinanceController */
/* @var $form CActiveForm */
 ?>
<div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h4>opportunitie #<?php echo $opportunitie->id; ?></h4>
</div>

<div class="modal-body">

    <div class="row">
        <h5>Carrier: <?php //echo $opportunitie->carriers->mobile_brand ? $opportunitie->carriers->mobile_brand  : ""; ?></h5>
        <h5>Country: <?php echo $opportunitie->country->name; ?></h5>
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
                array(
                    'name'              => 'mr',
                    'header'            => '',
                    'filter'            => '',
                    'headerHtmlOptions' => array('class'=>'plusMR'),
                    'filterHtmlOptions' => array('class'=>'plusMR'),
                    'htmlOptions'       => array('class'=>'plusMR'),
                    'type'              => 'raw',
                    'value'             =>  '
                        $data["rate"] === NULL && !isset($data["carrier"]) ?
                            CHtml::link(
                                    "<i class=\"icon-plus\"></i>",
                                    "javascript:;",
                                    array(
                                        "onClick" => CHtml::ajax( array(
                                            "type"    => "POST",
                                            "url"     => "multiRate?id=" . $data["id"] ."&month='.$month.'&year='.$year.'" ,
                                            "success" => "function( data )
                                                {
                                                    $(\"#modalClients\").html(data);
                                                    $(\"#modalClients\").modal(\"toggle\");
                                                }",
                                            )),
                                        "style"               => "width: 20px",
                                        "rel"                 => "tooltip",
                                        "data-original-title" => "Update"
                                        )
                                ) 
                        : null
                        '
                        ,
                ),
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

            <div class="form-actions">
                <div class="offset2">
                    <?php 
                        $period=date('Y-m-d', strtotime($year.'-'.$month.'-01'));

                         echo CHtml::htmlButton('Approved',array('id'=>'btnAp','class'=>'btn btn-success'));
                         Yii::app()->clientScript->registerScript('register_script_name', "
                            $('#btnAp').click(function(e){
                                e.preventDefault();
                               $.post( 'validateOpportunitie', { 'opportunities_id': ".$op.",'period':'".$period."' })
                                    .success(function( data ) {
                                    alert(data );
                                    window.location = document.URL;
                                    });
                                
                            });
                        ", CClientScript::POS_READY);

                     ?>
                </div>
            </div>
        </div>

</div>

<div class="modal-footer">
    
</div>
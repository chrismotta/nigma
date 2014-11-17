<?php 
/* @var $this FinanceController */
/* @var $form CActiveForm */
 ?>
<div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h4>Opportunitie #<?php echo $opportunitie->id; ?></h4>
</div>

<div class="modal-body">

    <div class="row">
        <h5>Carrier: <?php //echo $opportunitie->carriers->mobile_brand ? $opportunitie->carriers->mobile_brand  : ""; ?></h5>
        <h5>Country: <?php echo $opportunitie->country->name; ?></h5>
        <?php 
            $this->widget('yiibooster.widgets.TbExtendedGridView', array(
            'id'                         => 'revenue-validation-grid',
            'dataProvider'               => $dataProvider,
            'type'                       => 'striped bordered',    
            'template'                   => '{items} {pager} {summary}',
            'columns'                    => array(
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
                    'header'            =>'Rate',   
                ),  
                array(
                    'name'              =>'conv',
                    'header'            =>'Clics/Imp/Conv',
                    'value'             =>'$data["conv"]',  
                    'headerHtmlOptions' => array('width' => '80'),  
                    'htmlOptions'       => array('style'=>'text-align:right;'), 
                ),
                array(
                    'name'              =>'revenue',
                    'header'            =>'Revenue',
                    'value'             =>'$data["revenue"]',
                    'headerHtmlOptions' => array('width' => '80'),
                    'htmlOptions'       => array('style'=>'text-align:right;'),     
                ),
            ),
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
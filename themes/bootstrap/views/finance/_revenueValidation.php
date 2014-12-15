<?php 
/* @var $this FinanceController */
/* @var $form CActiveForm */
// $clients=array_merge(Ios::model()->getClientsMulti(11,2014,null,null,null,null,null,null,false),Ios::model()->getClientsMulti(11,2014,null,null,null,null,null,null,true));
// echo json_encode(Ios::model()->getClientsProfile2(11,2014,null,29,null,null,null,null,'profile')['data']);
// return;
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
            'dataProvider'               => $dataProvider,
            'type'                       => 'striped condensed',    
            'template'                   => '{items} {pager}',
            'columns'                    => array(
                array(
                    'name'              =>'country',
                    'value'             =>'$data["country"]', 
                    'headerHtmlOptions' => array('width' => '80'),
                    'header'            =>'Country',      
                    'footer'            =>'Totals:',      
                    ),
                array(
                    'name'              =>'product',
                    'value'             =>'$data["product"]', 
                    'headerHtmlOptions' => array('width' => '80'),
                    'header'            =>'Product',      
                    ),
                array(
                    'name'              =>'mobileBrand',
                    'value'             =>'$data["mobileBrand"]', 
                    'headerHtmlOptions' => array('width' => '80'),
                    'header'            =>'Carrier',      
                    ),
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
                    'value'             =>'number_format($data["conv"])',  
                    'headerHtmlOptions' => array('width' => '80'),  
                    'htmlOptions'       => array('style'=>'text-align:right;'),
                    'footerHtmlOptions' => array('style'=>'text-align:right;'),   
                    'footer'            => number_format($totals['conv']),
                ),
                array(
                    'name'              =>'revenue',
                    'header'            =>'Revenue',
                    'value'             =>'number_format($data["revenue"],2)',
                    'headerHtmlOptions' => array('width' => '80'),
                    'htmlOptions'       => array('style'=>'text-align:right;'),   
                    'footerHtmlOptions' => array('style'=>'text-align:right;'),    
                    'footer'            => number_format($totals['revenue'],2),
                ),
            ),
        )); ?>
        <div class="form-actions">
            <div class="offset2">
                <?php
                $period=date('Y-m-d', strtotime($year.'-'.$month.'-01'));


                $revenueValidation= new IosValidation;
                if($revenueValidation->checkValidationOpportunities($io->id,$period))
                    echo CHtml::htmlButton('Send Mail',array('id'=>'btnRev','class'=>'btn btn-success'));
                else 
                    echo "Opportunities not been validated yet";

                Yii::app()->clientScript->registerScript('register_script_name', "
                    $('#btnRev').click(function(e){
                        e.preventDefault();
                       $.post( 'sendMail', { 'io_id': ".$io->id.", 'period': '".$period."' })
                            .success(function( data ) {
                            alert(data );
                            });
                        
                    });
                ", CClientScript::POS_READY);

                 ?>
                 <br>
            </div>
        </div>
</div>

<div class="modal-footer">
    
</div>
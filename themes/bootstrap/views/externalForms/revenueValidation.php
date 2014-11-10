<?php
/* @var $this ExternalFormsController */
/* @var $model RevenueValidation */
if(isset($error))
{
    echo "<script>alert('".$error."')</script>";
    return;    
}
$io_id =$model->ios_id;
$month =date('m', strtotime($model->period));
$year  =date('Y', strtotime($model->period));
$ios=new Ios;
$io=$ios->findByPk($io_id);
$clients =$ios->getClients($month,$year,null,$io_id);
switch ($model->status) {
    case 'Approved':
        echo "<script>alert('Revenue already Approved')</script>";
        break;
    
    case 'Disputed':
        echo "<script>alert('Revenue already Disputed')</script>";
        break;
    
    case 'Expired':
        echo "<script>alert('Revenue already Expired')</script>";
        break;
    
    default:
        $log=new ValidationLog;
        $status='Viewed';
        $model->attributes=array('status'=>$status);
        if($model->save())            
            $log->loadLog($model->id,$status);
        else
            echo 'Error';
        break;
}
?>

<div class="row">
    <div class="span12">
        <h4><?php echo $io->commercial_name; ?></h4>

        <h5>Commercial Contact: <?php echo $io->contact_com; ?></h5>
        <h5>Administrative Contact: <?php echo $io->contact_adm; ?></h5>
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean eleifend magna libero, suscipit vehicula ex laoreet eleifend. Morbi gravida ipsum augue, quis bibendum odio viverra eu. Ut efficitur sem at lacus interdum euismod. Vivamus et lacinia ante. </p>
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
                    'headerHtmlOptions' => array('width' => '80', 'style'=>'text-align:right;'),  
                    'htmlOptions'       => array('style'=>'text-align:right;'), 
                    //'footer'          => $totals['rate'],
                    'header'            =>'Rate',   
                ),  
                array(
                    'name'              =>'conv',
                    'header'            =>'Clics/Imp/Conv',
                    'value'             =>'$data["conv"]',  
                    'headerHtmlOptions' => array('width' => '80','style'=>'text-align:right;'),
                    'footerHtmlOptions' => array('style'=>'text-align:right;'),  
                    'htmlOptions'       => array('style'=>'text-align:right;'), 
                    'footer'          => $totals['conv'],
                ),
                array(
                    'name'              =>'revenue',
                    'header'            =>'Revenue',
                    'value'             =>'$data["revenue"]',
                    'headerHtmlOptions' => array('width' => '80', 'style'=>'text-align:right;'),
                    'footerHtmlOptions' => array('style'=>'text-align:right;'),
                    'htmlOptions'       => array('style'=>'text-align:right;'),     
                    'footer'            => $totals['revenue'],
                ),
            ),
        ));
        ?>
    </div>
    <div class="span12">
        <?php
        echo CHtml::textArea('comment','',array('id'=>'comment','name'=>'comment','placeholder'=>'Comments...','style'=>'width:40%'));
        ?>
            <?php
            echo CHtml::htmlButton('Approved',array('id'=>'btnApproved','class'=>'btn btn-success'));
                 Yii::app()->clientScript->registerScript('revenueApproved', "
                    $('#btnApproved').click(function(e){
                        e.preventDefault();
                       $.post( 'revenueApproved', { 'token': '".$model->validation_token."', 'comment': $('#comment').val()})
                            .success(function( data ) {
                            alert(data );
                            });
                        
                    });
                ", CClientScript::POS_READY);
            ?>
            <?php
            echo CHtml::htmlButton('Disputed',array('id'=>'btnDisputed','class'=>'btn btn-success'));
                 Yii::app()->clientScript->registerScript('revenueDisputed', "
                    $('#btnDisputed').click(function(e){
                        e.preventDefault();
                        if($('#comment').val())
                        {
                            $.post('revenueDisputed', { 'token': '".$model->validation_token."', 'comment': $('#comment').val()})
                            .success(function( data ) {
                            alert(data );
                            });
                        }
                        else
                        {
                            $('#comment').css({'border-color': 'rgba(236, 82, 82, 0.8)','outline':'0px none','box-shadow':'0px 1px 1px rgba(0, 0, 0, 0.075) inset, 0px 0px 8px rgba(236, 82, 82, 0.6)'});
                            
                        }
                    });
                ", CClientScript::POS_READY);
            ?>
<hr>

        <p>Si no eres el responsable de esta información brindanos el e-mail del responsable</p>
        	<?php
            echo CHtml::textField('email_validation','',array('id'=>'email_validation','name'=>'email_validation','style'=>'width:40%'));
            ?>
                <?php
            	echo CHtml::htmlButton('Submit',array('id'=>'btnSubmit','class'=>'btn btn-success'));
                         Yii::app()->clientScript->registerScript('changeEmail', "
                            $('#btnSubmit').click(function(e){
                                e.preventDefault();
                                if($('#email_validation').val())
                                {
                                    $.post( 'changeEmail', { 'token': '".$model->validation_token."', 'email_validation': $('#email_validation').val()})
                                    .success(function( data ) {
                                    alert(data );
                                    });
                                }
                                else
                                {
                                    $('#email_validation').css({'border-color': 'rgba(236, 82, 82, 0.8)','outline':'0px none','box-shadow':'0px 1px 1px rgba(0, 0, 0, 0.075) inset, 0px 0px 8px rgba(236, 82, 82, 0.6)'});
                                    
                                }  
                            });
                        ", CClientScript::POS_READY);
                ?>
    </div>
</div>
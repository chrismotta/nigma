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
$clients =$ios->getClients($month,$year,null,$io_id,null,null,null,null,null);
switch ($model->status) {
    case 'Approved':
        // echo "<script>alert('Revenue already Approved')</script>";
        die ('Revenue already Approved');
        break;
    
    case 'Disputed':
        // echo "<script>alert('Revenue already Disputed')</script>";
        die ('Revenue is Disputed');
        break;
    
    case 'Expired':
        // echo "<script>alert('Revenue already Expired')</script>";
        die ('Revenue is Expired');
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

        <h5>Commercial Contact: <?php echo $io->contact_com; ?><br/>
        Administrative Contact: <?php echo $io->contact_adm; ?></h5>
        <p style="font-size:13px">Spending in <?php echo date('M j, Y', strtotime($model->period)) ?><br/>
            <span style="color:#999">Consumos correspondientes a <?php echo date('M j, Y', strtotime($model->period)) ?></span></p>
        <p>Please check the statement of your account below. We will assume that you are in agreement with us on the statement unless you inform us to the contrary by latest <?php echo date('M j, Y', strtotime('+4 days')) ?><br/>
        <span style="color:#999">Por favor verificar el estado de su cuenta a continuación. Se considerara de acuerdo con el estado actual a menos que se nos notifique a mas tardar el <?php echo date('M j, Y', strtotime('+4 days')) ?></span></p>
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
                    'headerHtmlOptions' => array('width' => '80', 'style'=>'text-align:right;'),  
                    'htmlOptions'       => array('style'=>'text-align:right;'), 
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
                       $.post( '".Yii::app()->baseUrl."/externalForms/revenueApproved', { 'token': '".$model->validation_token."', 'comment': $('#comment').val()})
                            .success(function( data ) {
                            // alert(data );
                                $('#content').html('<div style=\'text-align:center\'>'+data+'</div>');
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
                            $.post('".Yii::app()->baseUrl."/externalForms/revenueDisputed', { 'token': '".$model->validation_token."', 'comment': $('#comment').val()})
                            .success(function( data ) {
                            // alert(data );
                                $('#content').html('<div style=\'text-align:center\'>'+data+'</div>');
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

        <p style="font-size:13px"><strong>IMPORTANT:</strong> If you weren’t the right contact person to verify the invoice, please enter the correct email address in the box below<br/>
            <span style="color:#999"><strong>IMPORTANTE:</strong> Si usted no fuese la persona indicada para hacer esta verificación, por favor ingrese el email correspondiente en el siguiente campo</span></p>
        <p style="margin-bottom:40px">	<?php
            echo CHtml::textField('email_validation','',array('id'=>'email_validation','name'=>'email_validation','style'=>'width:40%'));
            ?>
                <?php
            	echo CHtml::htmlButton('Submit',array('id'=>'btnSubmit','class'=>'btn btn-success'));
                         Yii::app()->clientScript->registerScript('changeEmail', "
                            $('#btnSubmit').click(function(e){
                                e.preventDefault();
                                if($('#email_validation').val())
                                {
                                    $.post( '".Yii::app()->baseUrl."/externalForms/changeEmail', { 'token': '".$model->validation_token."', 'email_validation': $('#email_validation').val()})
                                    .success(function( data ) {
                                    // alert(data );                                        
                                        $('#content').html('<div style=\'text-align:center\'>'+data+'</div>');
                                    });
                                }
                                else
                                {
                                    $('#email_validation').css({'border-color': 'rgba(236, 82, 82, 0.8)','outline':'0px none','box-shadow':'0px 1px 1px rgba(0, 0, 0, 0.075) inset, 0px 0px 8px rgba(236, 82, 82, 0.6)'});
                                    
                                }  
                            });
                        ", CClientScript::POS_READY);
                ?>
        </p>
    </div>
</div>
<?php
function weekDaysSum($startDay, $cantDays) {
    for($i=1; $i<=$cantDays; $i++) {
        $weekDay = date('D', strtotime($startDay . " +" . $i));
        if( $weekDay == "Sat" || $weekDay == "Sun"){
            $cantDays++;
        }
    }
    return date('Y-m-d', strtotime($startDay . " +" . $cantDays) );
}

?>
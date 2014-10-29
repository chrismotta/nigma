<?php 
/* @var $this FinanceController */
/* @var $model Finance */

$date    =date('Y-m-d H:i:s', strtotime('NOW'));
$status  ="Sended";
$comment =null;

$validation_token=md5($date.$io_id);
$revenueValidation= new RevenueValidation;
$revenueValidation->attributes=array('ios_id'=>$io_id,'period'=>$period,'date'=>$date, 'status'=>$status, 'comment'=>$comment,'validation_token'=>$validation_token);
if($revenueValidation->save())
    echo '<script>alert("Oportunidad aprobada")</script>';
else 
    echo '<script>alert("Error al guardar")</script>';

?>
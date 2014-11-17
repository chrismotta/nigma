<?php 
/* @var $this ExternalFormsController */
/* @var $model ExternalForm */
$status ="Email Changed";
$log    =new ValidationLog;
$ios->attributes=array('email_validation'=>$email,'zip_code'=>'0');
if($ios->save())
{
	echo $status;
	$log->loadLog($model->id,$status);
}
else
	print_r($ios->getErrors());
?>
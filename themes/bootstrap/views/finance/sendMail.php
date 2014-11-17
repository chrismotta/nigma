<?php 
/* @var $this FinanceController */
/* @var $model Finance */
$date    =date('Y-m-d H:i:s', strtotime('NOW'));
$status  ="Sended";
$comment =null;

$validation_token=md5($date.$io_id);
$revenueValidation= new IosValidation;
$log=new ValidationLog;
if($revenueValidation->checkValidationOpportunities($io_id,$period))
{
	if(!$revenueValidation->checkValidation($io_id,$period))
	{
		$revenueValidation->attributes=array('ios_id'=>$io_id,'period'=>$period,'date'=>$date, 'status'=>$status, 'comment'=>$comment,'validation_token'=>$validation_token);
		if($revenueValidation->save())
		{
		    echo 'Io aprobada'.$revenueValidation->id;
			$log->loadLog($revenueValidation->id,$status);
		}
		else 
		    echo 'Error al guardar';
	}
	else
		    echo 'Email enviado anteriormente';		
}
else
	echo 'Las opperaciones aun no han sido validadas';
?>
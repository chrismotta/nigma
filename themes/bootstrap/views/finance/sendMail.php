<?php 
/* @var $this FinanceController */
/* @var $model Finance */
$date    =date('Y-m-d H:i:s', strtotime('NOW'));
$status  ="Sent";
$comment =null;

$validation_token=md5($date.$io_id);
$revenueValidation= new IosValidation;
$log=new ValidationLog;
// if($revenueValidation->checkValidationOpportunities($io_id,$period))
// {
	if($revenueValidation->checkValidation($io_id,$period))
	{
		$ioValidation=$revenueValidation->loadByIo($io_id,$period);
		$ioValidation->attributes=array('status'=>$status);
		// $revenueValidation->attributes=array('ios_id'=>$io_id,'period'=>$period,'date'=>$date, 'status'=>$status, 'comment'=>$comment,'validation_token'=>$validation_token);
		if($ioValidation->save())
		{
			//ENVIAR MAIL AQUI
		    echo 'Io #'.$ioValidation->ios_id.' mail enviado';
			$log->loadLog($ioValidation->id,$status);
		}
		else 
		    print_r($ioValidation->getErrors());
	}
	else
		    echo 'Las operaciones aun no han sido validadas';		
// }
// else
// 	echo 'Las opperaciones aun no han sido validadas';
?>
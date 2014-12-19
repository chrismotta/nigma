<?php 
/* @var $this FinanceController */
/* @var $model Finance */
$date    =date('Y-m-d H:i:s', strtotime('NOW'));
$status  ="Invoiced";

$revenueValidation= new IosValidation;
$log=new ValidationLog;
// if($revenueValidation->checkValidationOpportunities($io_id,$period))
// {
	$status=$revenueValidation->loadByIo($io_id,$period)->status;
	if($status=='Approved' || $status=='Expired')
	{
		$ioValidation=$revenueValidation->loadByIo($io_id,$period);
		$ioValidation->attributes=array('status'=>$status);
		// $revenueValidation->attributes=array('ios_id'=>$io_id,'period'=>$period,'date'=>$date, 'status'=>$status, 'comment'=>$comment,'validation_token'=>$validation_token);
		if($ioValidation->save())
		{
			//ENVIAR MAIL AQUI
		    echo 'Io #'.$ioValidation->ios_id.' invoiced';
			$log->loadLog($ioValidation->id,$status);
		}
		else 
		    print_r($ioValidation->getErrors());
	}
	elseif($status=='Invoiced')
	    echo 'IO already invoiced';		
	else
		echo 'IO no approved yet '
// }
// else
// 	echo 'Las opperaciones aun no han sido validadas';
?>
<?php 
/* @var $this FinanceController */
/* @var $model Finance */
$date    =date('Y-m-d H:i:s', strtotime('NOW'));
$status  ="Invoiced";

$log=new ValidationLog;
if($revenueValidation= IosValidation::model()->loadByIo($io_id,$period))
{
	if($revenueValidation->status=='Approved' || $revenueValidation->status=='Expired')
	{
		$revenueValidation->attributes=array('status'=>$status);
		if($revenueValidation->save())
		{
			//ENVIAR MAIL AQUI
		    echo 'Io #'.$revenueValidation->ios_id.' invoiced';
			$log->loadLog($revenueValidation->id,$status);
		}
		else 
		    print_r($revenueValidation->getErrors());
	}
	elseif($status=='Invoiced')
	    echo 'IO already invoiced';		
	else
		echo 'IO no approved yet ';
}
else
 	echo 'Las opperaciones aun no han sido validadas';
?>
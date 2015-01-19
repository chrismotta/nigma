<?php 
/* @var $this FinanceController */
/* @var $model Finance */

$date    =date('Y-m-d H:i:s', strtotime('NOW'));
$opportunitiesValidation= new OpportunitiesValidation;
$iosValidation=new IosValidation;
$log=new ValidationLog;
if(!$opportunitiesValidation->checkValidation($opportunities_id,$period))
{
	$opportunitiesValidation->attributes=array('opportunities_id'=>$opportunities_id,'period'=>$period,'date'=>$date);
	if($opportunitiesValidation->save())
	{
	    echo 'Oportunidad aprobada';
		if($iosValidation->checkValidationOpportunities($opportunitie->ios_id,$period))
		{

			$status  ="Validated";
			$comment =null;
			$validation_token=md5($date.$opportunitie->ios_id);
			$iosValidation->attributes=array('ios_id'=>$opportunitie->ios_id,'period'=>$period,'date'=>$date, 'status'=>$status, 'comment'=>$comment,'validation_token'=>$validation_token);
			if($iosValidation->save())
			{
			    echo 'IO Validated';
				$log->loadLog($iosValidation->id,$status);
			}
			else 
			    print_r($iosValidation->getErrors());
		}
	}
	else 
	    echo 'Error al guardar';
}
else
	echo 'La oportunidad ya ha sido validada anteriormente';

?>
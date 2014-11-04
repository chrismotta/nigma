<?php 
/* @var $this FinanceController */
/* @var $model Finance */

$date    =date('Y-m-d H:i:s', strtotime('NOW'));
$opportunitiesValidation= new OpportunitiesValidation;
if(!$opportunitiesValidation->checkValidation($opportunities_id,$period))
{
	$opportunitiesValidation->attributes=array('opportunities_id'=>$opportunities_id,'period'=>$period,'date'=>$date);
	if($opportunitiesValidation->save())
	    echo 'Oportunidad aprobada';
	else 
	    echo 'Error al guardar';
}
else
	echo 'La oportunidad ya ha sido validada anteriormente';

?>
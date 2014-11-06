<?php 
/* @var $this ExternalFormsController */
/* @var $model ExternalForm */

$status='Approved';
$log=new ValidationLog;
switch ($model->status) {
	case 'Approved':
		echo 'Revenue already approved';
		break;
	
	case 'Expired':
		echo 'Revenue is expired';
		break;
	
	default:
		$model->attributes=array('status'=>$status,'comment'=>$comment);
		if($model->save())
		{
			echo 'Revenue Approved';			
			$log->loadLog($model->id,$status);
		}
		else
			print_r($model->getErrors());
		break;
}

?>
<?php 
/* @var $this ExternalFormsController */
/* @var $model ExternalForm */
$status='Disputed';
$log=new ValidationLog;

switch ($model->status) {
	case 'Disputed':
		echo 'Revenue already disputed';
		break;
	
	case 'Expired':
		echo 'Revenue is expired';
		break;
	
	default:
		$model->attributes=array('status'=>$status,'comment'=>$comment);
		if($model->save())
		{
			echo 'Revenue Disputed';
			$log->loadLog($model->id,$status);
		}
		else
			print_r($model->getErrors());
		break;
}

?>
<?php 
/* @var $this ExternalFormsController */
/* @var $model ExternalForm */
$ios->attributes=array('email_validation'=>$email);
if($ios->save())
	echo 'Email changed';
else
	print_r($ios->getErrors());
?>
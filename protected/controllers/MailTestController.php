<?php

class MailTestController extends Controller
{

	public function accessRules()
	{
		return array(
			array('allow',
				'actions'=>array('index'),
				'roles'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	public function actionIndex()
	{
		Yii::log('Log - level: error', 'error', 'system.mail');
		Yii::log('Log - level: mail', 'mail', 'system.mail');

		$mail = new CPhpMailerLogRoute;
		$mail->send(array('no-reply@kickads.mobi', 'matias.cerrotta@kickads.mobi'), 'Custom Mail', 'Custom Message');
	}
}

?>
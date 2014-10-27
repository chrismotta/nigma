<?php

class MailTestController extends Controller
{
	public function actionIndex()
	{
		Yii::log("MailTest", 'error', 'system.mail');
	}
}

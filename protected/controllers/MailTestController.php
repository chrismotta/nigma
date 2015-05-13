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

		$body = '
<p>Dear client:</p>
<p>Please check the statement of your account by following the link below. We will assume that you are in agreement with us on the statement unless you inform us to the contrary by latest (+ 4 DAYS)</p>
<p>LINK</p>
<p>If you weren’t the right contact person to verify the invoice, we ask you to follow the link above and update the information. Do not reply to this email with additional information.</p>
<p>This process allows us to audit the invoice together beforehand and expedite any paperwork required and payment.</p>
<p>Thanks</p>
<p>TheMediaLab</p>
<br/>
<span style="color:#555">
<p>Estimado cliente:</p>
<p>Por favor verificar el estado de su cuenta a través del link a continuación. Se considerara de acuerdo con el estado actual a menos que se nos notifique lo contrario a mas tardar el (+ 4 DAYS)</p>
<p>LINK</p>
<p>Si usted no fuese la persona indicada para hacer esta verificación, le solicitamos ingrese al link anterior y actualice los datos. No responda a este correo con información adicional.</p>
<p>Este proceso nos permite auditar en conjunto la facturación previo a realizar y agilizar en lo posible el intercambio de documentos y el pago.</p>
<p>Gracias</p> 
<p>TheMediaLab</p>
</span>
		';

		$mail = new CPhpMailerLogRoute;
		$mail->send(array('chris@themedialab.co'), 'Custom Mail', $body, true);

		echo 'Enviado '.date('H:m:s');
	}
}

?>
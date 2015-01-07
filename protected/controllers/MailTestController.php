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
Dear client:

Please check the statement of your account by following the link below. We will assume that you are in agreement with us on the statement unless you inform us to the contrary by latest (+ 4 DAYS)

LINK

If you weren’t the right contact person to verify the invoice, we ask you to follow the link above and update the information. Do not reply to this email with additional information.

This process allows us to audit the invoice together beforehand and expedite any paperwork required and payment.

Thanks

KickAds

<hr/>
<span style="color:#555">
Estimado cliente:

Por favor verificar el estado de su cuenta a través del link a continuación. Se considerara de acuerdo con el estado actual a menos que se nos notifique lo contrario a mas tardar el (+ 4 DAYS)

LINK

Si usted no fuese la persona indicada para hacer esta verificación, le solicitamos ingrese al link anterior y actualice los datos. No responda a este correo con información adicional.

Este proceso nos permite auditar en conjunto la facturación previo a realizar y agilizar en lo posible el intercambio de documentos y el pago.

Gracias 

KickAds
</span>
		';

		$mail = new CPhpMailerLogRoute;
		$mail->send(array('christian.motta@kickads.mobi'), 'Custom Mail', $body);
	}
}

?>
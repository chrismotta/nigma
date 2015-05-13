<?php 
/* @var $this ExternalFormsController */
/* @var $model ExternalForm */
$status ="Email Changed";
$log    =new ValidationLog;
$ios->attributes=array('email_validation'=>$email,'zip_code'=>'0');
if($ios->save())
{
	echo $status;
	// Re-envio de mail
	$body = '
					<span style="color:#000">
					  <p>Dear client:</p>
					  <p>Please check the statement of your account by following the link below. We will assume that you are in agreement with us on the statement unless you inform us to the contrary by latest '.date('M j, Y', strtotime('+4 days')).'</p>
					  <p><a href="http://console.themedialab.co/externalForms/revenueValidation/'.$model->validation_token.'">http://console.themedialab.co/externalForms/revenueValidation/'.$model->validation_token.'</a></p>
					  <p>If you weren’t the right contact person to verify the invoice, we ask you to follow the link above and update the information. Do not reply to this email with additional information.</p>
					  <p>This process allows us to audit the invoice together beforehand and expedite any paperwork required and payment.</p>
					  <p>Thanks</p>
					</span>
					<hr style="border: none; border-bottom: 1px solid #999;"/>
					<span style="color:#666">
					  <p>Estimado cliente:</p>
					  <p>Por favor verificar el estado de su cuenta a través del link a continuación. Se considerara de acuerdo con el estado actual a menos que se nos notifique lo contrario a mas tardar el '.date('d-m-Y', strtotime('+4 days')).'</p>
					  <p><a href="http://console.themedialab.co/externalForms/revenueValidation/'.$model->validation_token.'">http://console.themedialab.co/externalForms/revenueValidation/'.$model->validation_token.'</a></p>
					  <p>Si usted no fuese la persona indicada para hacer esta verificación, le solicitamos ingrese al link anterior y actualice los datos. No responda a este correo con información adicional.</p>
					  <p>Este proceso nos permite auditar en conjunto la facturación previo a realizar y agilizar en lo posible el intercambio de documentos y el pago.</p>
					  <p>Gracias</p> 
					  <p><img src="http://themedialab.mobi/logo/logo_tml_181x56.png"/></p>
					</span>
                	';
            $subject = 'TheMediaLab - Statement of account as per '.date('M j, Y');
      
			$email_validation=is_null($ios->email_validation) ? $ios->email_adm : $ios->email_validation;
			
            $mail = new CPhpMailerLogRoute;   
            $mail->send(array($email_validation), $subject, $body); 
	 
	
	$log->loadLog($model->id,$status);
}
else
	print_r($ios->getErrors());
?>
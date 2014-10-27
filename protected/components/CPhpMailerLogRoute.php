<?php 

class CPhpMailerLogRoute extends CEmailLogRoute
{
    protected function sendEmail($email, $subject, $message)
    {
        // $mail             = new PHPMailer();
        $mail = Yii::createComponent('application.extensions.mailer.EMailer');
        $mail->IsSMTP();
        $mail->Host       = "email-smtp.us-east-1.amazonaws.com";
        $mail->SMTPAuth   = true;
        $mail->SMTPSecure = "tls";
        $mail->Port       = 25; // 25, 465 or 587
        $mail->Username   = 'AKIAIQTRLJHEZETZDRSQ';
        $mail->Password   = 'Ag/ctgxpxYGrnQPxiahJiLNKldgoBJBr2M9mtf/Hz//F';
        $mail->Subject    = $subject;
        $mail->Body       = $message;
        $mail->addAddress($email);
        $mail->send();
    }
}

?>
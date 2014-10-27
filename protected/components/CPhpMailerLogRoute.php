<?php 

class CPhpMailerLogRoute extends CEmailLogRoute
{

    private $_config = array(
        'From'       => 'matias.cerrotta@kickads.mobi',
        'FromName'   => 'Kickads AdServer',
        'Host'       => "email-smtp.us-east-1.amazonaws.com",
        'SMTPAuth'   => true,
        'SMTPSecure' => "tls",
        'Port'       => 25,
        'Username'   => "AKIAIQTRLJHEZETZDRSQ",
        'Password'   => "Ag/ctgxpxYGrnQPxiahJiLNKldgoBJBr2M9mtf/Hz//F",
        'CharSet'    => "UTF-8",
    ); // Default values


    protected function sendEmail($email, $subject, $message)
    {
        $configuration = $this->getConfig();
        $mailer        = Yii::createComponent('application.extensions.mailer.EMailer');

        foreach ($configuration as $attribute => $value) {
            $mailer->$attribute = $value;
        }

        $mailer->IsSMTP();
        $mailer->AddAddress($email);
        $mailer->Subject = $subject;
        $mailer->Body    = $message;
        $mailer->Send();
    }

    /*
     * Public function for sendEmail
     */
    public function send($emails, $subject, $message)
    {
        foreach ($emails as $email) {
            $this->sendEmail($email, $subject, $message);
        }
    }


     /**
     * Get the configurations
     *
     * @return array configurations for the send mechanism.
     */
    public function getConfig()
    {
        return $this->_config;
    }

 
    /**
     * Set all configurations inserted
     *
     * @param array() $value list of configurations to the PHPMailer send mechanism.
     *
     * @return void
     */
    public function setConfig($value)
    {
        $this->_config = array_merge($this->_config, $value);
    }

}

?>
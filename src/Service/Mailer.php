<?php

namespace Riotoon\Service;

use PHPMailer\PHPMailer\PHPMailer;

class Mailer
{
    private PHPMailer $mail;
    private $my_mail;
    private $port;
    private $host;

    public function __construct()
    {
        $this->mail = new PHPMailer(true);
        $this->my_mail = $_ENV['MY_MAIL'];
        $this->host = $_ENV['MAIL_HOST'];
        $this->port = $_ENV['MAIL_PORT'];
    }

    public function send(string $email, string $name, string $subject, string $message) {
        $this->mail->isSMTP();
        $this->mail->Host = $this->host;
        $this->mail->SMTPAuth = true;
        $this->mail->Port = $this->port;
        // $this->mail->SMTPSecure = "ssl";

        //sender information
        $this->mail->setFrom($this->my_mail, 'RioToon');
        $this->mail->addAddress($email, $name);
        $this->mail->CharSet = 'utf-8';

        $this->mail->isHTML(true);

        $this->mail->Subject = $subject;
        $this->mail->Body = $message;

        // Send mail   
        if (!$this->mail->send())
            BuildErrors::setErrors('mail', "Impossible d'envoyé un email à l'addresse $email");

        $this->mail->smtpClose();
    }
}
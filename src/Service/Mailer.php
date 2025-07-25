<?php

namespace Riotoon\Service;

use PHPMailer\PHPMailer\PHPMailer;

final class Mailer
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

    public function send(string $email, string $name, string $subject, string $message)
    {
        $this->mail->isSMTP();
        $this->mail->Host = $this->host; // smtp.gmail.com
        $this->mail->Port = $this->port; // 587 using gmail
        // If you use gmail, uncomment this section
        /* $this->mail->SMTPAuth = true;
        $this->mail->SMTPSecure = "ssl" or PHPMailer::ENCRYPTION_STARTTLS
        $this->mail->Username = ''; // your gmail (mail)
        $this->mail->Password = ''; // your password to google gmail
        */
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

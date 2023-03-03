<?php 
namespace NeeZiaa;

use NeeZiaa\Router\Router\Utils\main;
use PHPMailer\PHPMailer\PHPMailer;

class Mail {

    public static function send_mail(string $email, string $subject, string $body = '', string $altbody = ''){
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Invalid email address');
        }
        
        if (empty($subject)) {
            throw new InvalidArgumentException('Subject cannot be empty');
        }
        
        // if body is empty, use default message
        if (empty($body)) {
            $body = 'This is the default message.';
        }
        
        $mail = new PHPMailer(true);
  
        try {
            //Server settings
            $mail->isSMTP();                                              //Send using SMTP
            $mail->Host       = Main::env()['MAIL_HOST'];                      //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                     //Enable SMTP authentication
            $mail->Username   = Main::env()['MAIL_USER'];                 //SMTP username
            $mail->Password   = Main::env()['MAIL_PASSWD'];               //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;              //Enable implicit TLS encryption
            $mail->Port       = Main::env()['MAIL_PORT'];                 //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
  
            //Recipients
            $mail->setFrom('');
            $mail->addAddress($email);
        
            //Content
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $body;
            $mail->AltBody = $altbody;
        
            $mail->send();
            $sendResult = 'Message has been sent';
        } catch (Exception $e) {
            $sendResult = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
  
        return $sendResult;
  
    }
}

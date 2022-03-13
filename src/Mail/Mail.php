<?php 
namespace NeeZiaa;

use NeeZiaa\main;

class Mail {

    public static function send_mail($email,$subject,$body,$altbody){
        $mail = new PHPMailer(true);
  
        try {
            //Server settings
            $mail->isSMTP();                                              //Send using SMTP
            $mail->Host       = Main::env()['SMTP'];                      //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                     //Enable SMTP authentication
            $mail->Username   = Main::env()['MAIL_USER'];                 //SMTP username
            $mail->Password   = Main::env()['MAIL_PASSWD'];               //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;              //Enable implicit TLS encryption
            $mail->Port       = Main::env()['SMTP_PORT'];                 //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
  
            //Recipients
            $mail->setFrom('');
            $mail->addAddress($email);
        
            //Attachments
            //$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
            //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name
        
            //Content
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $body;
            $mail->AltBody = $altbody;
        
            $mail->send();
            $resultSend = 'Message has been sent';
        } catch (Exception $e) {
            $resultSend = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
  
        return $resultSend;
  
      }

}
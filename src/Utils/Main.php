<?php
namespace NeeZiaa;

use Dotenv\Dotenv;
use PHPMailer\PHPMailer\PHPMailer;

class Main {

    static function env() 
    {
        $dotenv = Dotenv::createImmutable(dirname(dirname(__DIR__)));
        $dotenv->load();

        return $_ENV;
    }

    static function getIp(){
        if(!empty($_SERVER['HTTP_CLIENT_IP'])){
          $ip = $_SERVER['HTTP_CLIENT_IP'];
        }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
          $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }else{
          $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
      }

    static function send_mail($email,$subject,$body,$altbody){
      $mail = new PHPMailer(true);

      try {
          //Server settings
          $mail->isSMTP();                                            //Send using SMTP
          $mail->Host       = 'smtpserver';                     //Set the SMTP server to send through
          $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
          $mail->Username   = '';                     //SMTP username
          $mail->Password   = 'ionosmdp03-';                               //SMTP password
          $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
          $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

          //Recipients
          $mail->setFrom('email');
          $mail->addAddress($email);               //Name is optional
      
          //Attachments
          //$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
          //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name
      
          //Content
          $mail->isHTML(true);                                  //Set email format to HTML
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
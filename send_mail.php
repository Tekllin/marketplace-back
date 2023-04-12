<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header('Access-Control-Allow-Credentials', true);
header('Access-Control-Allow-Methods: GET');
header("Content-Type: application/json; charset=UTF-8");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'src\Exception.php';
require 'src\PHPMailer.php'; 
require 'src\SMTP.php';



    $mail = new PHPMailer(true);
    try{
        //Server settings
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->Host = 'frweb7.pulseheberg.net';
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = 'ssl';
        $mail->Username = 'altameos@dantech.fr';
        $mail->Password = 'jJMqCgjZSL7xXCY';
        $mail->Port = '465';
        $mail->Charset = "UTF-8";
        $mail->Encoding = 'base64';
    
        //Recipients
        $mail->setFrom('no-reply@altameos.fr', 'Altameos.fr');
        $mail->addAddress($_GET['email']);
        //$mail->addReplyTo('
        //$mail->addCC('
        //$mail->addBCC('
    
        //Attachments
        //$mail->addAttachment('/var/tmp/file.tar.gz');
        //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');
    
        //Content
        $mail->isHTML(true);
        $mail->Subject = 'Altameos.fr - Vérification de votre adresse email';
        $mail->Body    = 'Bonjour, <br><br> Veuillez cliquer sur
        <a href="http://localhost:4200/verify?token='.$_GET['token'].'&email='.$_GET['email'].'">ce lien</a> pour vérifier votre adresse email.';
        $mail->AltBody = 'Bonjour, Veuillez cliquer sur ce lien pour vérifier votre adresse email: http://localhost/altameos/verify.php?token='.$_GET['token'].'&email='.$_GET['email'].'';
    
        $mail->send();
        echo 'Message has been sent';
    }
    catch(Exception $e){
        echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
    }



?>

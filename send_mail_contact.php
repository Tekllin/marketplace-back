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
        $mail->addAddress('micheletmax07@gmail.com');
        //$mail->addReplyTo('
        //$mail->addCC('
        //$mail->addBCC('
    
        //Attachments
        //$mail->addAttachment('/var/tmp/file.tar.gz');
        //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');
    
        //Content
        $mail->isHTML(true);
        $mail->Subject = 'Altameos.fr - Message Contact';
        $mail->Body    = 'Bonjour , <br><br> Vous venez de recevoir un message de la part de '.$_GET['nomUser'].' '.$_GET['prenomUser'].' <br><br> Son adresse email est : '.$_GET['emailUser'].' <br><br> Son numero de telephone est : '.$_GET['telUser'].' <br><br> Son message est le suivant : <br><br> '.$_GET['messageUser'].' <br><br> Cordialement, <br><br> L\'equipe Altameos.fr';
        
        $mail->AltBody = 'Bonjour , \n\n Vous venez de recevoir un message de la part de '.$_GET['nomUser'].' \n\n Son adresse email est : '.$_GET['emailUser'].' \n\n Son numéro de telephone est : '.$_GET['telUser'].' \n\n Son message est le suivant : \n\n '.$_GET['messageUser'].' \n\n Cordialement, \n\n L\'equipe Altameos.fr';
    
        $mail->send();
        echo 'Message has been sent';
    }
    catch(Exception $e){
        echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
    }



?>

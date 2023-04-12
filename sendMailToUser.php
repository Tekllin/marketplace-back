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
        $mail->Subject = 'Altameos.fr - Confirmation de commande';
        $mail->Body    = 'Bonjour, <br><br> Votre commande a été prise en compte et est en cours de préparation. <br><br> Vous pouvez télécharger votre facture en cliquant sur le lien suivant : <br><br>
        <a href="http://localhost/api/facturesCartPayment/' . $_GET['numFacture'] . '.pdf">#' . $_GET['numFacture'] . '</a>';
        $mail->AltBody = 'Bonjour, Votre commande a été prise en compte et est en cours de préparation. Vous pouvez télécharger votre facture en cliquant sur le lien suivant : http://localhost/api/facturesCartPayment/' . $_GET['numFacture'] . '.pdf';
    
        $mail->send();
        echo 'Message has been sent';
    }
    catch(Exception $e){
        echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
    }



?>

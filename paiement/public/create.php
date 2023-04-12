<?php

require_once '../../vendor/autoload.php';
require_once '../../db_connect.php';
require_once '../secrets.php';

\Stripe\Stripe::setApiKey($stripeSecretKey);


function calculateOrderAmount() {
    try {
        $database = new Operations();
        $conn = $database->dbConnection();
        $sql = "SELECT prixOffre FROM `typeOffre` WHERE idTypeOffre =".$_GET['id'];
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        if($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $total = $row['prixOffre'];
            return intval($total * 100);
        } else {
            return 1;
        }
        
    } catch (PDOException $pe) {
       return 1;
    }
}

try {
    // retrieve JSON from POST body
    $jsonStr = file_get_contents('php://input');
    
    $uid = $_GET['uid'];
    $hash = $_GET['session'];

    $database = new Operations();
    $conn = $database->dbConnection();
    $sql = "SELECT idUserSession FROM session_paiement WHERE hashSession ='".$hash."'";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    //Check if $uid is present in $data. $uid is crypted in md5 and the datas returned aren't crypted
    if($uid == md5($data['idUserSession'])) {

        $uid = $data['idUserSession'];
        $sql = "SELECT idAdresse, libelleAdresse, cpAdresse, villeAdresse, paysAdresse FROM facturations WHERE idUserAdresse =".$uid."";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        // Create a PaymentIntent with amount and currency
        $paymentIntent = \Stripe\PaymentIntent::create([
            'amount' => calculateOrderAmount(),
            'currency' => 'eur',
            'metadata' => [
                'userid' => $uid,
                'prdid' => $_GET['id'],
                'idFact' => $data['idAdresse'],
                'adresse' => $data['libelleAdresse'],
                'cp' => $data['cpAdresse'],
                'ville' => $data['villeAdresse'],
                'pays' => $data['paysAdresse'],
            ],
            'automatic_payment_methods' => [
                'enabled' => true,
            ],
        ]);

        $output = [
            'clientSecret' => $paymentIntent->client_secret,
            'prix' => calculateOrderAmount()
        ];
    } else {
       echo "<script>alert('Erreur de session. Veuillez réessayer.');</script>";
    }
    echo json_encode($output);
} catch (Error $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
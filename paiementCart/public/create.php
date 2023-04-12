<?php

require_once '../../vendor/autoload.php';
require_once '../../db_connect.php';
require_once '../secrets.php';

\Stripe\Stripe::setApiKey($stripeSecretKey);


function calculateOrderAmount() {
    try {
        $database = new Operations();
        $conn = $database->dbConnection();
        $sql = "SELECT idPanier, idProduit, libelleProduit, prixUnitHT, pourcTva, poidsProduit, qtProduit
                FROM panier
                INNER JOIN produits ON produits.idProduit = panier.idProduitPanier
                INNER JOIN tva ON tva.idTVA = produits.idTVAProduit
                WHERE MD5(idUser) = '" . $_GET['uid'] . "'";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        if($stmt->rowCount() > 0) {
            $data = null;
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $total = 0;

            foreach ($data as $key => $value) {
                $prixUnitTTC = $value['prixUnitHT'] * (1 + $value['pourcTva'] / 100);
                $total += $prixUnitTTC * $value['qtProduit'];
            }
            return $total * 100;
        } else {
            return 1;
        }
        
    } catch (PDOException $pe) {
       return 1;
    }
}


function calculateOrderWeight() {
    try {
        $database = new Operations();
        $conn = $database->dbConnection();
        $sql = "SELECT idPanier, idProduit, libelleProduit, prixUnitHT, pourcTva, poidsProduit, qtProduit
                FROM panier
                INNER JOIN produits ON produits.idProduit = panier.idProduitPanier
                INNER JOIN tva ON tva.idTVA = produits.idTVAProduit
                WHERE MD5(idUser) = '" . $_GET['uid'] . "'";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        if($stmt->rowCount() > 0) {
            $data = null;
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $poidsTotal = 0;

            foreach ($data as $key => $value) {
                $poidsTotal += $value['poidsProduit'] * $value['qtProduit'];
            }
            return ($poidsTotal);
        } else {
            return 1;
        }
        
    } catch (PDOException $pe) {
        return 1;
    }
}


function calculateOrderShippingFees($codePays, $idTransporteur, $totalWeight) {
    $totalWeight = $totalWeight / 1000;
    
    $database = new Operations();
    $conn = $database->dbConnection();

    $sql = "SELECT idContinent
            FROM continent
            INNER JOIN pays ON continent.idContinent = pays.idContinentPays
            WHERE codePays = '" . $codePays . "'";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    if($stmt->rowCount() > 0) {
        $idContinent = $stmt->fetch(PDO::FETCH_ASSOC)['idContinent'];
    } else {
        return 1;
    }


    $tarifTotal = 0;

    while ($totalWeight > 30){
        $poids = 30;
        $sql = "SELECT tarif
            FROM fraisdeport
            WHERE idTransporteurFdp = '" . $idTransporteur . "' AND idContinentFdp = '" . $idContinent . "' AND poids = '" . $poids . "'";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
        if($stmt->rowCount() > 0) {
            $data = null;
            $data = $stmt->fetch(PDO::FETCH_ASSOC);

            $tarifTotal += $data['tarif'];
            $totalWeight -= $poids;
        } else {
            return 1;
        }
    }

    if ($totalWeight <= 1) {
        $poids = 1;
    } elseif ($totalWeight <= 2){
        $poids = 2;
    } elseif ($totalWeight <= 5){
        $poids = 5;
    } elseif ($totalWeight <= 10){
        $poids = 10;
    } elseif ($totalWeight <= 15){
        $poids = 15;
    } elseif ($totalWeight <= 20){
        $poids = 20;
    } elseif ($totalWeight <= 25){
        $poids = 25;
    } elseif ($totalWeight <= 30){
        $poids = 30;
    }

    $sql = "SELECT tarif
            FROM fraisdeport
            WHERE idTransporteurFdp = '" . $idTransporteur . "' AND idContinentFdp = '" . $idContinent . "' AND poids = '" . $poids . "'";

    $stmt = $conn->prepare($sql);
    $stmt->execute();

    if($stmt->rowCount() > 0) {
        $data = null;
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        $tarifTotal += $data['tarif'];
    }

    if ($tarifTotal == 0) {
        return 1;
    } else {
        $tarifTotal = $tarifTotal * 100;
        return intval($tarifTotal);
    }
}

try {
    // retrieve JSON from POST body
    $jsonStr = file_get_contents('php://input');
    
    $uid = $_GET['uid'];
    $hash = $_GET['session'];
    $idTransporteur = $_GET['idTransporteur'];
    $codePays = $_GET['codePays'];

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
            'amount' => calculateOrderAmount() + calculateOrderShippingFees($codePays, $idTransporteur, calculateOrderWeight()),
            'currency' => 'eur',
            'metadata' => [
                'userid' => $uid,
                'idFact' => $data['idAdresse'],
                'adresse' => $data['libelleAdresse'],
                'cp' => $data['cpAdresse'],
                'ville' => $data['villeAdresse'],
                'pays' => $data['paysAdresse'],
                'weight' => calculateOrderWeight(),
                'shippingFees' => calculateOrderShippingFees($codePays, $idTransporteur, calculateOrderWeight()),
            ],
            'automatic_payment_methods' => [
                'enabled' => true,
            ],
        ]);

        $output = [
            'clientSecret' => $paymentIntent->client_secret,
            'prix' => (calculateOrderAmount() + calculateOrderShippingFees($codePays, $idTransporteur, calculateOrderWeight()))
        ];
        
    } else {
       echo "<script>alert('Erreur de session. Veuillez réessayer.');</script>";
    }
    echo json_encode($output);
} catch (Error $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
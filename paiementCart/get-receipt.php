<?php
error_reporting(0);
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header('Access-Control-Allow-Credentials', true);
header('Access-Control-Allow-Methods: GET');
header("Content-Type: application/json; charset=UTF-8");

if($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode([
        'success' => 0,
        'message' => 'Bad Request Detected.'
    ]);
    exit;
}
require_once '../vendor/autoload.php';
require_once '../db_connect.php';
require_once './secrets.php';

$database = new Operations();
$conn = $database->dbConnection();
try {
    $data = array();
    \Stripe\Stripe::setApiKey($stripeSecretKey);
    $stripe = new \Stripe\StripeClient($stripeSecretKey);
    $paymentIntent = $stripe->paymentIntents->retrieve(
        $_GET['payment'],
        []
    );
    
    $date = date('y-m-d');
    $id = $paymentIntent['metadata']['userid'];
    $fact = $paymentIntent['metadata']['idFact'];
    $hash = md5($_GET['payment']);
    $hash = strtoupper(substr($hash, 0, 10));
    $prix = intval($paymentIntent['amount']);
    $fraisDePort = $paymentIntent['metadata']['shippingFees'];

    $sql = "SELECT numFacture FROM commandeFacture WHERE numFacture = '$hash'";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    if($stmt->rowCount() > 0){
        try {
            $sql = "SELECT idPanier, idProduit, libelleProduit, prixUnitHT, pourcTva, poidsProduit, qtProduit
                    FROM panier
                    INNER JOIN produits ON produits.idProduit = panier.idProduitPanier
                    INNER JOIN tva ON tva.idTVA = produits.idTVAProduit
                    WHERE idUser = '" . $id . "'";
            $stmt = $conn->prepare($sql);
            $stmt->execute();

            
            if ($stmt->rowCount() > 0) {
                $data2 = null;
                $data2 = $stmt->fetchAll(PDO::FETCH_ASSOC);

                foreach ($data2 as $key => $value) {
                    try {
                        $sql = "INSERT INTO commander(numFactComm, idUserComm, idProduitComm, qtProduitComm)
                                VALUES('$hash','$id','$value[idProduit]','$value[qtProduit]')";
                        $stmt = $conn->prepare($sql);
                        $stmt->execute();
                    } catch (Exception $e) {
                       
                    }
                }
            }

            
        } catch (Exception $e) {
            echo json_encode([
                'success' => 1,
                'message' => $e->getMessage(),
            ]);
            exit;
        }
        $adresse = array();
        array_push($adresse, [
            'libelleAdresse'=>$paymentIntent['metadata']['adresse'], 
            'cpAdresse'=>$paymentIntent['metadata']['cp'], 
            'villeAdresse'=>$paymentIntent['metadata']['ville'], 
            'paysAdresse'=>$paymentIntent['metadata']['pays'], 
            ]);
        $sql = "SELECT nomUser, prenomUser, emailUser FROM user WHERE idUser = '$id'";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $user = array();
        array_push($user, [
            'nomUser'=>$row['nomUser'], 
            'prenomUser'=>$row['prenomUser'], 
            'emailUser'=>$row['emailUser'], 
            ]);
        
        
        array_push($data, [
            'hash'=>$hash, 
            'prix'=>$prix, 
            'adresse'=>$adresse,
            'user'=>$user,
            'date'=>$date,
            'fraisDePort'=>$fraisDePort,
            ]);

        echo json_encode([
            'success' => 1,
            'message' => 'Payment Intent retrieved successfully.',
            'data' => $data,
        ]);
        exit;



    } else {
        $sql = "SELECT idAdresse FROM livraisons WHERE idUserAdresse = '$id'";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $idLiv = $row['idAdresse'];

        $prixTotalFacture = $prix / 100;

        $sql = "INSERT INTO commandeFacture(numFacture, dateFacture, idLivraisonFacture, idFacturationFacture, idUserFacture, idEtatCommandeFacture, prixTotalFacture, isCreditable)
                VALUES ('$hash','$date','$idLiv','$fact','$id','1','$prixTotalFacture', '1')";
        $stmt = $conn->prepare($sql);
        $stmt->execute();


        $sql = "SELECT idPanier, idProduit, libelleProduit, prixUnitHT, pourcTva, poidsProduit, qtProduit
                FROM panier
                INNER JOIN produits ON produits.idProduit = panier.idProduitPanier
                INNER JOIN tva ON tva.idTVA = produits.idTVAProduit
                WHERE idUser = '" . $id . "'";
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $data3 = null;
            $data3 = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($data3 as $key => $value) {
                try {
                    $sql = "INSERT INTO commander(numFactComm, idUserComm, idProduitComm, qtProduitComm)
                            VALUES('$hash','$id','$value[idProduit]','$value[qtProduit]')";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute();
                } catch (Exception $e) {
                   
                }
            }
        }

        $sql = "SELECT libelleAdresse, cpAdresse, villeAdresse, paysAdresse FROM facturations WHERE idAdresse = '$fact'";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $adresse = array();
        array_push($adresse, [
            'libelleAdresse'=>$paymentIntent['metadata']['adresse'], 
            'cpAdresse'=>$paymentIntent['metadata']['cp'], 
            'villeAdresse'=>$paymentIntent['metadata']['ville'], 
            'paysAdresse'=>$paymentIntent['metadata']['pays'], 
            ]);
        $sql = "SELECT nomUser, prenomUser, emailUser FROM user WHERE idUser = '$id'";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $user = array();
        array_push($user, [
            'nomUser'=>$row['nomUser'], 
            'prenomUser'=>$row['prenomUser'], 
            'emailUser'=>$row['emailUser'], 
            ]);
        
        
        array_push($data, [
            'hash'=>$hash, 
            'prix'=>$prix, 
            'adresse'=>$adresse,
            'user'=>$user,
            'date'=>$date,
            'fraisDePort'=>$fraisDePort,
            ]);

        echo json_encode([
            'success' => 1,
            'message' => 'Payment Intent retrieved successfully.',
            'data' => $data,
        ]);
        exit;
    }
    
       
   
   
} catch (Exception $e) {
    echo json_encode([
        'success' => 0,
        'message' => $e->getMessage()
    ]);
}

?>
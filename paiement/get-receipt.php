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
    
    $date = date('d-m-y');
    $id = $paymentIntent['metadata']['userid'];
    $prdid = $paymentIntent['metadata']['prdid'];
    $fact = $paymentIntent['metadata']['idFact'];
    $hash = md5($_GET['payment']);
    $hash = strtoupper(substr($hash, 0, 10));
    $prix = intval($paymentIntent['amount']);

    $sql = "SELECT idAnnonceFacture FROM annonceFacture WHERE idAnnonceFacture = '$_GET[payment]'";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    if($stmt->rowCount() > 0){
        try {
            $sql = "INSERT INTO concerner VALUES('$_GET[payment]','$prdid')";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
        } catch (Exception $e) {

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

        $sql = "DELETE FROM session_paiement WHERE idUserSession = $id";
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        $anno = array();
        array_push($anno, [
            'nomAnnonceur'=>$row['nomUser'], 
            'prenomAnnonceur'=>$row['prenomUser'], 
            'emailAnnonceur'=>$row['emailUser'], 
            ]);
        
        
        array_push($data, [
            'hash'=>$hash, 
            'prix'=>$prix, 
            'prdid'=>$prdid,
            'adresse'=>$adresse,
            'anno'=>$anno,
            'date'=>$date,
            ]);

        echo json_encode([
            'success' => 1,
            'message' => 'Payment Intent retrieved successfully.',
            'data' => $data,
        ]);
        exit;
    } else {
        $sql = "INSERT INTO annonceFacture(refFacture, idAnnonceFacture, idAdresseAnnoFacture, idAnnonceurAnnoFacture, dateAnnoFacture)
        VALUES ('$hash','$_GET[payment]','$fact','$id','$date')";
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        $sql = "INSERT INTO concerner VALUES('$_GET[payment]','$prdid')";
        $stmt = $conn->prepare($sql);
        $stmt->execute();

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

        $sql = "DELETE FROM session_paiement WHERE idUserSession = $id";
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        $anno = array();
        array_push($anno, [
            'nomAnnonceur'=>$row['nomUser'], 
            'prenomAnnonceur'=>$row['prenomUser'], 
            'emailAnnonceur'=>$row['emailUser'], 
            ]);
        
        
        array_push($data, [
            'hash'=>$hash, 
            'prix'=>$prix, 
            'prdid'=>$prdid,
            'adresse'=>$adresse,
            'anno'=>$anno,
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
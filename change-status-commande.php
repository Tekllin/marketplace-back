<?php
error_reporting(0);
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$method = $_SERVER['REQUEST_METHOD'];

if ($method == "OPTIONS") {
    die();
}
 

if($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode([
        'success' => 0,
        'message' => 'Bad Request Detected.'
    ]);
    exit;
}

require_once 'db_connect.php';
$database = new Operations();
$conn = $database->dbConnection();


try {
    
    $idEtat = $_GET['idStatus'];
    $idCommande = $_GET['idCommande'];
    $message = strip_tags($_GET['message']);
    $sql = "UPDATE commandefacture
    SET idEtatCommandeFacture = '$idEtat', message='$message'
    WHERE numFacture = '$idCommande'";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    $sql = "SELECT emailUser FROM user
    INNER JOIN commandeFacture ON idUser = idUserFacture
    WHERE numFacture = '$idCommande'";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    $sql = "SELECT libelleEtatCommande FROM etatcommande
    WHERE idEtatCommande = '$idEtat'";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $data2 = $stmt->fetch(PDO::FETCH_ASSOC);



    echo json_encode([
        'success' => 200,
        'data' => $data,
        'data2' => $data2

    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => 405,
        'data' => $e->getMessage()
    ]);
    exit;
}
?>
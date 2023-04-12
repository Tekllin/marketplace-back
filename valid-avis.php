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

require_once 'db_connect.php';
$database = new Operations();
$conn = $database->dbConnection();

if(isset($_GET['id'])) {
    $idUser = filter_var($_GET['idUser'], FILTER_VALIDATE_INT, [
        'options' => [
            'default' => 'all_users',
            'min_range' => 1
        ]
    ]);
}

try {
    $idProduit = $_GET['idProduit'];
    $idUser = $_GET['idUser'];

    $sql = "UPDATE avis SET valid = 1 WHERE idProduitAvis = '".$idProduit."' AND idUserAvis = '".$idUser."'";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    echo json_encode([
        'success' => 200,
        'message' => "Avis validé",
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => 0,
        'message' => "Erreur lors de la validation de l'avis"
    ]);
}

?>
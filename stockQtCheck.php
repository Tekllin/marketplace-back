<?php

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

try {
    $sql = "SELECT qtStock
            FROM produits
            WHERE idProduit = '" . $_GET['idProduct'] . "'";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    if($stmt->rowCount() > 0) {
        $data = null;
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        echo json_encode([
            'success' => 1,
            'data' => $data
        ]);
    } else {
        echo json_encode([
            'success' => 0,
            'message' => "Le produit n'existe pas."
        ]);
    }
} catch (Exception $e) {
    echo json_encode([
        'success' => 0,
        'message' => $e->getMessage()
    ]);
}


?>
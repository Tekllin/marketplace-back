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
    $sql = "SELECT idUser, idProduitPanier
            FROM panier
            WHERE idUser = '" . $_GET['idUser'] . "' AND idProduitPanier = '" . $_GET['idProduct'] . "'";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    if($stmt->rowCount() > 0) {
        $sql = "DELETE FROM panier
                WHERE idUser = '" . $_GET['idUser'] . "' AND idProduitPanier = '" . $_GET['idProduct'] . "'";
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        echo json_encode([
            'success' => 1,
            'message' => "Le produit a été supprimé du panier."
        ]);
    } else {
        echo json_encode([
            'success' => 0,
            'message' => "Le produit n'existe pas dans le panier."
        ]);
    }
} catch (Exception $e) {
    echo json_encode([
        'success' => 0,
        'message' => $e->getMessage()
    ]);
}


?>
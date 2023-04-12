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

$erreurStock = false;

$sql = "SELECT idUser, idProduitPanier, qtProduit
        FROM panier
        WHERE idUser = '" . $_GET['idUser'] . "'";
$stmt = $conn->prepare($sql);
$stmt->execute();

if($stmt->rowCount() > 0) {
    $data = null;
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach($data as $row) {
        $idProduit = $row['idProduitPanier'];
        $qtProduitDemandee = $row['qtProduit'];

        $sql = "SELECT qtStock
                FROM produits
                WHERE idProduit = '" . $idProduit . "'";
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        $qtStock = 0;
        $qtStock = $stmt->fetch(PDO::FETCH_ASSOC)['qtStock'];

        if($stmt->rowCount() > 0) {
            if ($qtStock < $qtProduitDemandee) {
                $erreurStock = true;
            } else {
                // Tout est ok
            }
        } else {
            $erreurStock = true;
        }
    }

    if ($erreurStock == true) {
        echo json_encode([
            'success' => 0,
            'message' => 'Stock insuffisant.'
        ]);
    } else {
        echo json_encode([
            'success' => 1,
            'message' => 'Stock suffisant.'
        ]);
    }
} else {
    echo json_encode([
        'success' => 0,
        'message' => 'Erreur'
    ]);
}


?>
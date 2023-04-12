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

        $sql = "UPDATE produits
                SET qtStock = qtStock + " . $qtProduitDemandee . "
                WHERE idProduit = '" . $idProduit . "'";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
    }

    echo json_encode([
        'success' => 1,
        'message' => 'Produits remis en stock'
    ]);

} else {
    echo json_encode([
        'success' => 0,
        'message' => 'Erreur'
    ]);
}


?>